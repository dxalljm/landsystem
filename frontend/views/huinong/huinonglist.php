<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Plant;
use app\models\Subsidiestype;
use yii\helpers\Url;
use app\models\Huinonggrant;
use dosamigos\datetimepicker\DateTimePicker;
use yii\widgets\ActiveFormrdiv;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\HuinongSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'huinong';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
// var_dump($huinongs);
?>
<div class="huinong-index">
<?php $form = ActiveFormrdiv::begin(); ?>
    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header"><?php if(empty($date)) $date = date('Y');?>
                    <table><tr><td><h3 class="box-title"><?= $this->title ?></h3></td><td width="30"></td><td width="60"><?= DateTimePicker::widget([
                'id' => 'setYear',
				'name' => 'setyear',
				'value' => $date,
				'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
// 				'options' => ['class'=>''],
				'inline' => false, 
		    	'language'=>'zh-CN',
		        'clientOptions' => [
		            'autoclose' => true,
		        	'startView' => 4,
		        	'minView' => 4,
		            'format' => 'yyyy'
				]
			]);?></td><td>年度</td></tr></table>
                </div>
                <div class="box-body">
                 
    			<table class="table table-bordered table-hover">
    				<tr>
    					<td align="center">补贴类型</td>
    					<td align="center">补贴种类</td>
    					<td align="center">操作</td>
    				</tr>
    				<?php foreach ($huinongs as $value) {
    					$type = Subsidiestype::find()->where(['id'=>$value['subsidiestype_id']])->one();
    					?>
    				<tr>
    					<td align="center"><?= $type['typename']?></td>
    					<td align="center"><?php $classFile = 'app\\models\\'. $type['urladdress'];
				            		$data = $classFile::find()->where(['id'=>$value['typeid']])->one();
				    				if($type['urladdress'] == 'Plant')
				            			echo $data['cropname'];
				            		if($type['urladdress'] == 'Goodseed') {
				            			$plant = Plant::find()->where(['id'=>$data['plant_id']])->one();
								        echo $plant['cropname'].'/'.$data['plant_model'];
				            		}
				            		 ?>
            		</td>
            		<td align="center"><?php echo html::a('补贴对象确认',Url::to('index.php?r=huinong/huinongdata&id='.$value['id']),['class'=>'btn btn-success']);?></td>
    				</tr>
    				<?php }?>
    			</table>
    			
                </div>
            </div>
        </div>
    </div>
</section>
<?php ActiveFormrdiv::end(); ?>
</div>
<script>
	$('#setYear').change(function(){
		$("form").submit();
	});
</script>