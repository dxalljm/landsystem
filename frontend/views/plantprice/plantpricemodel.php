<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Farms;
use app\models\Collection;
/* @var $this yii\web\View */
/* @var $model app\models\PlantPrice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plant-price-form">

         <!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               正在生成相关数据，请耐心等待。
            </h4>
         </div>
         <div class="modal-body">
           <div class="progress">
           <?php 
	           $farms = Farms::find()->where(['state'=>1]);
	           $farmCount = $farms->count();
	           $i=0;
	           foreach ($farms->all() as $farm) {
	           	$i++;
	           	$collection = Collection::find()->where(['farms_id'=>$farm['id'],'payyear'=>$model->years])->one();
	           	$oldCollection = Collection::find()->where(['farms_id'=>$farm['id'],'payyear'=>$model->years-1])->one();
	           	if($collection) {
	           		$collectionModel = Collection::findOne($collection['id']);
	           		$collectionModel->update_at = time();
	           	} else {
	           		$collectionModel = new Collection();
	           		$collectionModel->create_at = time();
	           		$collectionModel->update_at = $collectionModel->create_at;
	           	}
	           	$collectionModel->payyear = $model->years;
	           	$collectionModel->farms_id = $farm['id'];
	           	$collectionModel->amounts_receivable = $collectionModel->getAR($model->years,$farm['id']);
	           	$collectionModel->ypayarea = $farm['contractarea'];
	           	$collectionModel->ypaymoney = $collectionModel->amounts_receivable;
	           	$collectionModel->dckpay = 0;
	           	$collectionModel->state = 0;
	           	$collectionModel->management_area = $farm['management_area'];
	           	if($oldCollection) {
	           		$collectionModel->owe = $oldCollection->owe;
	           	}
	           	$collectionModel->save();
	           	$bfb = ceil ($i/$farmCount).'%';
	           	echo '<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: '.$bfb.';">';
	            echo '<span class="sr-only">40% 完成</span>';
	           	echo '</div>';
	           }
// 	           $this->redirect(['plantpriceindex']);
           ?>
			   
			</div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" 
               data-dismiss="modal">关闭
            </button>
         </div>
      </div><!-- /.modal-content -->
</div><!-- /.modal -->
</div>
</div>