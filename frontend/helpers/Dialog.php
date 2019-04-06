<?php
namespace frontend\helpers;
use app\models\Logs;
use yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User: liujiaming
 * Date: 2018/3/3
 * Time: 10:55
 */

class Dialog
{
    public static function show($classname,$where)
    {
        $html = '';
        $html.= '<div id="outDialog" title="数据导出"></div>';
        $html.= '<div id="selectDialog" title="请选择要导出项"></div>';
        $html.= '<script>';
        $html.= 'function dialog()';
        $html.= '{';
        $html.= '$.get("index.php?r=excelout/excelout", {classname: "'.$classname.'"}, function (body) {';
        $html.= '$("#selectDialog").html(body);';
        $html.= '$("#selectDialog").dialog("open");';
        $html.= '});';
        $html.= '}';

        $html.= '$( "#outDialog" ).dialog({';
        $html.= 'autoOpen: false,';
        $html.= 'width: 1200,';
        $html.= 'modal: true,';
        $html.= 'buttons: [';
        $html.= '{';
        $html.= 'text: "关闭",';
        $html.= 'class:"btn btn-success",';
        $html.= 'click: function() {';
        $html.= '$( this ).dialog( "close" );';
        $html.= '}';
        $html.= '},';
        $html.= ']';
        $html.= '});';

        $html.= '$( "#selectDialog" ).dialog({';
        $html.= 'autoOpen: false,';
        $html.= 'width: 1200,';
        $html.= 'modal: true,';
        $html.= 'buttons: [';
        $html.= '{';
        $html.= 'text: "确定",';
        $html.= 'class:"btn btn-success",';
        $html.= 'click: function() {';
        $html.= '$( this ).dialog( "close" );';
        $html.= '$.get("index.php?r=excelout/datalist",{"classname":"'.$classname.'","where":\''.$where.'\',"field":$("#Selected").val()},function (body) {';
        $html.= '$("#outDialog").html(body);';
        $html.= '$("#outDialog").dialog("open");';
        $html.= '});';
        $html.= '}';
        $html.= '},';
        $html.= '{';
        $html.= 'text: "取消",';
        $html.= 'class:"btn btn-danger",';
        $html.= 'click: function() {';
        $html.= '$( this ).dialog( "close" );';
        $html.= '}';
        $html.= '}';
        $html.= ']';
        $html.= '});';
        $html.= '</script>';
        Logs::writeLogs('xls农场数据导出');
        return $html;
    }
}