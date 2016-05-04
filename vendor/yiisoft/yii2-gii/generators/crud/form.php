<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\crud\Generator */

echo $form->field($generator, 'modelClass')->label('模型名称，需要写全地址，例：app\models\模型名称');
echo $form->field($generator, 'searchModelClass')->label('模型搜索类，例：frontend\models\模型名称Search');
echo $form->field($generator, 'controllerClass')->label('控制器类，例：frontend\controllers\模型名称Controller');
echo $form->field($generator, 'viewPath');
echo $form->field($generator, 'baseControllerClass');
echo $form->field($generator, 'indexWidgetType')->dropDownList([
    'grid' => 'GridView',
    'list' => 'ListView',
]);
echo $form->field($generator, 'enableI18N')->checkbox();
echo $form->field($generator, 'messageCategory');
