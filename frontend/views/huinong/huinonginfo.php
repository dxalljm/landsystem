<?php
namespace frontend\controllers;
use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Plant;
use app\models\Subsidiestype;
use yii\helpers\Url;
use app\models\Huinonggrant;
use app\models\Huinong;

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
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
    			<table class="table table-bordered table-hover">
    				<tr>
    					<td align="center">补贴类型</td>
    					<td align="center">补贴种类</td>
    					<td align="center">应发人数</td>
    					<td align="center">实发人数</td>
    					<td align="center">进度</td>
    					
    					<td align="center">操作</td>
    				</tr>
    				<?php foreach ($huinongs as $value) {?>
    				<tr>
    					<td align="center"><?= Subsidiestype::find()->where(['urladdress'=>$value['subsidiestype_id']])->one()['typename']?></td>
    					<td align="center"><?php $classFile = 'app\\models\\'. $value['subsidiestype_id'];
				            		$data = $classFile::find()->where(['id'=>$value['typeid']])->one();
				            		if($value['subsidiestype_id'] == 'plant')
				            			echo $data['typename'];
				            		if($value['subsidiestype_id'] == 'goodseed') {
				            			$planttypename = Plant::find()->where(['id'=>$data['plant_id']])->one()['typename'];
				            			echo $planttypename.'/'.$data['typename'];
				            		} ?>
            		</td>
            		<?php $huinonggrant = Huinonggrant::find();
            		$total = $huinonggrant->where(['huinong_id'=>$value['id']])->count();
            		$real = $huinonggrant->where(['huinong_id'=>$value['id'],'state'=>1])->count();
            		if($real) {
            			$bfb = sprintf("%.2f", $real/$total)*100;
            			$bfb = $bfb.'%';
            		}
            		else 
            			$bfb = '地产科未提交';
            		?>
            		<td align="center"><?= $total;?></td>
            		<td align="center"><?= $real;?></td>
            		<td width="30%" align="center"><div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-success" style="width: <?= $bfb?>"></div>
                    </div><span class="badge bg-green"><?= $bfb?></span></td>
            		
            		<td align="center"><?php 
            			echo html::a('查看详情',Url::to('index.php?r=huinong/huinonginfodata&id='.$value['id']),['class'=>'btn btn-success']);?></td>
    				</tr>
    				<?php }?>
    			</table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
