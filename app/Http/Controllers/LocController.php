<?php

namespace App\Http\Controllers;

use App\Traits\BarcodeGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Cache;

class LocController extends Controller {
    use BarcodeGenerator;

    protected function repairDeliveryPhone($countryName, &$phone) {
        $res = DB::table('oms_country_currency')->where('country_name', $countryName)->first(['phone_area_code']);
        isset($res->phone_area_code) && $phone = $res->phone_area_code . $phone;
    }

    protected function qcTest() {
        $result = [];
        $location = [
            'A111',
            'A112',
            'A113',
        ];
        ksort($location);
        $cacheKey = md5(json_encode($location));
        if ($cache = Cache::get($cacheKey, false)) {
            return unserialize($cache);
        }

        $sql = "SELECT
	wsds.customer_id,
	wsds.sku_code,
	wsds.region_code,
	wwa.subarea,
	wsds.location,
	wsds.quality_level,
	sum( wsds.existing_number ) AS sum_existing_number,
	sum( wsds.allocate_number ) AS sum_allocate_number,
	wwa.type 
FROM
	`wms_sku_detail_stock` AS `wsds`
	INNER JOIN `wms_locations` AS `wl` ON `wl`.`location_code` = `wsds`.`location`
	INNER JOIN `wms_warehouse_areas` AS `wwa` ON `wl`.`warehouse_area_id` = `wwa`.`id` 
WHERE
	`wsds`.`existing_number` > '0' 
	AND `wsds`.`location` IN ('" . join($location, '\',\'') . "') 
	AND `wsds`.`customer_id` IN ( '1', '2' ) 
GROUP BY
	`wsds`.`customer_id`,
	`wsds`.`location`,
	`wsds`.`sku_code`,
	`wsds`.`quality_level` 
ORDER BY
	`wsds`.`id`";
        $res = DB::connection("mysql_qawms")->select($sql);
        foreach ($location as $v) {
            $result['location'][$v] = $this->generateQRcodeBase64ImageString($v);
        }
        foreach ($res as $v) {
            $key = $v->location;
            $v->sku_code_qr_code = $this->generateQRcodeBase64ImageString($v->sku_code . '-0-NEW');
            $result['skuCode'][$key][] = $v;
        }
        ksort($result);
        Cache::put($cacheKey, serialize($result), 3600);
        return $result;
    }

    protected function timeTo() {
        date_default_timezone_set("Asia/Shanghai");
        $time = time();
        $today = date('Y-m-d', $time);
        $position = '2020-05-31';// 定位大周
        $weekend = date('Y-m-d', ($time + (7 - (date('w') == 0 ? 7 : date('w'))) * 24 * 3600));
        $isSmall = ((strtotime($weekend) - strtotime($position)) / 86400 / 7) % 2;

        $title = $isSmall ? '小' : '大';
        $weekend = $isSmall ? date('Y-m-d', strtotime($weekend) - 86400) : date('Y-m-d', strtotime($weekend) - 86400 * 2);
        $timeTo = [
            0 => [
                'name' => '距离下班',
                'to' => $today . ' 19:00',
            ],
            1 => [
                'name' => '距离' . $title . '周',
                'to' => $weekend . ' 18:00',
            ],
        ];
        foreach ($timeTo as &$item) {
            $diff = strtotime($item['to']) - $time;
            $diff < 0 && $diff = 0;
            $item['1'] = $diff . '秒';
            $item['2'] = round($diff / 60, 9) . '分钟';
            $item['3'] = round($diff / 3600, 9) . '小时';
            $item['4'] = round($diff / 86400, 9) . '天';
            $item['5'] = round($diff / 86400 / 30, 9) . '月';
            $item['6'] = round($diff / 86400 / 30 / 356, 9) . '年';
        }
        return $timeTo;
    }

    public function params(Request $request) {
        Log::info("接收到参数" . json_encode($request->all()));
        return $_GET;
        return $request->all();
    }

    public function test() {
        $users = DB::select('select * from users ');
        exit(var_dump($users));
    }

