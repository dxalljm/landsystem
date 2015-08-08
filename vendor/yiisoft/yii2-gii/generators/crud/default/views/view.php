<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = 'ID:'.$model-><?= $generator->getNameAttribute() ?>;
$title = Tables::find()->where(['tablename'=><?= $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['<?= $generator->classString('{modelClass} ', ['modelClass' => StringHelper::basename($generator->modelClass)])?>index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">

    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>

    <p>
    	 <?= "<?= " ?>Html::a(<?= $generator->generateString('添加') ?>, ['<?= $generator->classString('{modelClass} ', ['modelClass' => StringHelper::basename($generator->modelClass)])?>create', <?= $urlParams ?>], ['class' => 'btn btn-success']) ?>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('更新') ?>, ['<?= $generator->classString('{modelClass} ', ['modelClass' => StringHelper::basename($generator->modelClass)])?>update', <?= $urlParams ?>], ['class' => 'btn btn-primary']) ?>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('删除') ?>, ['<?= $generator->classString('{modelClass} ', ['modelClass' => StringHelper::basename($generator->modelClass)])?>delete', <?= $urlParams ?>], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => <?= $generator->generateString('您确定要删除这项吗？') ?>,
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= "<?= " ?>DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo "            '" . $name . "',\n";
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
    }
}
?>
        ],
    ]) ?>

</div>
