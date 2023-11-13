<?php

namespace App\Jobs;

use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AfterAuthenticateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 5;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Indicate if the job should be marked as failed on timeout.
     *
     * @var bool
     */
    public $failOnTimeout = true;

    /**
     * Create a new job instance.
     */
    public function __construct(private Store $store)
    {
        $store->withoutRelations();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $topic = "APP_UNINSTALLED";
        $callbackUrl = \secure_url('/api/webhooks');

        $webhookSubscriptions = $this->store->shopifyClient()->webhookSubscriptions();
        if (\array_search($topic, \array_column($webhookSubscriptions, 'topic')) === false) {
            $this->store->shopifyClient()->createWebhookSubscription($topic, $callbackUrl);
            \info("{$topic} webhook registered");
        }
    }
}
