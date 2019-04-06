<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Machineapply */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => 'Machineapplies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="machineapply-create">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?= date('Y')?>年农业机械购置补贴申请表</h3>
                </div>
                <div class="box-body">

                    <?= $this->render('machineapply_form', [
                        'model' => $model,
                        'farm' => $farm,
                        'farmer' => $farmer,
                    ]) ?>

</div>
