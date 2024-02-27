<?php

namespace App\Jobs;

use App\Models\Beneficiaries;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveBeneficiaryQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public array $beneficiary){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            Beneficiaries::query()->create($this->beneficiary);
        }catch (Exception $e) {
            logExceptionErrorMessage('SaveBeneficiaryQueue',$e,[],'error');
        }
    }
}
