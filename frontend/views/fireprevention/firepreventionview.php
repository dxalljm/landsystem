<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Firepreventionemployee;
use app\models\Farms;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\Fireprevention */
/* @var $form yii\widgets\ActiveForm */
?>
<link rel="stylesheet" href="vendor/bower/viewerjs-master/dist/viewer.css">
<style>
    .pictures {
        margin: 0;
        padding: 0;
        list-style: none;
        max-width: 50rem;
    }

    .pictures > li {
        float: left;
        width: 100%;
        height: 100%;
        margin: 0 -1px -1px 0;
        border: 1px solid transparent;
        overflow: hidden;
    }

    .pictures > li > img {
        width: 100%;
        cursor: -webkit-zoom-in;
        cursor: zoom-in;
    }

    .viewer-download {
        color: #fff;
        font-family: FontAwesome;
        font-size: .75rem;
        line-height: 1.5rem;
        text-align: center;
    }

    .viewer-download::before {
        content: "\f019";
    }

</style>




<div class="fireprevention-form">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
                    <?= Farms::showFarminfo2($_GET['farms_id'])?>
    <?php $form = ActiveFormrdiv::begin(); ?>
<br>
<table width="57%" class="table table-bordered table-hover ">
		<tr>

<td colspan="12" align='center'><span style="font-weight: 200;font-size: 30px">防火、安全、环保合同</span></td>

</tr>

<tr>

<td width="33%" align='center'><span style="font-weight: 200;font-size: 20px">防火合同</span></td>

<td width="33%" align='center'><span style="font-weight: 200;font-size: 20px">安全生产合同</span></td>
<td align='center'><span style="font-weight: 200;font-size: 20px">环境保护协议</span></td>

</tr>

<tr>

<td align='center'><?php viewModel($model->firecontract);?></td>
<td align='center'><?php viewModel($model->safecontract);?></td>
<td align='center'><?php viewModel($model->environmental_agreement);?></td>

</tr>

<tr>
<td colspan="3" align='center'><span style="font-weight: 200;font-size: 30px">农场防火宣传栏</span></td>
</tr>

<tr>

    <td align='center'><span style="font-weight: 200;font-size: 20px">野外作业许可证</span></td>
    <td align='center'><span style="font-weight: 200;font-size: 20px">防火宣传单</span></td>
    <td align='center'><span style="font-weight: 200;font-size: 20px">防火检查整改记录</span></td>
</tr>
<tr>
    <td align='center'><?php viewModel($model->fieldpermit);?>
    <td align='center'><?php viewModel($model->leaflets); ?></td>
    <td align='center'><?php viewModel($model->rectification_record);?></td>
</tr>
<tr>
    <td align='center' height="300px">
        <?php
        if(isset($picArray['fieldpermit'])) {?>
            <div id="fieldpermit">
                <ul class="pictures">
                    <?php
                    foreach ($picArray['fieldpermit'] as $key => $pic):
                        ?>
                        <li><img data-original="<?= 'http://192.168.1.10/'.$pic?>" src="<?= 'http://192.168.1.10/'.$pic?>" ></li>
                        <?php
                    endforeach;
                    ?>
                </ul>
            </div>
        <?php }?></td>
    <td align='center' height="300px">
        <?php
        if(isset($picArray['leaflets'])) {?>
            <div id="leaflets">
                <ul class="pictures">
                    <?php
                    foreach ($picArray['leaflets'] as $key => $pic):
                        ?>
                        <li><img data-original="<?= 'http://192.168.1.10/'.$pic?>" src="<?= 'http://192.168.1.10/'.$pic?>" ></li>
                        <?php
                    endforeach;
                    ?>
                </ul>
            </div>
        <?php }?>
    </td>
    <td align='center' height="300px">
        <?php
        if(isset($picArray['rectification_record'])) {?>
            <div id="rectification_record">
                <ul class="pictures">
                    <?php
                    foreach ($picArray['rectification_record'] as $key => $pic):
                        ?>
                        <li><img data-original="<?= 'http://192.168.1.10/'.$pic?>" src="<?= 'http://192.168.1.10/'.$pic?>" ></li>
                        <?php
                    endforeach;
                    ?>
                </ul>
            </div>
        <?php }?>
    </td>
</tr>
</table>
<table width="57%" class="table table-bordered table-hover">
<tr>

<td colspan="6" align='center'><h3>农场雇工登记</h3></td>

</tr>

<tr>

<td width=8% align='center'>雇工期限</td>

<td align='center'>雇工姓名</td>
<td align='center'>身份证号</td>
<td align='center'>是否吸烟</td>
<td align='center'>智障人员</td>



</tr>
<?php 
foreach($employees as $emp) {
	foreach($emp as $val) {
		$efire = Firepreventionemployee::find()->where(['employee_id'=>$val['id']])->one();
	?>
<tr>

<td width=8% align='center'><?= $val['employeetype'] ?></td>
<td align='center'><?= $val['employeename'] ?></td>
<td align='center'><?= $val['cardid'] ?></td>
<td align='center'><?php viewModel($efire['is_smoking']); ?></td>
<td align='center'><?php viewModel($efire['is_retarded']); ?></td>
</tr>
<?php }}?>
</table>

<?= Html::a('返回', Yii::$app->getRequest()->getReferrer(), ['class' => 'btn btn-success'])?>
    <?php ActiveFormrdiv::end(); ?>
<?php function viewModel($modelname) {
	if($modelname == 0)
		echo '<i class="fa fa-fw fa-times-circle text-danger"></i>';
	else 
		echo '<i class="fa fa-fw fa-check-circle text-success"></i>';
}?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<script src="vendor/bower/viewerjs-master/dist/viewer.js"></script>
<script>
    window.addEventListener('DOMContentLoaded', function () {
        var galley = document.getElementById('fieldpermit');
        var viewer;
        viewer = new Viewer(galley, {
            url: 'data-original',
        });
    });
    window.addEventListener('DOMContentLoaded', function () {
        var galley = document.getElementById('leaflets');
        var viewer;
        viewer = new Viewer(galley, {
            url: 'data-original',
        });
    });
    window.addEventListener('DOMContentLoaded', function () {
        var galley = document.getElementById('rectification_record');
        var viewer;
        viewer = new Viewer(galley, {
            url: 'data-original',
        });
    });
</script>