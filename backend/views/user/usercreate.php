<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\groups;
use yii\helpers\ArrayHelper;
use app\models\Department;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = '新增用户';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= $form->field($model, 'username')->label('用户名') ?>
                <?= $form->field($model, 'password')->passwordInput()->label('密码') ?>
                <?= $form->field($model, 'department_id')->dropDownList(ArrayHelper::map(Department::find()->all(), 'id', 'departmentname'))?>
                <div class="form-group">
                    <?= Html::submitButton('创建', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
