<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Insurance */

?>
<div class="insurance-create">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">下载报表</h3>
                </div>
                <div class="box-body">
    <?= Html::a('种植业保险情况统计表.xls',iconv("gb2312//IGNORE","utf-8",$filename))?>

</div>
