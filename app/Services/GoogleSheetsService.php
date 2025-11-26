<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\Log;

class GoogleSheetsService
{
    protected $client;
    protected $service;
    protected $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = config('services.google.spreadsheet_id');
        $credentialsPath = base_path(config('services.google.sheets_credentials_path'));

        if (!config('services.google.sheets_credentials_path') || !is_file($credentialsPath)) {
            Log::error("Google Sheets credentials file not found or invalid at: " . $credentialsPath);
            return;
        }

        $this->client = new Client();
        $this->client->setAuthConfig($credentialsPath);
        $this->client->addScope(Sheets::SPREADSHEETS);
        $this->service = new Sheets($this->client);
    }

    public function getService()
    {
        return $this->service;
    }

    /**
     * Sync an account to the Google Sheet (Insert or Update)
     */
    public function syncAccount(ChartOfAccount $account)
    {
        if (!$this->service) return;

        try {
            // 1. Check if account exists in the sheet
            Log::info("Searching for account {$account->account_number} in Sheet1...");
            $rowIndex = $this->findRowIndex($account->account_number);
            Log::info("Found row index: " . ($rowIndex ?? 'NULL'));

            Log::info("Syncing Account: {$account->account_number}", [
                'is_parent' => $account->is_parent,
                'mapped_value' => $account->is_parent ? 'Parent' : 'Sub'
            ]);

            $values = [
                $account->account_number,                    // A: Account Code
                $account->account_group,                     // B: Account Type
                $account->parent_account_number ?? '',        // C: Parent Account
                $account->account_type,                       // D: Account Title
                $account->is_parent ? 'Parent' : 'Sub',      // E: Class (Parent/Sub)
                '✓',                                          // F: Postable? (always true for now)
                $account->is_active ? '✓' : '✗',            // G: Active?
                $account->description ?? ''                   // H: Notes
            ];

            $body = new ValueRange([
                'values' => [$values]
            ]);
            $params = ['valueInputOption' => 'RAW'];

            // For updates, update only app-managed columns (D, E, G, H)
            // Leave manually maintained columns (A, B, C, F) untouched
            if ($rowIndex) {
                // UPDATE columns D, E, G, H
                Log::info("Updating app-managed columns for row {$rowIndex}...");
                
                // Column D: Account Title
                $bodyD = new ValueRange(['values' => [[$account->account_type]]]);
                $this->service->spreadsheets_values->update($this->spreadsheetId, 'Sheet1!D' . $rowIndex, $bodyD, $params);
                
                // Column E: Class (Parent/Sub)
                $bodyE = new ValueRange(['values' => [[$account->is_parent ? 'Parent' : 'Sub']]]);
                $this->service->spreadsheets_values->update($this->spreadsheetId, 'Sheet1!E' . $rowIndex, $bodyE, $params);
                
                // Column G: Active
                $bodyG = new ValueRange(['values' => [[$account->is_active ? '✓' : '✗']]]);
                $this->service->spreadsheets_values->update($this->spreadsheetId, 'Sheet1!G' . $rowIndex, $bodyG, $params);
                
                // Column H: Notes
                $bodyH = new ValueRange(['values' => [[$account->description ?? '']]]);
                $this->service->spreadsheets_values->update($this->spreadsheetId, 'Sheet1!H' . $rowIndex, $bodyH, $params);
                
                Log::info("Update successful.");
            } else {
                // APPEND new row with all data
                Log::info("Appending new row...");
                $range = 'Sheet1!A:H';
                $this->service->spreadsheets_values->append($this->spreadsheetId, $range, $body, $params);
                Log::info("Append successful.");
            }

        } catch (\Exception $e) {
            Log::error("Google Sheets Sync Error: " . $e->getMessage());
        }
    }

    /**
     * Delete an account from the Google Sheet
     */
    public function deleteAccount($accountNumber)
    {
        if (!$this->service) return;

        try {
            $rowIndex = $this->findRowIndex($accountNumber);

            if ($rowIndex) {
                // Google Sheets API deleteDimension request
                $batchUpdateRequest = new \Google\Service\Sheets\BatchUpdateSpreadsheetRequest([
                    'requests' => [
                        'deleteDimension' => [
                            'range' => [
                                'sheetId' => 0, // Assuming first sheet (GID 0)
                                'dimension' => 'ROWS',
                                'startIndex' => $rowIndex - 1, // 0-indexed
                                'endIndex' => $rowIndex
                            ]
                        ]
                    ]
                ]);

                $this->service->spreadsheets->batchUpdate($this->spreadsheetId, $batchUpdateRequest);
                Log::info("Deleted row $rowIndex from Google Sheet for account $accountNumber");
            }
        } catch (\Exception $e) {
            Log::error("Google Sheets Delete Error: " . $e->getMessage());
        }
    }

    /**
     * Find the row index of an account by its database ID
     * Returns the 1-based row index or null if not found
     */
    private function findRowIndex($accountId)
    {
        // Read Column A (Database ID)
        $range = 'Sheet1!A:A';
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
        $values = $response->getValues();

        if (empty($values)) {
            return null;
        }

        // Iterate and find the account ID
        // $i is 0-indexed, so row number is $i + 1
        foreach ($values as $i => $row) {
            if (isset($row[0]) && $row[0] == $accountId) {
                return $i + 1;
            }
        }

        return null;
    }
}
