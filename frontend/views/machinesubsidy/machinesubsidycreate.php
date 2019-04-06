<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Machinesubsidy */

$this->title = 'Create Machinesubsidy';
$this->params['breadcrumbs'][] = ['label' => 'Machinesubsidies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="machinesubsidy-create">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                          <?= $this->title ?>                    </h3>
                </div>
                <div class="box-body">

                    <?= $this->render('_form', [
                    'model' => $model,
                    ]) ?>

</div>
