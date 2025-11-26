<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleSheetsService;

class CheckSheetHeaders extends Command
{
    protected $signature = 'sheets:headers';
    protected $description = 'Check Google Sheet headers';

    public function handle()
    {
        $service = new GoogleSheetsService();
        $spreadsheetId = config('services.google.spreadsheet_id');
        
        try {
            $range = 'Sheet1!1:2';
            $response = $service->getService()->spreadsheets_values->get($spreadsheetId, $range);
            $rows = $response->getValues();
            
            $this->info("First 2 rows of your sheet:");
            foreach ($rows as $i => $row) {
                $this->info("Row " . ($i + 1) . ": " . json_encode($row));
            }
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
