<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Elasticsearch\Client;
use App\Services\Facades\ElasticSearchClient;

class ElasticSearchLog implements ShouldQueue
{
    use  InteractsWithQueue, Queueable, SerializesModels;

    private $params;

    /**
     * Create a new job instance.
     * ElasticSearchLog constructor.
     * @param array $records
     */
    public function __construct(array $records)
    {
        $this->params['body'] = [];
        foreach ($records as $record) {
            unset($record['context']);
            unset($record['extra']);
            $record['datetime'] = $record['datetime']->format('Y-m-d H:i:s');
            $this->params['body'][] = [
                'index' => [
                    '_index' => config('elasticsearch.log_index'),
                    '_type' => config('elasticsearch.log_type'),
                ],
            ];
            $this->params['body'][] = $record;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = ElasticSearchClient::getClient();
        if ($client instanceof Client) {
            $client->bulk($this->params);
        }
    }
}