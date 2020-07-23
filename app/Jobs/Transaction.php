<?php

namespace App\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use DB;

class Transaction extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        try {
            $res = DB::table('test')->insert(['name' => 'jun' . $this->attempts()]);
            if ($res) {
                throw new \Exception('插入失败');
            }
             Log::info('MQ插入成功');
        } catch (\Exception $e) {
            $msg = '计算物流商提货数据异常:' . $e->getMessage();
            Log::error($msg);
            $delay = $this->attempts() > 3 ? 60 : 3600;
            $this->release($delay);
        }

    }
}
