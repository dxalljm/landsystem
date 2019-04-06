<?php
namespace frontend\controllers;
use app\models\Saleswhere;
use app\models\User;
use app\models\Yieldbase;
use frontend\helpers\MoneyFormat;
use Yii;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Lease;
use app\models\Farms;
use app\models\Plant;
use app\models\Goodseed;
use app\models\Plantinputproduct;
use app\models\Inputproduct;
use app\models\Yields;
use app\models\Sales;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\salesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'sales';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-index">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getLastYear()?>年度)</font></h3></div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
 
<h4>必须先添加“产量信息”后，才可以添加“销量信息”。</h4>
<table class="table table-bordered table-hover">
	    <tr>
	    	<td align="center">承租人/法人</td>
	    	<td align="center">承包面积</td>
	    	<td align="center">作物</td>
	    	<td align="center">良种型号</td>
	    	<td align="center">总产量(平均值)</td>
	    	<td align="center">操作</td>
	    </tr>
	    <?php foreach ($plantings as $planting) {
	   
	    	?>
	    <?php $name = Lease::find()->where(['id'=>$planting->lease_id])->one()['lessee'];
	    $yields = Yieldbase::find()->where(['plant_id'=>$planting->plant_id,'year'=>User::getLastYear()])->one();
	    ?>
	    <tr>
	    	<td align="center"><?php if($name == '') echo Farms::find()->where(['id'=>$_GET['farms_id']])->one()['farmername']; else echo $name;?></td>
	    	<td align="center"><?= $planting->area;?>亩</td>
	    	<td align="center"><?= Plant::find()->where(['id'=>$planting->plant_id])->one()['typename']?></td>
	    	<td align="center"><?= Goodseed::find()->where(['id'=>$planting->goodseed_id])->one()['typename']?></td>
	    	<td align="center"><?= MoneyFormat::num_format($yields['yield']*$planting->area)?>斤</td>
	    	<td align="center"><?php echo Html::a('销售信息', '#', ['class' => 'btn btn-xs btn-success','onclick'=>'setDialog('.$planting['id'].','.$_GET['farms_id'].')']) ?></td>
	    </tr>
		<?php $salses = Sales::find()->where(['planting_id'=>$planting->id])->all();
		if($salses) {
			foreach($salses as $value) {
		?>
	    <tr>
	    	<td width="20%" align="center">|_</td>
		    <td align="center">销售去向：<?= Saleswhere::find()->where(['id'=>$value['whereabouts']])->one()['wherename'] ?></td>
		    <td align="center">销售量：<?= $value['volume'] ?>斤</td>
		    <td align="center">销售单价：<?= $value['price'] ?>元</td>
		    <td align="center">销售总价：<?= $value['volume']*$value['price'] ?>元</td>
		    <td width="12%" align="center"><?= Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', [
					'onclick' => 'deleteSales('.$value['id'].','.$value['planting_id'].','.$value['farms_id'].')',
                    'title' => Yii::t('yii', '删除'),
                ]);?></td>
	    </tr>
	    <?php }}}?>
    </table>

    
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<script>
	$('#Refresh').val(true);
	function setDialog(planting_id,farms_id) {
		$.get('index.php?r=sales/salescreateajax', {planting_id:planting_id,farms_id: farms_id}, function (body) {
			$('#dialogMsg').html('');
			$('#dialogMsg').html(body);
			$("#dialogMsg").dialog("open");
		});
	}
	function deleteSales(id,planting_id,farms_id) {
		$.getJSON('index.php?r=sales/salesdeleteajax',{id:id},function(data) {
			if(data.state == 1) {
				$.get('index.php?r=sales/salesindexajax', {planting_id:planting_id,farms_id: farms_id}, function (body) {
					$('#dialogMsg').html('');
					$('#dialogMsg').html(body);
					$("#dialogMsg").dialog("open");
				});
			}
		});
	}
</script>