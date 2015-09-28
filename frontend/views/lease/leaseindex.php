<?php

use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Farms;
use app\models\Lease;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="lease-index">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= Farms::find()->where(['id'=>$_GET['farms_id']])->one()['farmname']; ?>
                    </h3>
                </div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<?php if($areas) {?>
    <p><?php //echo $areas;?>
    	<?= Html::a('添加', ['leasecreate','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success']) ?>
         <?php //echo Html::a('添加', 'javascript:void(0)', ['onclick'=>'lease.create('.$_GET['id'].')', 'class' => 'btn btn-success', 'id' => 'wubaiqing']) ?>
    </p>
	<?php } else {?>
	<p>
	<?= Html::a('查看明细', ['leaseallview','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
	</p>
	<?php }?>
	<script type="text/javascript">
	function openwindows(url)
	{
		window.open(url,'','width=1200,height=600,top=50,left=80, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');
		self.close();
	}
	</script>
	<?php if($areas) {?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'lessee',
            [
            	'attribute' => 'lease_area',
            	'label' => '宗地',
            ],
            
            [
            	'label' => '总面积',
            	'value' => function ($model) {
            		return Lease::getListArea($model->lease_area).'亩';
           		},
            ],
            //'plant_id',
            //'farms_id',

             [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {update} {delete}',
            'buttons' => [
                // 下面代码来自于 yii\grid\ActionColumn 简单修改了下
                'view' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', '查看'),
                        'aria-label' => Yii::t('yii', 'View'),
                        'data-pjax' => '0',
                    ];
                    $url.='&farms_id='.$_GET['farms_id'];
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                },
                'update' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', '更新'),
                        'aria-label' => Yii::t('yii', 'Update'),
                        'data-pjax' => '0',
                    ];
                    $url.='&farms_id='.$_GET['farms_id'];
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                },
                'delete' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', '删除'),
                        'aria-label' => Yii::t('yii', 'Delete'),
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                    ];
                    $url.='&farms_id='.$_GET['farms_id'];
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                },
            	]
       	 	],
        ],
    ]); ?>
<?php } else {?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'lease_area',
            'lessee',
            //'plant_id',
            //'farms_id',

             [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'buttons' => [
                // 下面代码来自于 yii\grid\ActionColumn 简单修改了下
                'view' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', 'View'),
                        'aria-label' => Yii::t('yii', 'View'),
                        'data-pjax' => '0',
                    ];
                    $url.='&farms_id='.$_GET['farms_id'];
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                },
            	]
       	 	],
        ],
    ]); ?>
<?php }?>
	                </div>
            </div>
        </div>
    </div>
</section>
</div>

