<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Libs\Core\CoreSpeech;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;

class SpeechController extends Controller {

    public function index() {
        $pageNow = Cache::get('page_now');
        return view('speech.index', [
            'pageNow' => $pageNow,
            'configTab' => [],
            'configTabUrl' => [],
        ]);
    }

    public function api(Request $request) {
        if (!isset($request->pageKey)) {
            $result = array('state' => 300, 'info' => '请传参');
        } else {
            $json_string = file_get_contents(public_path('uploads') . '/txt.json');
            // 用参数true把JSON字符串强制转成PHP数组
            $data = json_decode($json_string, true);
            if (isset($data['msg']) && !empty($data['msg'])) {
                $pageNow = $request->pageKey;
                $pageNow === 'auto' && $pageNow = !empty(Cache::get('page_now')) ? Cache::get('page_now') : 0;
                //翻译行数
                $limit = 100;
                $msgArr = explode('。', $data['msg']);
                //翻译
                $msg = '';
                $start = $pageNow;
                for ($i = $start; $i < $start + $limit; $i++) {
                    //翻译长度限制
                    if (strlen($msg) > 5000) {
                        break;
                    }
                    $msg .= isset($msgArr[$i]) ? $msgArr[$i] : '';
                    $pageNow++;
                }
                Cache::forever('page_now', $pageNow);
                $result = CoreSpeech::make($msg);
                if ($result['state'] === 200) {
                    $result['data']['file'] = str_replace('/Users/exeweb/Sites/locHome/public', '', $result['data']['file']);
                    $lastPage = $pageNow - $limit * 2;
                    $urlInfo = "<a href='/speech/api/{$lastPage}'>上一页</a> 
                    <a href='/speech/api/{$pageNow}'>下一页</a>
                    <a target='_blank' href='" . $result['data']['file'] . "'>结束位置:{$pageNow}</a><hr>";
                    exit(print_r($urlInfo));
                }
                exit(var_dump($result));
            } else {
                $result = array('state' => 300, 'info' => '文件错误');
            }
        }
        return $result;
    }
}
