<?php

namespace App\Jobs;

use App\Models\ProvidusDataStore;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveProvidusWebhookDataStore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $data) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ProvidusDataStore::create($this->data);
    }
}
