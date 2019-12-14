<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller {

    public function test() {
        $users = DB::select('select * from users where 1', [1]);
        exit(var_dump($users));
    }

    private function getConfigOne() {
        $tab = [
            'lan' => [
                'type' => 'warehouse',
                'name' => 'LAN',
                'color' => '#00838F',
                'domain' => 'http://127.0.0.1'
            ],
            'dev' => [
                'type' => 'warehouse',
                'name' => '开发',
                'color' => '#00838F',
                'domain' => 'http://qa-wms.dev.interfocus.org'
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
        foreach ($tab as $item) {
            $item['type'] === 'warehouse' && $mqUrl .= $item['domain'] . '/?c=of_base_com_mq&__OF_DEBUG__=,';
            $item['type'] === 'warehouse' && $jsUrl .= $item['domain'] . '/?c=of_base_htmlTpl_tool&a=index&__OF_DEBUG__=,';
        }
        $url = [
            'OA需求' => [
                'url' => 'https://oa.yafex.cn/?c=gts_task&a=index&type=0&indexType=2&devUser=%E8%92%8B%E5%B0%9A%E5%90%9B',
                'target' => '_self',
                'type' => 'build',
            ],
            '构建' => [
                'url' => 'https://opsoa.yafex.cn/flow/gitflow/list',
                'target' => '_self',
                'type' => 'build',
            ],
            '测试构建' => [
                'url' => 'https://jenkins.yafex.cn/job/wms4sz2dev/',
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
            'APS' => [
                'url' => 'http://localhost:8888/aps/src/',
                'target' => '_self',
                'type' => 'build',
            ],
            'intelligence' => [
                'url' => 'http://localhost:8888/intelligence/src/',
                'target' => '_self',
                'type' => 'build',
            ],
            'OPS-DB' => [
                'url' => 'https://opsoa.yafex.cn/flow/db/list',
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
            '基础环境' => [
                'url' => '/?c=ctrl_dd&a=index',
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
        return [$tab, $url];
    }

    private function getConfigTwo() {
        $tab = [
            'lan' => [
                'type' => 'warehouse',
                'name' => 'LAN',
                'color' => '#00838F',
                'domain' => 'http://127.0.0.1'
            ],
            'dev' => [
                'type' => 'warehouse',
                'name' => '开发',
                'color' => '#00838F',
                'domain' => 'http://qa-wms.dev.interfocus.org'
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
        foreach ($tab as $item) {
            $item['type'] === 'warehouse' && $mqUrl .= $item['domain'] . '/?c=of_base_com_mq&__OF_DEBUG__=,';
            $item['type'] === 'warehouse' && $jsUrl .= $item['domain'] . '/?c=of_base_htmlTpl_tool&a=index&__OF_DEBUG__=,';
        }
        $url = [
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
            '企业邮箱' => [
                'url' => 'https://exmail.qq.com/login',
                'target' => '_self',
                'type' => 'build',
            ],
            '系统概述' => [
                'url' => 'https://eat5vr.axshare.com/',
                'target' => '_self',
                'type' => 'build',
            ],
            'QA产品' => [
                'url' => 'https://zaaay8.axshare.com/#id=bjd41p&p=%E6%9D%A1%E7%A0%81%E6%96%B9%E6%A1%88&g=1',
                'target' => '_self',
                'type' => 'build',
            ],
        ];
        return [$tab, $url];
    }

    public function index() {
        $urlArr = $this->getConfigTwo();
        return view('home.index', [
            'configTab' => $urlArr[0],
            'configTabUrl' => $urlArr[1],
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
