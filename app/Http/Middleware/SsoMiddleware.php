<?php

namespace App\Http\Middleware;

use Closure;
use Session,Redis,DB;
class SsoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userInfo = Session::get('user_login');
        if ($userInfo) {
            // 获取 Cookie 中的 token
            $singletoken = $request->cookie('SINGLETOKEN');
            if ($singletoken) {
                // 从 Redis 获取 time
                $redisTime = Redis::get(g_STRING_SINGLETOKEN . $userInfo->id);
                // 重新获取加密参数加密
                $ip = $request->getClientIp();
                $secret = md5($ip . $userInfo->id . $redisTime);
                if ($singletoken != $secret) {
                    // 记录此次异常登录记录
                    DB::table('data_login_exception')->insert(['guid' => $userInfo->guid, 'ip' => $ip, 'addtime' => time()]);
                    // 清除 session 数据
                    Session::forget('user_login');
                    return view('/403')->with(['Msg' => '您的帐号在另一个地点登录..']);
                }
                return $next($request);
            } else {
                return redirect('/login');
            }
        } else {
            return redirect('/login');
        }
    }
}
