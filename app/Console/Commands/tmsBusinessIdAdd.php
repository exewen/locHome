<?php

namespace App\Console\Commands;

use App\Models\Outwarehouse\OutboundBags;
use App\Models\Outwarehouse\OutboundOrder;
use App\Models\Outwarehouse\OutboundWeightLabel;
use App\Models\Outwarehouse\PickOrder;
use App\Models\Role;
use App\Models\Rule;
use App\Models\User;
use Log, DB;
use App\Events\UpdateRuleCache;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class tmsBusinessIdAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:tms_business_id_add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '扩展tms渠道id物流商di';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @desc 拉取客户信息
     * @throws \Exception
     */
    public function handle()
    {
        if (1 || $this->confirm('确认初始化菜单? [y|N]')) {
            $this->handleProgress();
        }
    }

    protected function getIdsArr($businessCodes, $methodCodes, $data)
    {
        $saveData = [];
        foreach ($data as $items) {
            $temp['id'] = $items->id;
            if(isset($items->logistics_provider)){
                $newBusinessId = array_get($businessCodes, $items->logistics_provider);
                !empty($newBusinessId) && $temp['logistics_provider'] = $newBusinessId;
            }
            if(isset($items->logistics_provider_code)){
                $newBusinessId = array_get($businessCodes, $items->logistics_provider_code);
                !empty($newBusinessId) && $temp['logistics_provider'] = $newBusinessId;
            }
            if(isset($items->logistics_channel)){
                $newMethodId = array_get($methodCodes, $items->logistics_channel);
                !empty($newMethodId) && $temp['logistics_channel'] = $newMethodId;
            }
            if (isset($temp['logistics_provider']) || isset($temp['logistics_channel'])) {
                $saveData[] = $temp;
            }
        }
        return $saveData;
    }

    protected function updateBatch2($tableName = "",$field=[])
    {
        $business = \Logistics::getBusiness();
        $method = \Logistics::getBusinessMethod();
        $q = "UPDATE " . $tableName . " SET ";
        foreach ($field as $column){
            if(in_array($column,['logistics_provider','logistics_provider_code'])){
                $q .= $column . " = CASE ";
                foreach ($business as $data) {
                    $q .= "WHEN " . $column . " = '" . $data['business_code'] . "' THEN '" . $data['id'] . "' ";
                }
                $q .= "ELSE " . $column . " END, ";
            }
            if(in_array($column,['logistics_channel'])){
                $q .= $column . " = CASE ";
                foreach ($method as $data) {
                    $q .= "WHEN " . $column . " = '" . $data['method_code'] . "' THEN '" . $data['id'] . "' ";
                }
                $q .= "ELSE " . $column . " END, ";
            }
        }
        $q = rtrim($q, ", ");
        return $q;

    }
    protected function updateBatch($tableName = "", $multipleData = array())
    {

        if ($tableName && !empty($multipleData)) {
            // column or fields to update
            $updateColumn = array_keys($multipleData[0]);
            $referenceColumn = $updateColumn[0]; //e.g id
            unset($updateColumn[0]);
            $whereIn = "";

            $q = "UPDATE " . $tableName . " SET ";
            foreach ($updateColumn as $uColumn) {
                $q .= $uColumn . " = CASE ";

                foreach ($multipleData as $data) {
                    $q .= "WHEN " . $referenceColumn . " = " . $data[$referenceColumn] . " THEN '" . $data[$uColumn] . "' ";
                }
                $q .= "ELSE " . $uColumn . " END, ";
            }
            foreach ($multipleData as $data) {
                $whereIn .= "'" . $data[$referenceColumn] . "', ";
            }
            $q = rtrim($q, ", ") . " WHERE " . $referenceColumn . " IN (" . rtrim($whereIn, ', ') . ")";

            // Update
            return DB::update(DB::raw($q));

        } else {
            return false;
        }

    }

    private function handleProgress()
    {
        try {
            $res=$this->updateBatch2('wms_outbound_orders',[
                'logistics_provider',
                'logistics_channel',
            ]);
            dd($res);
            $businessCodes = array_column(\Logistics::getBusiness(), 'id', 'business_code');
            $methodCodes = array_column(\Logistics::getBusinessMethod(), 'id', 'method_code');
            $order = 'CK01200410000038';
            $orderNot = ['CK01200408000030'];
            $time = '2020-04-11 03:12:31';
            // wms_outbound_orders
            $mode = OutboundOrder::query()->where('created_at', '<=', $time);
            $mode->where('outbound_order_code','CK01200304000001');
            $data = $mode->get(['id', 'logistics_provider', 'logistics_channel']);
            $saveData = $this->getIdsArr($businessCodes, $methodCodes, $data);
            $this->updateBatch('wms_outbound_orders', $saveData);

            // wms_outbound_weight_label
            $mode = OutboundWeightLabel::query()->where('created_at', '<=', $time);
            $data = $mode->get(['id', 'logistics_channel']);
            $saveData = $this->getIdsArr($businessCodes, $methodCodes, $data);
            $this->updateBatch('wms_outbound_weight_label', $saveData);

            // wms_outbound_bags
            $mode = OutboundBags::query()->where('created_at', '<=', $time);
            $data = $mode->get(['id', 'logistics_provider_code']);
            $saveData = $this->getIdsArr($businessCodes, $methodCodes, $data);
            $this->updateBatch('wms_outbound_bags', $saveData);

            // wms_pick_orders
            $mode = PickOrder::query()->where('created_at', '<=', $time);
            $data = $mode->get(['id', 'logistics_provider']);
            $saveData = $this->getIdsArr($businessCodes, $methodCodes, $data);
            $this->updateBatch('wms_pick_orders', $saveData);

            // sms_outbound_order
//            $mode = PickOrder::query()->where('created_at', '<=', $time);
//            $data = $mode->get(['id', 'logistics_provider', 'logistics_channel']);
//            $saveData = $this->getIdsArr($businessCodes, $methodCodes, $data);
//            $this->updateBatch('sms_outbound_order', $saveData);

            $this->comment('扩展成功');
        } catch (\Exception $e) {
            $this->comment("扩展失败:{$e->getMessage()}");
        }
    }
}
