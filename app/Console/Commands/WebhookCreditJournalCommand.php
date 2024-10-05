<?php

namespace App\Console\Commands;

use App\Models\ProvidusDataStore;
use Illuminate\Console\Command;

class WebhookCreditJournalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:webhook-credit-journal-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Credit accounts from webhook with the amount sent';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = ProvidusDataStore::query()->where('processed', '=',false)->get()->each(function ($item) {
        });

    }
}
