<?php
namespace Libs\Core;
/**
 * 描述 : 文件管理
 * 作者 : jiangshangjun
 */
class CoreFile {

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
