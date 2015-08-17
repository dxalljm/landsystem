<?php

use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Farms;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="lease-index">

    <h1><?= Farms::find()->where(['id'=>$_GET['farms_id']])->one()['farmname']; ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<?php if($areas < Farms::find()->where(['id'=>$_GET['farms_id']])->one()['measure']) {?>
    <p>
    	<?= Html::a('添加', ['leasecreate','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success']) ?>
         <?php //echo Html::a('添加', 'javascript:void(0)', ['onclick'=>'lease.create('.$_GET['id'].')', 'class' => 'btn btn-success', 'id' => 'wubaiqing']) ?>
    </p>
	<?php }?>
	<script type="text/javascript">
	function openwindows(url)
	{
		window.open(url,'','width=1200,height=600,top=50,left=80, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');
		self.close();
	}
	</script>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'lease_area',
            'lessee',
            'plant_id',
            //'farms_id',

             [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {update} {delete}',
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
                'update' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', 'Update'),
                        'aria-label' => Yii::t('yii', 'Update'),
                        'data-pjax' => '0',
                    ];
                    $url.='&farms_id='.$_GET['farms_id'];
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                },
                'delete' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', 'Delete'),
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

</div>

