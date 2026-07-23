<?php

namespace App\Jobs\Payroll;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Payroll\TaxCalculateService;
use Illuminate\Support\Facades\Log;

class ProcessTaxCalculationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(TaxCalculateService $service): void
    {
        Log::info('ProcessTaxCalculationJob: Executing tax calculation job.');
        $service->calculateTaxForAllEmployees();
        Log::info('ProcessTaxCalculationJob: Completed tax calculation job.');
    }
}
