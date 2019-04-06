<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Department;
use app\models\Userlevel;
use app\models\Mainmenu;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\user */

$this->title = $model->realname;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
    <?php
        $plantarr = ArrayHelper::map(Mainmenu::find()->where(['typename'=>1])->all(), 'id', 'menuname');
    echo '<table>';
        foreach ($plantarr as $key => $plant) {
            echo '<tr>';
            echo '<td>';
            echo $plant;
            echo '</td>';
            echo '<td>';
            echo Html::checkboxList('plate','',[$key.'-view'=>'查看',$key.'-create'=>'新增',$key.'-update'=>'更新',$key.'-delete'=>'删除']);
            echo '</td>';
            echo '</tr>';
        }
    echo '</table>';
    ?>

    <div class="form-group">
        <?= Html::submitButton('下一步', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
