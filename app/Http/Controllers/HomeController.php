<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller {

    public function index() {
        $configTab = [
            'lan' => [
                'type' => 'warehouse',
                'name' => 'LAN',
                'color' => '#00838F',
                'domain' => 'http://wms4sz2.lan'
            ],
            'd01' => [
                'type' => 'warehouse',
                'name' => '深圳仓',
                'color' => '#F9A825',
                'domain' => 'http://wms4sz1.yafex.cn'
            ],
            'd02' => [
                'type' => 'warehouse',
                'name' => '浒关仓',
                'color' => '#558B2F',
                'domain' => 'http://218.4.82.250:8040'
            ],
            'd03' => [
                'type' => 'warehouse',
                'name' => '苏州仓',
                'color' => '#D84315',
                'domain' => 'http://218.4.82.250:8050'
            ],
            'd04' => [
                'type' => 'warehouse',
                'name' => '菲律宾仓',
                'color' => '#6D4C41',
                'domain' => 'http://wms4ph1.yafex.cn'
            ],
            'build' => [
                'type' => 'build',
                'color' => '#6A1B9A',
                'name' => '工具',
                'domain' => ''
            ],
        ];
        $jsUrl = '';
        $mqUrl = '';
        foreach ($configTab as $item) {
            $item['type'] === 'warehouse' && $mqUrl .= $item['domain'] . '/?c=of_base_com_mq&__OF_DEBUG__=,';
            $item['type'] === 'warehouse' && $jsUrl .= $item['domain'] . '/?c=of_base_htmlTpl_tool&a=index&__OF_DEBUG__=,';
        }
        $configTabUrl = [
            '构建' => [
                'url' => 'http://192.168.1.147:8080/job/wms4sz2/',
                'target' => '_self',
                'type' => 'build',
            ],
            '刷新JS' => [
                'url' => '/home/multiPage/' . base64_encode(trim($jsUrl, ',')),
                'target' => '_self',
                'type' => 'build',
            ],
            '重启消息队列' => [
                'url' => '/home/multiPage/' . base64_encode(trim($mqUrl, ',')),
                'target' => '_self',
                'type' => 'build',
            ],
            'TAPD' => [
                'url' => 'https://www.tapd.cn/my_worktable',
                'target' => '_self',
                'type' => 'build',
            ],

            '主页' => [
                'url' => '/',
                'target' => '_self',
                'type' => 'warehouse',
            ],
            'MBB' => [
                'url' => '/mobile.php',
                'target' => '_self',
                'type' => 'warehouse',
            ],
            '消息队列' => [
                'url' => '/?c=ctrl_queueManage&a=index',
                'target' => '_self',
                'type' => 'warehouse',
            ],
            '出库列表' => [
                'url' => '/?c=ctrl_pickOrder&a=index&orderState=40&problemType=normal&PHC=1',
                'target' => '_self',
                'type' => 'warehouse',
            ],
            'TEST' => [
                'url' => '/?c=demo_jiangshangjun&a=index',
                'target' => '_self',
                'type' => 'warehouse',
            ],
            '错误日志' => [
                'url' => '/?c=of_base_error_tool&__OF_DEBUG__',
                'target' => '_self',
                'type' => 'warehouse',
            ],
        ];
        return view('home.index', [
            'configTab' => $configTab,
            'configTabUrl' => $configTabUrl,
        ]);
    }

    public function multiPage(Request $request) {
        $pages = base64_decode($request->pages);
        $pagesArr = explode(",", trim($pages, ','));
        return view('home.multiPage', [
            'pages' => $pagesArr
        ]);
    }
}