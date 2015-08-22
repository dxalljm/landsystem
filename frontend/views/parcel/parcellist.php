<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Parcel;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\parcelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '宗地明细'
?>
<div class="parcel-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('打印', '', ['class' => 'btn btn-success']) ?>
    </p>

    <table class="table table-striped table-bordered table-hover table-condensed">
    
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
			$i=1;
    	foreach($parcels as $parcel) {?>
    	<tr>
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
    </table>

</div>
