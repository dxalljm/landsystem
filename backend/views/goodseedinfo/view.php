<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Goodseedinfo */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Goodseedinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goodseedinfo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'farms_id',
            'management_area',
            'lease_id',
            'planting_id',
            'plant_id',
            'goodseed_id',
            'zongdi',
            'area',
            'create_at',
            'update_at',
            'year',
        ],
    ]) ?>

</div>
