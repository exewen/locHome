<?php


namespace App\Modules\Att\Http\Controllers;

use App\Http\Requests;
use App\Models\LogisticsBusinessToken;
use App\Services\API\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB, Redis, Session, Input;
use CLog;

use Response;
use RuntimeException;

class LazadaController extends Controller
{
    const AuthUrl = 'https://auth.lazada.com/rest';
    const AppKey = 114381;
    const AppSecret = '9vXVujPkb2G5IriSov4Ir959PKxEJsU2';
    const APICODE = 'LAZADAAPI';

    private $accessToken = null;
    private $acount = null;


    public function index()
    {
        $this->acount = '1510212265@qq.com';
        $this->token();
        dd(['accessToken' => $this->accessToken]);
    }

    /**
     * token验证
     * @param $code
     * @return null|string
     */
    public function token($code = '')
    {
        $apiKey = self::APICODE . '_' . $this->acount;
        /***token验证***/
        $businessToken = LogisticsBusinessToken::token($apiKey);
        if ($businessToken['status'] == 1) {
            $this->accessToken = $businessToken['access_token'];
            return $this->accessToken;
        } elseif ($businessToken['status'] == 2) {
            dd('刷新');
            $refresh_token = $businessToken['refresh_token'];
            $method = "/auth/token/refresh";
            $http = self::AuthUrl . $method;
            $param = [
                'app_key' => self::AppKey,
                'app_secret' => self::AppSecret,
                'refresh_token' => $refresh_token,
                'timestamp' => time() . '000',
                'sign_method' => 'sha256'
            ];
        } else {
            $code = Input::get('code', 0);
            if ($code) {
                $method = "/auth/token/create";
                $http = self::AuthUrl . $method;
                $param = [
                    'app_key' => self::AppKey,
                    'app_secret' => self::AppSecret,
                    'code' => $code,
                    'timestamp' => time() . '000',
                    'sign_method' => 'sha256'
                ];
            } else {
                throw new RuntimeException('LAZADA——token过期，请联系技术人员');
            }
        }
        $sign = $this->sign($method, $param);
        $param['sign'] = $sign;
        $http = $http . '?' . http_build_query($param);
        $response = $this->sendRequest($http, 'GET', []);
        $this->logger()->info(self::APICODE . '---token---响应报文数据：' . $response);
        $result = json_decode($response);
        if ($result->access_token) {
            $record = LogisticsBusinessToken::where('api', $apiKey)->first();
            if ($record) {
                LogisticsBusinessToken::where('api', $apiKey)
                    ->update([
                        'access_token' => $result->access_token,
                        'refresh_token' => $result->refresh_token,
                        'ex_time' => date('Y-m-d h:i:s', (time() + $result->expires_in)),
                        'refresh_ex_time' => date('Y-m-d h:i:s', (time() + $result->refresh_expires_in)),
                    ]);
            } else {
                LogisticsBusinessToken::create([
                    'api' => $apiKey,
                    'access_token' => $result->access_token,
                    'refresh_token' => $result->refresh_token,
                    'ex_time' => date('Y-m-d h:i:s', (time() + $result->expires_in)),
                    'refresh_ex_time' => date('Y-m-d h:i:s', (time() + $result->refresh_expires_in)),
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'account' => $result->account,
                    'country_info' => join(',', array_column($result->country_user_info, 'country')),
                ]);
            }
            $this->accessToken = $result->access_token;
        } else {
            throw new RuntimeException("LAZADA换取TOKEN异常!" . $result->message);
        }
        return $this->accessToken;

    }

    /**
     * 定义Logger
     * @return mixed
     */
    protected function logger()
    {
        return CLog::getLogger(self::APICODE, 'ExpressApi');
    }

    /**
     * 签名验证
     * @param $appNmae
     * @param $params
     * @return string
     */
    private function sign($appNmae, $params)
    {
        ksort($params);

        $stringToBeSigned = '';
        $stringToBeSigned .= $appNmae;
        foreach ($params as $k => $v) {
            $stringToBeSigned .= "$k$v";
        }
        unset($k, $v);

        return strtoupper($this->hmac_sha256($stringToBeSigned, self::AppSecret));
    }

    function hmac_sha256($data, $key)
    {
        return hash_hmac('sha256', $data, $key);
    }

    /**
     * 发送请求数据
     * @param $http
     * @param string $method
     * @param array $body
     * @return mixed|object
     */
    public function sendRequest($http, $method = 'GET', $body = [])
    {
        $header = [
            'Content-Type: application/json; charset=utf-8',
        ];

        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $http);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_HEADER, false);

            if ("POST" == $method) {
                curl_setopt($curl, CURLOPT_POST, true);
            }
            if (count($body) > 0) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
            }
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            Log::info("-----send_request----" . $e->getMessage());
            return (object)["status" => "Failed", "code" => '404', "message" => $e->getMessage()];
        }
    }
}