<?php

namespace App\Observers;

use App\Models\ChartOfAccount;
use App\Services\GoogleSheetsService;

class ChartOfAccountObserver
{
    protected $googleSheetsService;

    public function __construct(GoogleSheetsService $googleSheetsService)
    {
        $this->googleSheetsService = $googleSheetsService;
    }

    /**
     * Handle the ChartOfAccount "created" event.
     */
    public function created(ChartOfAccount $chartOfAccount): void
    {
        $this->googleSheetsService->syncAccount($chartOfAccount);
    }

    /**
     * Handle the ChartOfAccount "updated" event.
     */
    public function updated(ChartOfAccount $chartOfAccount): void
    {
        $this->googleSheetsService->syncAccount($chartOfAccount);
    }

    /**
     * Handle the ChartOfAccount "deleted" event.
     */
    public function deleted(ChartOfAccount $chartOfAccount): void
    {
        $this->googleSheetsService->deleteAccount($chartOfAccount->account_number);
    }
}
