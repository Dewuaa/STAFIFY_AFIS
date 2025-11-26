<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class TestGoogleSheets extends Command
{
    protected $signature = 'sheets:test';
    protected $description = 'Test Google Sheets Integration';

    public function handle()
    {
        $this->info('Testing Google Sheets Integration...');

        $spreadsheetId = config('services.google.spreadsheet_id');
        $credentialsPath = base_path(config('services.google.sheets_credentials_path'));

        if (!file_exists($credentialsPath)) {
            $this->error("Credentials file NOT found!");
            return;
        }

        try {
            $client = new Client();
            $client->setAuthConfig($credentialsPath);
            $client->addScope(Sheets::SPREADSHEETS);
            $service = new Sheets($client);

            // 0. Print Client Email
            $authConfig = json_decode(file_get_contents($credentialsPath), true);
            $clientEmail = $authConfig['client_email'] ?? 'Unknown';
            $this->info("------------------------------------------------");
            $this->info("Service Account Email: " . $clientEmail);
            $this->info("------------------------------------------------");

            // Try to APPEND a test row
            $this->info("Attempting to APPEND to Spreadsheet ID: $spreadsheetId");
            
            $values = [['TEST', 'ROW', date('Y-m-d H:i:s')]];
            $body = new ValueRange(['values' => $values]);
            $params = ['valueInputOption' => 'RAW'];
            
            $result = $service->spreadsheets_values->append(
                $spreadsheetId,
                'Sheet1!A:C',
                $body,
                $params
            );
            
            $this->info("SUCCESS! Wrote to the sheet.");
            $this->info("Updated range: " . $result->getUpdates()->getUpdatedRange());
            $this->info("------------------------------------------------");
            $this->info("The connection WORKS! Try editing an account in your web app now.");

        } catch (\Exception $e) {
            $this->error("API Error: " . $e->getMessage());
            
            if (strpos($e->getMessage(), '404') !== false) {
                $this->error("DIAGNOSIS: The Spreadsheet ID is wrong OR Sheets API is not working.");
            } else if (strpos($e->getMessage(), '403') !== false) {
                $this->error("DIAGNOSIS: Permission denied. Check if Sheets API is enabled.");
            }
        }
    }
}
