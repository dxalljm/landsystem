<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\groups;
use yii\helpers\ArrayHelper;
use app\models\Department;
use app\models\Userlevel;
use app\models\Mainmenu;
use app\models\MenuToUser;
/* @var $this yii\web\View */
/* @var $model app\models\user */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>
    
    <?= $form->field($model, 'realname')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'auth_key')->hiddenInput(['maxlength' => 32])->label(false) ?>

    <?= $form->field($model, 'password_hash')->hiddenInput(['maxlength' => 255])->label(false) ?>

    <?= $form->field($model, 'password_reset_token')->hiddenInput(['maxlength' => 255])->label(false) ?>

    <?= $form->field($model, 'email')->hiddenInput(['maxlength' => 255])->label(false) ?>

    <?= $form->field($model, 'status')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'updated_at')->hiddenInput()->label(false) ?>
    
	<?= $form->field($model, 'department_id')->dropDownList(ArrayHelper::map(Department::find()->all(), 'id', 'departmentname')) ?>

    <?= $form->field($model, 'level')->dropDownList(ArrayHelper::map(Userlevel::find()->all(), 'id', 'levelname')) ?>
    
    <?= $form->field($model, 'ip')->textInput() ?>
    
    <?= $form->field($model, 'mac')->textInput() ?>

    <?php
    if($model->auditinguser)
        $model->auditinguser = explode(',',$model->auditinguser);
    ?>

    <?= $form->field($model, 'auditinguser')->checkboxList(MenuToUser::getAuditingList())?>


    <?php
    $plantarr = ArrayHelper::map(Mainmenu::find()->where(['typename'=>1])->all(), 'id', 'menuname');
    $plantarr[51] = '农机器具';
    $plantarr[83] = '固定资产登记';
    $plateArray = explode('/',$model->plate);
//    var_dump($plateArray);
    $plateResult = '';
    echo '<h5>管辖板块及权限</h5>';
    echo '<table>';
    foreach ($plantarr as $key => $plant) {
            if($plateArray[0]) {
                foreach ($plateArray as $value) {
                    $plateValue = explode('-', $value);
                    if ($key == $plateValue[1]) {
                        $exValue = explode(',',$plateValue[2]);
                        foreach ($exValue as $val) {
                            $plateResult[] = $key . '-' . $val;
                        }
                    }
                }
            }
        echo '<tr>';
        echo '<td>';
        echo $plant;
        echo '</td>';
        echo '<td>';
        echo Html::checkboxList('plate',$plateResult,[$key.'-view'=>'查看',$key.'-create'=>'新增',$key.'-update'=>'更新',$key.'-delete'=>'删除']);
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
//    var_dump($plateResult);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
