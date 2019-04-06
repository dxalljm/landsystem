<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Department;
use app\models\Userlevel;
/* @var $this yii\web\View */
/* @var $model app\models\user */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	<?= Html::a('添加', ['usercreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['userupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['userdelete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('添加权限', ['/role/roleaddchild', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'email:email',
            'status',
            [
            	'attribute' => 'created_at',
            	'value' => date('Y-m-d H:i:s',$model->created_at),
            ],
            [
            	'attribute' => 'updated_at',
            	'value' => date('Y-m-d H:i:s',$model->updated_at),
            ],
            [
            	'label' => '所属科室',
            	'attribute' => 'department_id',
            	'value' => Department::find()->where(['id'=>$model->department_id])->one()['departmentname'],
            ],
            [
                'attribute' => 'level',
                'value' => Userlevel::find()->where(['id'=>$model->level])->one()['levelname'],
            ],
            'ip',
            'mac',
            
        ],
    ]) ?>

</div>
