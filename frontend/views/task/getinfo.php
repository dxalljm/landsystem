<?php
namespace frontend\controllers;
use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use dosamigos\datetimepicker\DateTimePicker;
use frontend\helpers\ActiveFormrdiv;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\breedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '详细信息';
?>
<!--<section class="content">-->
<!--    <div class="row">-->
<!--        <div class="col-xs-12">-->
<!--            --><?php //User::tableBegin($this->title);?>
            <?php
             echo $result['html'];
            ?>
            <?php  User::tableEnd();?>
<!--        </div>-->
<!--    </div>-->
<!--</section>-->
