<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LocController extends Controller
{

    public function test()
    {
        $users = DB::select('select * from test ');
        exit(var_dump($users));
    }

    private function getConfigOne()
    {
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

    private function configs()
    {
        $configs = [
            'QAWMS' => [
                'name' => 'QAWMS',
                'color' => '#00838F',
                'domain' => '',
                'items' => [
                    0 => [
                        'url' => 'http://qawms.lan/',
                        'name' => 'Lan',
                        'target' => '_self',
                    ],
                    1 => [
                        'url' => 'http://qawms.dev.interfocus.org/',
                        'name' => 'Dev',
                        'target' => '_self',
                    ],
                    2 => [
                        'url' => 'http://52.221.152.145:88/server/qa-wms',
                        'name' => 'Git',
                        'target' => '_self',
                    ],
                    3 => [
                        'url' => 'http://192.168.9.157:8080/jenkins/job/qa-wms/',
                        'name' => 'Jenkins',
                        'target' => '_self',
                    ],
                ]
            ],
            'SMS' => [
                'name' => 'SMS',
                'color' => '#00838F',
                'domain' => '',
                'items' => [
                    0 => [
                        'url' => 'http://sms.lan/',
                        'name' => 'Lan',
                        'target' => '_self',
                    ],
                    1 => [
                        'url' => 'http://sms.dev.interfocus.org/',
                        'name' => 'Dev',
                        'target' => '_self',
                    ],
                    2 => [
                        'url' => 'http://52.221.152.145:88/server/sms',
                        'name' => 'Git',
                        'target' => '_self',
                    ],
                    3 => [
                        'url' => 'http://192.168.9.157:8080/jenkins/job/sms/',
                        'name' => 'Jenkins',
                        'target' => '_self',
                    ],
                ]
            ],

            'build' => [
                'color' => '#6A1B9A',
                'name' => '工具',
                'domain' => '',
                'items' => [
                    0 => [
                        'url' => 'https://exmail.qq.com/',
                        'name' => '企业邮箱',
                        'target' => '_self',
                    ],
                    1 => [
                        'url' => 'https://eat5vr.axshare.com/#g=1&p=%E7%B3%BB%E7%BB%9F%E6%A6%82%E8%BF%B0%E5%8F%8A%E6%B5%81%E7%A8%8B',
                        'name' => '系统概述',
                        'target' => '_self',
                    ],
                    2 => [
                        'url' => 'https://www.italent.cn/',
                        'name' => 'italent',
                        'target' => '_self',
                    ],
                ]
            ],
        ];
        return $configs;
    }

    public function index()
    {
        $configs = $this->configs();
        return view('loc.index', ['configs' => $configs,]);
    }

    public function multiPage(Request $request)
    {
        $pages = base64_decode($request->pages);
        $pagesArr = explode(",", trim($pages, ','));
        return view('loc.multiPage', [
            'pages' => $pagesArr
        ]);
    }
}
