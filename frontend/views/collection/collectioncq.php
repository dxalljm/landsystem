<?php
namespace frontend\controllers;use app\models\User;
use Yii;
use yii\helpers\Url;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Theyear;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\farmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        缴费业务(<?= Theyear::findOne(1)['years']?>年度)
                    </h3>
                </div>
                <div class="box-body">
<br />
    <?= $this->render('farmer_search', [
        'model' => $model,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$script = <<<JS
jQuery('#years').change(function(){
    var year = $(this).val();
    $.get('/landsystem/frontend/web/index.php?r=collection/collectionindex',{year:year},function (data) {
		      $('body').html(data);
		    });
});
JS;
$this->registerJs($script);
?>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'collectioncreate-modal',
	'size'=>'modal-lg',
	'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
	'options' => ['data-keyboard' => 'false', 'data-backdrop' => 'static']
	//'header' => '<h4 class="modal-title"></h4>',
]);?>
<?php \yii\bootstrap\Modal::end(); ?>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'farmercontract-modal',
	'size'=>'modal-lg',
	'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
	//'header' => '<h4 class="modal-title"></h4>',
]);?>
<?php \yii\bootstrap\Modal::end(); ?>