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

    <h1><?= Farms::find()->where(['id'=>$_GET['id']])->one()['farmname']; ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<?php if($areas < Farms::find()->where(['id'=>$_GET['id']])->one()['measure']) {?>
    <p>
         <?= Html::a('添加', ['leasecreate','id'=>$_GET['id']], ['onclick'=>'leasecreate('.$_GET['id'].','.$_GET['year'].')','data-toggle' => 'modal','data-target' => '#leasecreate-modal','class' => 'btn btn-success']) ?>
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'leasecreate-modal',
	'size'=>'modal-lg',
	//'header' => '<h4 class="modal-title"></h4>',
	'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',

]); 

?>
<?php \yii\bootstrap\Modal::end(); ?>