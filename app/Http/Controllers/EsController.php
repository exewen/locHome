<?php

namespace App\Http\Controllers;

use App\Services\Facades\ElasticSearchClient;
use App\Traits\BarcodeGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Cache;
use Elasticsearch\ClientBuilder;

class EsController extends Controller
{
    public $client = null;

    public function __construct()
    {
        $host = env('ELASTIC_HOST');

        // $this->client = ClientBuilder::create()->build();
        $this->client = ClientBuilder::create()->setHosts([$host])->build();
    }

    //创建
    public function index()
    {
        $params = [
            'index' => 'my_index',
            'type' => 'my_type',
            'id' => 'my_id',
            'body' => ['testField' => 'abc2']
        ];
        $response = $this->client->index($params);
        dd($response);
    }

    public function log()
    {
        Log::info('okokokol', ['code' => 0, 'msg' => '成功统', 'data' => [1, 2, 3, 4, 5]]);
        Log::info('okokokol2，日志同时写入 文件系统 和 ElasticSearch 系统', ['code' => 0, 'msg' => '成功了，日志同时写入 文件系统 和 ElasticSearch 系统', 'data' => [1, 2, 3, 4, 5]]);
    }
}
