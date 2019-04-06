<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;
use app\models\Mainmenu;
use app\models\MenuToUser;
use yii\helpers\ArrayHelper;
use app\models\AuthItem;
/* @var $this yii\web\View */
/* @var $model app\models\MenuToUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-to-user-form">

    <?php $form = ActiveForm::begin(); ?>
	
    <?= $form->field($model, 'role_id')->dropDownList(ArrayHelper::map(AuthItem::find()->where(['type'=>1])->all(), 'name', 'name')) ?>
	<?php $parents = MenuToUser::find()->where(['role_id'=>$model->role_id])->one();?>
    <?php 
		$data = Mainmenu::find()->where(['typename'=>0])->orderBy('sort ASC')->all();
		$menus =ArrayHelper::map($data,'id', 'menuname');
		$model->menulist = explode(',',$parents['menulist']);
		$model->plate = explode(',',$parents['plate']);
		$model->businessmenu = explode(',',$parents['businessmenu']);
		$model->searchmenu = explode(',',$parents['searchmenu']);
	?>
    <?= $form->field($model, 'menulist')->checkboxList($menus); ?>
	<?php $plantarr = ArrayHelper::map(Mainmenu::find()->where(['typename'=>1])->all(), 'id', 'menuname');?>
	<?= $form->field($model, 'plate')->checkboxList($plantarr)->label('八大板块'); ?>
	
	<?php $business = ArrayHelper::map(Mainmenu::find()->where(['typename'=>2])->all(),'id','menuname');?>
	<?= $form->field($model, 'businessmenu')->checkboxList($business)->label('业务菜单'); ?>
	<?php $searchmenu = MenuToUser::getSearchList();?>
	<?= $form->field($model, 'searchmenu')->checkboxList($searchmenu)?>
	<?= $form->field($model, 'auditinguser')->checkboxList(MenuToUser::getAuditingList())?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'menutousercreate-modal',
	'size'=>'modal-sm',
	'header' => '<h4 class="modal-title">请选择一个用户</h4>',
	'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',

]); 
echo '<div id="modalContent">';
?>
<?php 
$users = User::find()->all();
//print_r($tables);
foreach($users as $val)
{	
	echo '&nbsp; &nbsp;'.$val['id'].':&nbsp; &nbsp; ';
	echo Html::a($val['username'],'#',['onclick'=>'setMenuUserid('.$val['id'].')']);
	echo '<br>';
}
echo '</div>';

?>

<?php \yii\bootstrap\Modal::end(); ?>
</div>