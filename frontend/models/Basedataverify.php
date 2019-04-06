<?php

namespace app\models;

use Yii;
/**
 */
class Basedataverify extends \yii\db\ActiveRecord
{
    public static $tablename = '岭南生态农业示范区农业种植基础数据核实汇总表';

    public static $plantablename = '岭南生态农业示范区农业种植基础数据计划汇总表';

    public static function getDataFields()
    {
        return [
            ['name' => 'row', 'type' => 'number'],
            ['name' => 'management_area', 'type'=> 'string'],
            ['name' => 'farmname', 'type'=> 'string'],
            ['name' => 'farmername', 'type'=> 'string'],
            ['name' => 'contractnumber', 'type'=> 'string'],
            ['name' => 'lease', 'type'=> 'string'],
            ['name' => 'cardid', 'type'=> 'string'],
            ['name' => 'telephone', 'type'=> 'string'],
            ['name' => 'contractarea', 'type'=> 'string'],
            ['name' => 'ddarea', 'type'=> 'number'],
            ['name' => 'ddzongdi', 'type'=> 'string'],
            ['name' => 'ddbtfarmer', 'type'=> 'string'],
            ['name' => 'ddbtlease', 'type'=> 'string'],
            ['name' => 'ymarea', 'type'=> 'number'],
            ['name' => 'ymzongdi', 'type'=> 'string'],
            ['name' => 'ymbtfarmer', 'type'=> 'string'],
            ['name' => 'ymbtlease', 'type'=> 'string'],
            ['name' => 'xm', 'type'=> 'number'],
            ['name' => 'mls', 'type'=> 'number'],
            ['name' => 'zd', 'type'=> 'number'],
            ['name' => 'by', 'type'=> 'number'],
            ['name' => 'lm', 'type'=> 'number'],
            ['name' => 'other', 'type'=> 'number'],
            ['name' => 'verifydate', 'type'=> 'string'],
            ['name' => 'content', 'type'=> 'string'],
        ];
    }


    public static function getColumns()
    {
        return [
            ['text' => '序号','dataField' =>'row','width' => '40','align'=>'center','editable'=>false,'cellsAlign'=>"center"],
            ['text' => '管理区','dataField' =>'management_area','width' => '150','editable'=>false,'align'=>'center','cellsAlign'=>"center"],
            ['text' => '农场','dataField' =>'farmname','width' => '150','editable'=>false,'align'=>'center','cellsAlign'=>"center"],
            ['text' => '法人', 'dataField' =>'farmername','width' => '100','editable'=>false,'align'=>'center','cellsAlign'=>"center"],
            ['text' => '合同号', 'dataField' =>'contractnumber','width' => '150','editable'=>false,'align'=>'center','cellsAlign'=>"center"],
            ['text' => '种植者','dataField' =>'lease','width' => '100','align'=>'center','cellsAlign'=>"center"],
            ['text' => '身份证号','dataField' =>'cardid','width' => '200','align'=>'center','cellsAlign'=>"center"],
            ['text' => '联系方式','dataField' =>'telephone','width' => '150','align'=>'center','cellsAlign'=>"center"],
            ['text' => '合同面积','dataField' =>'contractarea','width' => '80','editable'=>false,'align'=>'center','cellsAlign'=>"right",'aggregates'=> 'sum'],
            ['text' => '面积','dataField' =>'ddarea','width' => '50','align'=>'center','columngroup' => 'dd','cellsAlign'=>"right"],
            ['text' => '地号','dataField' =>'ddzongdi','width' => '100','align'=>'center','columngroup' => 'dd','cellsAlign'=>"left"],
            ['text' => '补贴归属(法人)','dataField' =>'ddbtfarmer','width' => '100','align'=>'center','columngroup' => 'dd','cellsAlign'=>"right"],
            ['text' => '补贴归属(种植者)','dataField' =>'ddbtlease','width' => '100','align'=>'center','columngroup' => 'dd','cellsAlign'=>"right"],
            ['text' => '面积','dataField' =>'ymarea','width' => '50','align'=>'center','columngroup' => 'ym','cellsAlign'=>"right"],
            ['text' => '地号','dataField' =>'ymzongdi','width' => '100','align'=>'center','columngroup' => 'ym','cellsAlign'=>"left"],
            ['text' => '补贴归属(法人)','dataField' =>'ymbtfarmer','width' => '100','align'=>'center','columngroup' => 'ym','cellsAlign'=>"right"],
            ['text' => '补贴归属(种植者)','dataField' =>'ymbtlease','width' => '100','align'=>'center','columngroup' => 'ym','cellsAlign'=>"right"],
            ['text' => '小麦','dataField' =>'xm','width' => '50','align'=>'center','cellsAlign'=>"right"],
            ['text' => '马铃薯','dataField' =>'mls','width' => '50','align'=>'center','cellsAlign'=>"right"],
            ['text' => '杂豆','dataField' =>'zd','width' => '50','align'=>'center','cellsAlign'=>"right"],
            ['text' => '北药','dataField' =>'by','width' => '50','align'=>'center','cellsAlign'=>"right"],
            ['text' => '蓝莓','dataField' =>'lm','width' => '50','align'=>'center','cellsAlign'=>"right"],
            ['text' => '其它','dataField' =>'other','width' => '50','align'=>'center','cellsAlign'=>"right"],
            ['text' => '核实日期','dataField' =>'verifydate','width' => '100','align'=>'center','cellsAlign'=>"right"],
            ['text' => '备注','dataField' =>'content','width' => '200','align'=>'center','cellsAlign'=>"left"],

        ];
    }

    public static function getColumngroups()
    {
        return [
            ['text' => '大豆', 'align' => 'center', 'name' => 'dd'],
            ['text' => '玉米', 'align' => 'center', 'name' => 'ym'],
        ];
    }
    
}
