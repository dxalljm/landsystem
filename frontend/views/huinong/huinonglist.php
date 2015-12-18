<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Plant;
use app\models\Subsidiestype;
use yii\helpers\Url;
use app\models\Huinonggrant;

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
    					<td align="center">操作</td>
    				</tr>
    				<?php foreach ($huinongs as $value) {?>
    				<tr>
    					<td align="center"><?= Subsidiestype::find()->where(['urladdress'=>$value['subsidiestype_id']])->one()['typename']?></td>
    					<td align="center"><?php $classFile = 'app\\models\\'. $value['subsidiestype_id'];
				            		$data = $classFile::find()->where(['id'=>$value['typeid']])->one();
				            		if($value['subsidiestype_id'] == 'plant')
				            			echo $data['cropname'];
				            		if($value['subsidiestype_id'] == 'goodseed') {
				            			$plantcropname = Plant::find()->where(['id'=>$data['plant_id']])->one()['cropname'];
				            			echo $plantcropname.'/'.$data['plant_model'];
				            		} ?>
            		</td>
            		<td align="center"><?php 
            		if(Huinonggrant::find()->where(['huinong_id'=>$value['id']])->count()) 
            			echo html::a('补贴发放情况',Url::to('index.php?r=huinong/huinongdatainfo&id='.$value['id']),['class'=>'btn btn-success']);
            		else 
            			echo html::a('补贴对你确认',Url::to('index.php?r=huinong/huinongdata&id='.$value['id']),['class'=>'btn btn-success']);?></td>
    				</tr>
    				<?php }?>
    			</table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
