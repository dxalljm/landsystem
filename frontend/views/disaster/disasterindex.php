<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Disastertype;
use app\models\Plant;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\disasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'disaster';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disaster-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php
        if(User::disabled()) {
            echo Html::a('添加', '#', ['class' => 'btn btn-success','disabled'=>User::disabled()]);
        } else {
            echo Html::a('添加', ['disastercreate', 'farms_id' => $_GET['farms_id']], ['class' => 'btn btn-success']);
        }?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//         'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//             'id',
            [
            	'attribute' => 'farms_id',
            	'value' => function ($model) {
            		return Farms::find()->where(['id'=>$model->farms_id])->one()['farmname'];
            }
            ],
//             'farms_id',
            [
            'attribute' => 'disastertype_id',
            'value' => function ($model) {
            	return Disastertype::find()->where(['id'=>$model->disastertype_id])->one()['typename'];
            }
            ],
//             'disastertype_id',
            'disasterarea',
            [
            'attribute' => 'disasterplant',
            'value' => function ($model) {
            	return Plant::find()->where(['id'=>$model->disasterplant])->one()['typename'];
            }
            ],
//             'disasterplant',
            // 'insurancearea',
            // 'yieldreduction',
            // 'socmoney',

            ['class' => 'frontend\helpers\eActionColumn'],
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