    private function configs() {
        $testDomain = '.dev.patpat.vip';
        $testDomainTop = '.dev.patpat.top';
        $domainLine = '.1000shores.com';
        $domainLine2 = '.interfocus.org';
        $configs = [
//            'QAWMS' => [
//                'name' => 'QAWMS',
//                'color' => '#F17C67',
//                'domain' => '',
//                'items' => [
//                    0 => [
//                        'url' => 'http://qa-wms.lan/',
//                        'name' => 'Lan',
//                        'target' => '_self',
//                    ],
//                    1 => [
//                        'url' => 'http://qa-wms' . $testDomain,
//                        'name' => 'Dev',
//                        'target' => '_self',
//                    ],
//                    4 => [
//                        'url' => 'http://qa-wms' . $testDomainTop,
//                        'name' => 'Top',
//                        'target' => '_self',
//                    ],
//                    5 => [
//                        'url' => 'http://wms' . $domainLine,
//                        'name' => '1000',
//                        'target' => '_self',
//                    ],
//                    2 => [
//                        'url' => 'http://52.221.152.145:88/server/qa-wms/merge_requests/new#',
//                        'name' => 'Git',
//                        'target' => '_self',
//                    ],
//                    3 => [
//                        'url' => 'http://192.168.8.17:8080/jenkins/job/qa-wms/',
//                        'name' => 'Jenkins',
//                        'target' => '_self',
//                    ],
//                ]
//            ],
//            'SMS' => [
//                'name' => 'SMS',
//                'color' => '#C3BED4',
//                'domain' => '',
//                'items' => [
//                    0 => [
//                        'url' => 'http://sms.lan/',
//                        'name' => 'Lan',
//                        'target' => '_self',
//                    ],
//                    1 => [
//                        'url' => 'http://sms' . $testDomain,
//                        'name' => 'Dev',
//                        'target' => '_self',
//                    ],
//                    4 => [
//                        'url' => 'http://sms' . $testDomainTop,
//                        'name' => 'Top',
//                        'target' => '_self',
//                    ],
//                    5 => [
//                        'url' => 'http://sms' . $domainLine,
//                        'name' => '1000',
//                        'target' => '_self',
//                    ],
//                    2 => [
//                        'url' => 'http://52.221.152.145:88/server/sms/merge_requests/new#',
//                        'name' => 'Git',
//                        'target' => '_self',
//                    ],
//                    3 => [
//                        'url' => 'http://192.168.8.17:8080/jenkins/job/sms/',
//                        'name' => 'Jenkins',
//                        'target' => '_self',
//                    ],
//                ]
//            ],
//            'QATMS' => [
//                'name' => 'QATMS',
//                'color' => '#495A80',
//                'domain' => '',
//                'items' => [
//                    0 => [
//                        'url' => 'http://qa-tms.lan/',
//                        'name' => 'Lan',
//                        'target' => '_self',
//                    ],
//                    1 => [
//                        'url' => 'http://qa-tms' . $testDomain,
//                        'name' => 'Dev',
//                        'target' => '_self',
//                    ],
//                    4 => [
//                        'url' => 'http://qa-tms' . $testDomainTop,
//                        'name' => 'Top',
//                        'target' => '_self',
//                    ],
//
//                    5 => [
//                        'url' => 'http://tms' . $domainLine,
//                        'name' => '1000',
//                        'target' => '_self',
//                    ],
//                    2 => [
//                        'url' => 'http://52.221.152.145:88/server/qa-tms/merge_requests/new#',
//                        'name' => 'Git',
//                        'target' => '_self',
//                    ],
//                    3 => [
//                        'url' => 'http://192.168.8.17:8080/jenkins/job/qa-tms/',
//                        'name' => 'Jenkins',
//                        'target' => '_self',
//                    ],
//                ]
//            ],
            'WMS' => [
                'name' => 'WMS',
                'color' => '#9966CC',
                'domain' => '',
                'items' => [
                    0 => [
                        'url' => 'http://wms.lan/',
                        'name' => 'Lan',
                        'target' => '_self',
                    ],
                    1 => [
                        'url' => 'http://wms' . $testDomain,
                        'name' => 'Dev',
                        'target' => '_self',
                    ],
                    5 => [
                        'url' => 'http://wms' . $domainLine2,
                        'name' => 'Line',
                        'target' => '_self',
                    ],
                    2 => [
                        'url' => 'http://52.221.152.145:88/server/wms/merge_requests/new#',
                        'name' => 'Git',
                        'target' => '_self',
                    ],
                    3 => [
                        'url' => 'http://192.168.8.17:8080/jenkins/job/wms-test',
                        'name' => 'Jenkins',
                        'target' => '_self',
                    ],
                ]
            ],
            'TMS' => [
                'name' => 'TMS',
                'color' => '#495A80',
                'domain' => '',
                'items' => [
                    0 => [
                        'url' => 'http://tms.lan/',
                        'name' => 'Lan',
                        'target' => '_self',
                    ],
                    1 => [
                        'url' => 'http://tms' . $testDomain,
                        'name' => 'Dev',
                        'target' => '_self',
                    ],

                    5 => [
                        'url' => 'http://tms' . $domainLine2,
                        'name' => 'Line',
                        'target' => '_self',
                    ],
                    2 => [
                        'url' => 'http://52.221.152.145:88/server/tms/merge_requests/new#',
                        'name' => 'Git',
                        'target' => '_self',
                    ],
                    3 => [
                        'url' => 'http://192.168.8.17:8080/jenkins/job/tms-test/',
                        'name' => 'Jenkins',
                        'target' => '_self',
                    ],
                ]
            ],
            'OC' => [
                'name' => 'OC',
                'color' => '#BDB76A',
                'domain' => '',
                'items' => [
                    0 => [
                        'url' => 'http://oc.lan/',
                        'name' => 'Lan',
                        'target' => '_self',
                    ],
                    1 => [
                        'url' => 'http://oc' . $testDomain,
                        'name' => 'Dev',
                        'target' => '_self',
                    ],

                    5 => [
                        'url' => 'http://oc' . $domainLine2,
                        'name' => 'Line',
                        'target' => '_self',
                    ],
                    2 => [
                        'url' => 'http://52.221.152.145:88/server/oc/merge_requests/new#',
                        'name' => 'Git',
                        'target' => '_self',
                    ],
                    3 => [
                        'url' => 'http://192.168.8.17:8080/jenkins/job/oc/',
                        'name' => 'Jenkins',
                        'target' => '_self',
                    ],
                ]
            ],
            'FMS' => [
                'name' => 'FMS',
                'color' => '#483C32',
                'domain' => '',
                'items' => [
                    0 => [
                        'url' => 'http://fms.lan/',
                        'name' => 'Lan',
                        'target' => '_self',
                    ],
                    1 => [
                        'url' => 'http://fms' . $testDomain,
                        'name' => 'Dev',
                        'target' => '_self',
                    ],

                    5 => [
                        'url' => 'http://fms' . $domainLine2,
                        'name' => 'Line',
                        'target' => '_self',
                    ],
                    2 => [
                        'url' => 'http://52.221.152.145:88/server/fms/merge_requests/new#',
                        'name' => 'Git',
                        'target' => '_self',
                    ],
                    3 => [
                        'url' => 'http://192.168.8.17:8080/jenkins/job/fms/',
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
                        'url' => 'https://exmail.qq.com/login',
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
                    3 => [
                        'url' => 'http://confluence.interfocus.org/#all-updates',
                        'name' => 'confluence',
                        'target' => '_self',
                    ],
                    4 => [
                        'url' => 'https://13.250.226.26/assets/user-asset/',
                        'name' => '堡垒机',
                        'target' => '_self',
                    ],
                ]
            ],
        ];

        return $configs;
    }

    public function index(Request $request) {
        $configs = $this->configs();
        $qcTest = $this->qcTest();
        $timeTo = $this->timeTo();
        return view('loc.index', compact('configs', 'qcTest', 'timeTo'));
    }

    public function multiPage(Request $request) {
        $pages = base64_decode($request->pages);
        $pagesArr = explode(",", trim($pages, ','));
        return view('loc.multiPage', [
            'pages' => $pagesArr
        ]);
    }

//    private function getConfigOne()
//    {
//        $tab = [
//            'lan' => [
//                'type' => 'warehouse',
//                'name' => 'LAN',
//                'color' => '#00838F',
//                'domain' => 'http://127.0.0.1'
//            ],
//            'dev' => [
//                'type' => 'warehouse',
//                'name' => '开发',
//                'color' => '#00838F',
//                'domain' => 'http://qa-wms.dev.interfocus.org'
//            ],
//            'build' => [
//                'type' => 'build',
//                'color' => '#6A1B9A',
//                'name' => '工具',
//                'domain' => ''
//            ],
//        ];
//        $jsUrl = '';
//        $mqUrl = '';
//        foreach ($tab as $item) {
//            $item['type'] === 'warehouse' && $mqUrl .= $item['domain'] . '/?c=of_base_com_mq&__OF_DEBUG__=,';
//            $item['type'] === 'warehouse' && $jsUrl .= $item['domain'] . '/?c=of_base_htmlTpl_tool&a=index&__OF_DEBUG__=,';
//        }
//        $url = [
//            'OA需求' => [
//                'url' => 'https://oa.yafex.cn/?c=gts_task&a=index&type=0&indexType=2&devUser=%E8%92%8B%E5%B0%9A%E5%90%9B',
//                'target' => '_self',
//                'type' => 'build',
//            ],
//            '构建' => [
//                'url' => 'https://opsoa.yafex.cn/flow/gitflow/list',
//                'target' => '_self',
//                'type' => 'build',
//            ],
//            '测试构建' => [
//                'url' => 'https://jenkins.yafex.cn/job/wms4sz2dev/',
//                'target' => '_self',
//                'type' => 'build',
//            ],
//            '刷新JS' => [
//                'url' => '/home/multiPage/' . base64_encode(trim($jsUrl, ',')),
//                'target' => '_self',
//                'type' => 'build',
//            ],
//            '重启消息队列' => [
//                'url' => '/home/multiPage/' . base64_encode(trim($mqUrl, ',')),
//                'target' => '_self',
//                'type' => 'build',
//            ],
//            'APS' => [
//                'url' => 'http://localhost:8888/aps/src/',
//                'target' => '_self',
//                'type' => 'build',
//            ],
//            'intelligence' => [
//                'url' => 'http://localhost:8888/intelligence/src/',
//                'target' => '_self',
//                'type' => 'build',
//            ],
//            'OPS-DB' => [
//                'url' => 'https://opsoa.yafex.cn/flow/db/list',
//                'target' => '_self',
//                'type' => 'build',
//            ],
//            '主页' => [
//                'url' => '/',
//                'target' => '_self',
//                'type' => 'warehouse',
//            ],
//            'MBB' => [
//                'url' => '/mobile.php',
//                'target' => '_self',
//                'type' => 'warehouse',
//            ],
//            '消息队列' => [
//                'url' => '/?c=ctrl_queueManage&a=index',
//                'target' => '_self',
//                'type' => 'warehouse',
//            ],
//            '出库列表' => [
//                'url' => '/?c=ctrl_pickOrder&a=index&orderState=40&problemType=normal&PHC=1',
//                'target' => '_self',
//                'type' => 'warehouse',
//            ],
//            '基础环境' => [
//                'url' => '/?c=ctrl_dd&a=index',
//                'target' => '_self',
//                'type' => 'warehouse',
//            ],
//            'TEST' => [
//                'url' => '/?c=demo_jiangshangjun&a=index',
//                'target' => '_self',
//                'type' => 'warehouse',
//            ],
//            '错误日志' => [
//                'url' => '/?c=of_base_error_tool&__OF_DEBUG__',
//                'target' => '_self',
//                'type' => 'warehouse',
//            ],
//        ];
//        return [$tab, $url];
//    }
}
