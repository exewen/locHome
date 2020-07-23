<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App;
use Log;
use App\Contracts\TestContract;

class ApiTestController extends Controller
{

    public function get(Request $request)
    {
        Log::info('get_log:' . json_encode($request->all()));
        return 1;
    }

    public function post(Request $request)
    {
        Log::info('post_log:' . json_encode($request->all()));
        return 1;
    }


}