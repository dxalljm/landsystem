<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Plant;
use app\models\Subsidiestype;
use yii\helpers\Url;
use app\models\Huinonggrant;
use app\models\ManagementArea;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\HuinongSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'huinong';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="huinong-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>                    </h3>
                </div>
                <div class="box-body">
    			<table class="table table-bordered table-hover">
    				<tr>
    					<td align="center">补贴类型</td>
    					<td align="center">补贴种类</td>
    					<td align="center">状态</td>
    					<td align="center">操作</td>
    				</tr>
    				<?php
//     					var_dump($huinongs);
    					foreach ($huinongs as $value) {
    						
    					$type = Subsidiestype::find()->where(['id'=>$value['subsidiestype_id']])->one();
//     					var_dump($type);
    					?>
    				<tr>
    					<td align="center"><?= $type['typename']?></td>
    					<td align="center"><?php $classFile = 'app\\models\\'.$type['urladdress'];
				            		$data = $classFile::find()->where(['id'=>$value['typeid']])->one();
// 				            		var_dump($data);
				            		if($type['urladdress'] == 'Plant')
				            			echo $data['cropname'];
				            		if($type['urladdress'] == 'Goodseed') {
				            			$plant = Plant::find()->where(['id'=>$data['plant_id']])->one();
				            			echo $plant['cropname'].'/'.$data['plant_model'];
				            		} ?>
            		</td>
            		<td align="center"><?php
            			$marea = [];
            			foreach (ManagementArea::find()->all() as $area) {
            			$huinonggrant = Huinonggrant::find()->where(['management_area'=>$area['id'],'huinong_id'=>$value['id'],'issubmit'=>1])->count();
            			if($huinonggrant) 
            				$marea[] = ['areaname'=>$area['areaname'],'num'=>$huinonggrant];
            			}?>
            				<div class="btn-group">
	            				<div class="btn dropdown-toggle"
		            				data-toggle="dropdown" data-trigger="hover">
		            				<?php if(count($marea) == 7) echo '管理区已经全部提交'; else echo '已有'. count($marea).'个管理区提交 ';?><span class="caret"></span>
	            				</div>
	            				<ul class="dropdown-menu" role="menu">
		            				<?php foreach ($marea as $val) {?>
		            					<li><a href="#"><?= $val['areaname'].':'.$val['num']?>户</a></li>
		            				<?php }?>
	            				</ul>
            				</div>
            			</td>
            		<td align="center"><?php 
            		if(count($marea)) 
            			echo html::a('补贴发放',Url::to('index.php?r=huinong/huinongsearch&huinong_id='.$value['id']),['class'=>'btn btn-success']);
            		else 
            			echo '等待地产科确认提交'?></td>
    				</tr>
    				<?php }?>
    			</table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
