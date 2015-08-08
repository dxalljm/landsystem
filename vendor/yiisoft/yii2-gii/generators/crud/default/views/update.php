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

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?> ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['<?= $generator->classString('{modelClass} ', ['modelClass' => StringHelper::basename($generator->modelClass)])?>index']];
$this->params['breadcrumbs'][] = ['label' => $model-><?= $generator->getNameAttribute() ?>, 'url' => ['<?= $generator->classString('{modelClass} ', ['modelClass' => StringHelper::basename($generator->modelClass)])?>view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = <?= $generator->generateString('更新') ?>;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-update">

    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>

    <?= "<?= " ?>$this->render('<?= $generator->classString('{modelClass} ', ['modelClass' => StringHelper::basename($generator->modelClass)])?>_form', [
        'model' => $model,
    ]) ?>

</div>
