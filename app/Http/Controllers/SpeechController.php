<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Libs\Core\CoreSpeech;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Speech;

class SpeechController extends Controller
{

    public function index()
    {
        $pageNow = Cache::get('page_now');
        return view('speech.index', [
            'pageNow' => $pageNow,
            'configTab' => [],
            'configTabUrl' => [],
        ]);
    }

    public function api(Request $request)
    {
        if (!isset($request->pageKey)) {
            $result = array('state' => 300, 'info' => '请传参');
        } else {
            $file = $request->input('file', 'test');
            $file = 'kcsjxg';
            $txtMsg = file_get_contents(public_path('uploads') . '/' . $file . '.txt');
            // 用参数true把JSON字符串强制转成PHP数组
            if (!empty($txtMsg)) {
                $pageNow = $tmpPageS = $request->pageKey;
                $pageNow === 'auto' && $pageNow = !empty(Cache::get('page_now')) ? Cache::get('page_now') : 0;
                //翻译行数
                $limit = 100;
                $msgArr = explode('。', $txtMsg);
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
                $res = Speech::makeVoice($msg, true);
                if ($res['success'] === true) {
                    $lastPage = $pageNow - $limit * 2;
                    $urlInfo = "<a href='/speech/api/{$lastPage}'>上一页</a>
                    <a href='/speech/api/{$pageNow}'>下一页</a>
                    <a target='_blank' href='/" . $res['data'] . "'>结束位置:{$pageNow}</a><hr>";
                    exit(print_r($urlInfo));
                }
                if ($res['success'] === true) {
                    $result = array('state' => 200, 'info' => '操作成功', 'data' => [
                        'url' => '/' . $res['data'],
                        'page' => intval($tmpPageS),
                        'nextPage' => intval($pageNow),
                    ]);
                } else {
                    $result = array('state' => 301, 'info' => $res['msg']);
                }
            } else {
                $result = array('state' => 300, 'info' => '文件错误');
            }
        }
        return $result;
    }
}
