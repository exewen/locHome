<?php

namespace App\Http\Controllers;

class HomeController extends Controller {

    public function index() {
        $configTab = [
            'lan' => [
                'type' => 'warehouse',
                'name' => 'LAN',
                'color' => '#5BC0DE',
                'domain' => 'http://wms4sz2.lan'
            ],
            'd01' => [
                'type' => 'warehouse',
                'name' => '深圳仓',
                'color' => 'green',
                'domain' => 'http://wms4sz1.yafex.cn'
            ],
            'd02' => [
                'type' => 'warehouse',
                'name' => '浒关仓',
                'color' => '#5BC0DE',
                'domain' => 'http://218.4.82.250:8040'
            ],
            'd03' => [
                'type' => 'warehouse',
                'name' => '苏州仓',
                'color' => 'green',
                'domain' => 'http://218.4.82.250:8050'
            ],
            'build' => [
                'type' => 'build',
                'color' => '#5BC0DE',
                'name' => '工具',
                'domain' => ''
            ],
        ];
        $configTabUrl = [
            '构建' => [
                'url' => 'http://192.168.1.147:8080/job/wms4sz2/',
                'target' => '_self',
                'type' => 'build',
            ],
            '刷新JS' => [
                'url' => '/include/of/?c=of_base_htmlTpl_tool&a=index&__OF_DEBUG__=',
                'target' => '_self',
                'type' => 'build',
            ],
            '重启消息队列' => [
                'url' => '/?c=of_base_com_mq&__OF_DEBUG__=',
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
}