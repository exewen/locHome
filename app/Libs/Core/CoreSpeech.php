<?php

namespace Libs\Core;

use Libs\Speech\AipSpeech;

/**
 * 描述 : 百度语音合成
 * 作者 : jiangshangjun
 */
class CoreSpeech {

    static $apiId = '15673657';
    static $apiKey = 'O9twNuj2EfDlwql7Xb0meN5W';
    static $secretKey = '9afrjc218SREwWVrDi6v0k3T8o3GiezW';

    /**
     * 描述 : 生成语音文件
     * 参数 : params : {
     *          "msg" :  语音消息
     *      }
     * 返回 : {
     *          "state":状态码,
     *          "info":提示信息,
     *          "data" : {
     *               "name" : 生成音频文件名,
     *               "fileWrite" : 写入返回信息,
     *               "error_code" : 错误码,
     *               "error_msg" : 错误描述信息，帮助理解和解决发生的错误
     *          }
     *      }
     * 注明 : 状态码返回一览表
     *        200 : 操作成功
     * 作者 : jiangshangjun
     */
    public static function make($msg = null) {
        $config = array(
            'spd' => 4,//语速，取值0-9，默认为5中语速
            'pit' => 5,//音调，取值0-9，默认为5中语调
            'vol' => 10,//音量，取值0-15，默认为5中音量
            'per' => 3,//发音人选择, 0为女声，1为男声，3为情感合成-度逍遥，4为情感合成-度丫丫，默认为普通女
        );
        $filename = md5($msg . serialize($config));
        $url = "/cache/voice/" . date('Ymd', time()) . "/";
        $route = public_path('uploads') . $url;
        $file = $route . $filename . ".mp3";
        if (CoreFile::createDir($route) && file_exists($file) === false) {
            $client = new AipSpeech(self::$apiId, self::$apiKey, self::$secretKey);
            $res = $client->synthesis($msg, 'zh', 1, $config);
            // 识别正确返回语音二进制 错误则返回json 参照下面错误码
            if (!is_array($res)) {
                $re = file_put_contents($file, $res);
                $result = array('state' => 200, 'info' => '生成成功', 'data' => array(
                    'fileWrite' => $re,
                    'name' => $filename,
                    'route' => $route,
                    'file' => $file,
                    'url' => 'http://lan.exeweb.lan/uploads' . $url . $filename . '.mp3',
                ));
            } else {
                $result = array('state' => 300, 'info' => '生成失败', 'data' => $res);
            }
        } else {
            $result = array(
                'state' => 200,
                'info' => '返回缓存文件',
                'data' => array(
                    'name' => $filename,
                    'route' => $route,
                    'file' => $file,
                    'url' => 'http://lan.exeweb.lan/uploads' . $url . $filename . '.mp3',
                ));
        }
        return $result;
    }

    /**
     * 描述 : 创建文件夹
     * 作者 : jiangshangjun
     */
    public static function createDir($path) {
        if (is_dir($path)) {
            return true;
        } else {
            $res = mkdir(iconv("UTF-8", "GBK", $path), 0777, true);
            if ($res) {
                return true;
            } else {
                return false;
            }
        }
    }
}
