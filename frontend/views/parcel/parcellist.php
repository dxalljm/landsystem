<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Parcel;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\parcelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '宗地明细'
?>
<div class="parcel-index">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
        <?= Html::a('打印', '', ['class' => 'btn btn-success']) ?>
    </p>

    <table class="table table-bordered table-hover">
    
    	<tr>
    		<td align='center'>序号</td>
    		<td align='center'>地块暂编号</td>
    		<td align='center'>地块统编号</td>
    		<td align='center'>坡位</td>
    		<td align='center'>坡向</td>
    		<td align='center'>坡度</td>
    		<td align='center'>土壤类型</td>
    		<td align='center'>含石量</td>
    		<td align='center'>毛面积</td>
    		<td align='center'>零星地类面积</td>
    		<td align='center'>净面积</td>
    		<td align='center'>图幅号</td>
    	</tr>
    	<?php
			$i=1;$areaSum = 0.0;
    	foreach($parcels as $parcel) {?>
    	<tr><?php $areaSum += $parcel->netarea?>
    		<td align='center'><?= $i?></td>
    		<td align='center'><?= $parcel->temporarynumber?></td>
    		<td align='center'><?= $parcel->unifiedserialnumber?></td>
    		<td align='center'><?= $parcel->powei?></td>
    		<td align='center'><?= $parcel->poxiang?></td>
    		<td align='center'><?= $parcel->podu?></td>
    		<td align='center'><?= $parcel->agrotype?></td>
    		<td align='center'><?= $parcel->stonecontent?></td>
    		<td align='center'><?= $parcel->grossarea?></td>
    		<td align='center'><?= $parcel->piecemealarea?></td>
    		<td align='center'><?= $parcel->netarea?></td>
    		<td align='center'><?= $parcel->figurenumber?></td>
    	</tr>
    	<?php $i++;}?>
    	<tr>
    		<td align='center'>合计</td>
    		<td align='center'></td>
    		<td align='center'></td>
    		<td align='center'></td>
    		<td align='center'></td>
    		<td align='center'></td>
    		<td align='center'></td>
    		<td align='center'></td>
    		<td align='center'></td>
    		<td align='center'></td>
    		<td align='center'><?= $areaSum?></td>
    		<td align='center'></td>
    	</tr>
    	
    </table>
	                </div>
            </div>
        </div>
    </div>
</section>
</div>
