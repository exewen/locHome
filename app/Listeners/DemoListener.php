<?php

namespace App\Listeners;

use App\Events\DemoEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DemoListener
{
    /**
     * 定义监听器
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DemoEvent  $event
     * @return void
     */
    public function handle(DemoEvent $event)
    {
        //
        echo 'demo';
    }
}
