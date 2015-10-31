<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\ManagementArea;
use app\models\Cooperative;
use yii\grid\GridView; 
use yii\web\View;
use app\models\Cooperativetype;
use app\models\Parcel;
use app\models\Farms;
/* @var $this yii\web\View */
/* @var $model app\models\farms */

?>
<div class="farms-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        农场转让信息
                    </h3>
                </div>
                <div class="box-body">
	<p>
    	 <?= Html::a('过户', ['farmstransfer', 'farms_id' => $farms_id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('宗地转让', ['farmsttpozongdi', 'farms_id' => $farms_id],['class' => 'btn btn-primary']) ?>
        <?= Html::a('分户', ['farmssplit', 'farms_id' => $farms_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('合并', ['farmsmerge', 'farms_id' => $farms_id], ['class' => 'btn btn-primary']) ?>
    </p>
    <?php if(!empty($ttpoModel)) {?>
    <h3>过户信息</h3>
    <table class="table table-bordered table-hover">
    	<tr>
    		<td align="center" valign="middle">原农场</td>
    		<td align="center" valign="middle">过户农场</td>
    		<td align="center" valign="middle">过户时间</td>
    		<td align="center" valign="middle">操作</td>
    	</tr>
    <?php foreach($ttpoModel as $value) {?>
       	<tr><?php $oldfarm = Farms::find()->where(['id'=>$value['oldfarms_id']])->one(); $newfarm = Farms::find()->where(['id'=>$value['newfarms_id']])->one();?>
    		<td align="center" valign="middle"><?= $oldfarm->farmname ?>(<?= $oldfarm->farmername ?>)</td>
    		<td align="center" valign="middle"><?= $newfarm->farmname ?>(<?= $newfarm->farmername ?>)</td>
    		<td align="center" valign="middle"><?= date('Y-m-d',$value['create_at']) ?></td>
    		<td align="center" valign="middle"><?= Html::a('查看详情', ['farmsttpoview', 'id' => $value['id']], ['class' => 'btn btn-success']) ?></td>
    	</tr>
    	<?php }?>
    </table>
    <?php }?>
    <br>
    <?php if(!empty($ttpozongdiModel)) {?>
    <h3>宗地转让信息</h3>
    <table class="table table-bordered table-hover">
   		 <tr>
    		<td align="center" valign="middle">原农场</td>
    		<td align="center" valign="middle">转让农场</td>
    		<td align="center" valign="middle">过户时间</td>
    		<td align="center" valign="middle">操作</td>
    	</tr>
       	<?php foreach($ttpozongdiModel as $value) {?>
       	<tr><?php $oldfarm = Farms::find()->where(['id'=>$value['oldfarms_id']])->one(); $newfarm = Farms::find()->where(['id'=>$value['newfarms_id']])->one();?>
    		<td align="center" valign="middle"><?= $oldfarm->farmname ?>(<?= $oldfarm->farmername ?>)</td>
    		<td align="center" valign="middle"><?= $newfarm->farmname ?>(<?= $newfarm->farmername ?>)</td>
    		<td align="center" valign="middle"><?= date('Y-m-d',$value['create_at']) ?></td>
    		<td align="center" valign="middle"><?= Html::a('查看详情', ['farmsttpozongdiview', 'id' => $value['id']], ['class' => 'btn btn-success']) ?></td>
    	</tr>
    	<?php }?>
    </table>
    <?php }?>
    
	                </div>
            </div>
        </div>
    </div>
</section>
</div>

