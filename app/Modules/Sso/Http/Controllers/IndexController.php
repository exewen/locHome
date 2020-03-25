<?php


namespace App\Modules\Sso\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB,Redis,Session;
const STRING_SINGLETOKEN_ = 'STRING_SINGLETOKEN_';

class IndexController extends  Controller
{
    public function index()
    {
        dd('index');
    }

    public function login(Request $request)
    {
        $userName = 'shangjun.jiang';
        $pwd = 'admin';
        // 登录验证
        //$result = \DB::table('users')->where(['username' => $userName, 'password' => $pwd])->find();
        $result = DB::table('users')->where(['name' => $userName])->first();
        // 该地方为登录验证逻辑
        if ($result) {
            # 登录成功
            // 制作 token
            $time = time();
            // md5 加密
            $singleToken = md5($request->getClientIp() . $result->id . $time);
            // 当前 time 存入 Redis
            Redis::set(STRING_SINGLETOKEN_ . $result->id, $time);
            // 用户信息存入 Session
            Session::put('user_login', $result);
            // 跳转到首页, 并附带 Cookie
            return response()->view('index')->withCookie('SINGLETOKEN', $singleToken);
        } else {
            # 登录失败逻辑处理
        }
    }
}