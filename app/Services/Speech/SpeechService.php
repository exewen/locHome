<?php

namespace App\Services\Speech;

use Storage;
use Jormin\BaiduSpeech\BaiduSpeech;

class SpeechService
{
    /**
     * 获取文字音频文件
     * @param null $msg
     * @return array
     */
    public static function makeVoice($msg = null, $refresh = false)
    {
        $fileName = 'tmp/audios/' . md5($msg) . '.mp3';
        !Storage::disk('speech')->exists($fileName) && $refresh = true;
        if ($refresh) {
            $data = BaiduSpeech::combine($msg, '', 'zh', 5, 5, 5, 0, $fileName);
            if ($data['success'] === true) {
                $contents = Storage::get($data['data']);
                Storage::delete($data['data']);
                Storage::disk('speech')->put($fileName, $contents);
            }
        } else {
            $data = [
                'success' => true,
                'data' => $fileName,
            ];
        }
        return $data;
    }
}
