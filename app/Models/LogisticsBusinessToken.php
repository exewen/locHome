<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class LogisticsBusinessToken extends Model
{

    protected $table = "wms_logistics_business_token";
    protected $guarded = [];

    protected $connection = 'tms';

    /**
     * @param $api
     * @param string $account 授权账号
     * @return array|bool|int[]
     */
    public static function token($api, $account = '')
    {
        $mode = LogisticsBusinessToken::where('api', $api);
        $account && $mode->where('account', $account);
        $token = $mode->first();
        if ($token) {
            /**过期验证**/
            if (time() < strtotime($token->ex_time)) {
                return ['status' => 1, 'access_token' => $token->access_token];
                /***令牌过期，刷新令牌可用***/
            } elseif (time() >= strtotime($token->ex_time) && time() < strtotime($token->refresh_ex_time)) {
                return ['status' => 2, 'refresh_token' => $token->refresh_token];
                /***都过期***/
            } elseif (time() >= strtotime($token->ex_time) && time() > strtotime($token->refresh_ex_time)) {
                return ['status' => 3];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}