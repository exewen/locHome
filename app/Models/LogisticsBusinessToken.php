<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class LogisticsBusinessToken extends Model
{

    protected $table = "wms_logistics_business_token";
    protected $guarded = [];

    protected $connection = 'tms';

    public static function token($api)
    {
        $token = LogisticsBusinessToken::where('api',$api)->first();
        if($token){
            /**过期验证**/
            if(time() < strtotime($token->ex_time)){
                return [ 'status'=>1, 'access_token' => $token->access_token];
            /***令牌过期，刷新令牌可用***/
            }elseif(time() >= strtotime($token->ex_time) && time() < strtotime($token->refresh_ex_time)){
                return ['status' =>2,'refresh_token'=> $token->refresh_token ];
                /***都过期***/
            }elseif(time() >= strtotime($token->ex_time) && time() > strtotime($token->refresh_ex_time)){
                return ['status' =>3];
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

}