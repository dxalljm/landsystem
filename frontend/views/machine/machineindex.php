<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\MachineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'machine';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="machine-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;农机器具<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['machinecreate'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('XLS导入', ['machinexls'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//             'id',
            'productname',
            'implementmodel',
            'filename',
            'enterprisename',
            'state',
            'year',
            // 'enterprisename',
            // 'parameter:ntext',

            ['class' => 'frontend\helpers\eActionColumn'],
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
