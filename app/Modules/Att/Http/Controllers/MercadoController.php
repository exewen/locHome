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

class MercadoController extends Controller
{
    const AuthUrl = 'https://api.mercadolibre.com';
    const AppKey = 6632736547738841;
    const AppSecret = 'RPJqwsIhUgZKP9JvNPj1KtTLTDyc5xaq';
    const APICODE = 'MERCADOAPI';

    private $accessToken = null;
    private $account = null;


    public function __construct()
    {
        $sellerNameMaps = [
            1 => 'fashion@patpat.com',
            2 => 'patpatq-lazada@patpat.com',
            3 => '1510212265@qq.com',
        ];
        $this->account = $sellerNameMaps[3]; // 查询店铺
    }

    /**
     * 授权回调
     */
    public function index()
    {
        $code = Input::get('code', '');
        $this->token($code);
        echo 'ok';
    }

    public function auth()
    {
        dd('授权成功');
    }

    /**
     * token验证
     * @param $code
     * @return null|string
     */
    public function token($code = '')
    {
        /***token验证***/
        $businessToken = LogisticsBusinessToken::token(self::APICODE);
        if ($businessToken['status'] == 1) {
            $this->accessToken = $businessToken['access_token'];
            return $this->accessToken;
        } elseif ($businessToken['status'] == 2) {
            if (env('APP_TEST')) {
                throw new RuntimeException('测试环境不进行token刷新');
            }
            $refresh_token = $businessToken['refresh_token'];
            $method = "/oauth/token";
            $http = self::AuthUrl . $method;
            $params = [
                'grant_type' => 'refresh_token',
                'client_id' => self::AppKey,
                'client_secret' => self::AppSecret,
                'refresh_token' => $refresh_token,
            ];
        } else {
            if (env('APP_TEST')) {
                if ($code) {
                    $method = "/oauth/token";
                    $http = self::AuthUrl . $method;
                    $params = [
                        'grant_type' => 'authorization_code',
                        'client_id' => self::AppKey,
                        'client_secret' => self::AppSecret,
                        'code' => $code,
                        'redirect_uri' => 'https://att.exeweb.cn/att/mercado',
                    ];
                } else {
                    throw new RuntimeException('LAZADA——token过期，请联系技术人员');
                }
            } else {
                // 初始化token
                $createSql = 'INSERT INTO `wms_logistics_business_token`(`api`, `access_token`, `refresh_token`, `ex_time`, `refresh_ex_time`, `created_at`, `updated_at`) VALUES (\'MERCADOAPI\', \'APP_USR-6632736547738841-051502-35b0b0fa403337aaf4f0a62cf5e5c7c2-544124082\', \'TG-5eaa42a00d34dc00064d34e5-544124082\', \'2020-05-15 08:19:53\', \'2030-05-13 02:19:53\', \'2020-04-30 03:14:41\', \'2020-05-15 02:19:53\');';
                \DB::insert($createSql);
                return $this->token();
            }

        }
        $http = $http . '?' . http_build_query($params);
        $this->logger()->info('---MERCADO-0---请求报文数据：' . $http);
        $response = $this->sendRequest($http, 'POST', []);
        $this->logger()->info('---MERCADO-0---响应报文数据：' . $response);
        $decodeRes = json_decode($response);
        if (isset($decodeRes->access_token)) {
            $this->saveAccessToken($decodeRes, self::APICODE);
            $this->accessToken = $decodeRes->access_token;
            return $decodeRes->access_token;
        } else {
            throw new RuntimeException("LAZADA刷新TOKEN异常：" . $response);
        }
        return false;
    }

    /**
     * 储存令牌
     * @param $response
     */
    protected function saveAccessToken($response, $apiCode)
    {
        if ($apiCode == self::APICODE) $response->refresh_expires_in = 86400 * 365 * 10; // MERCADOAPI refresh_expires_in暂时没返回失效时间
        $record = LogisticsBusinessToken::where('api', $apiCode)->first();
        if ($record) {
            $res = LogisticsBusinessToken::where('api', $apiCode)
                ->update([
                    'access_token' => $response->access_token,
                    'refresh_token' => $response->refresh_token,
                    'ex_time' => Carbon::now()->addSecond($response->expires_in)->toDateTimeString(),
                    'refresh_ex_time' => Carbon::now()->addSecond($response->refresh_expires_in)->toDateTimeString(),
                ]);
        } else {
            $res = LogisticsBusinessToken::create([
                'api' => self::APICODE,
                'access_token' => $response->access_token,
                'refresh_token' => $response->refresh_token,
                'ex_time' => Carbon::now()->addSecond($response->expires_in)->toDateTimeString(),
                'refresh_ex_time' => Carbon::now()->addSecond($response->refresh_expires_in)->toDateTimeString(),
                'created_at' => Carbon::now()->toDateTimeString()
            ]);
        }
        return $res;
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