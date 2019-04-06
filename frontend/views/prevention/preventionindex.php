<?php
namespace frontend\controllers;
use Yii;
use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Breed;
use app\models\Farms;
use app\models\Breedtype;
use frontend\helpers\MoneyFormat;
use yii\helpers\Url;
use app\models\Breedinfo;
use app\models\Prevention;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\preventionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'prevention';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prevention-index">

     <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3>
                </div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
            	'label' => '养殖场名称',
            	'attribute' => 'breed_id',
            	'value' => function($model) {
            		$breed = Breed::find()->where(['id'=>$model->breed_id])->one();
            		return $breed->breedname;
            	}
            ],
            [
            	'label' => '养殖种类',
            	'attribute' => 'breedtype_id',
            	'value' => function($model) {
            		$breedtype = Breedtype::find()->where(['id'=>$model->breedtype_id])->one();
            		return $breedtype->typename;
            	}
            ],
            [
            	'attribute' => 'number',
            	'value' => function($model) {
	            	$breedtype = Breedtype::find()->where(['id'=>$model->breedtype_id])->one();
	            	return $model->number.$breedtype->unit;
            	}
            ],
            [
	            'attribute' => 'basicinvestment',
	            'value' => function($model) {
	            	return MoneyFormat::num_format($model->basicinvestment).'元';
            }
            ],
            [
	            'attribute' => 'housingarea',
	            'value' => function($model) {
	            	return $model->housingarea.'平米';
            }
            ],
            
            // 'breedtype_id',

            [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{prevention}',
            'buttons' => [
            		'prevention' => function ($url, $model, $key) {
            		$url = Url::to('index.php?r=prevention/preventioncreate&id='.$model->id.'&farms_id='.$_GET['farms_id']);
            			$options = [
            					'title' => Yii::t('yii', '填写防疫情况'),
            					'aria-label' => Yii::t('yii', 'prevention'),
            					'data-pjax' => '0',
            			];
            		$row = Prevention::find()->where(['breedinfo_id'=>$model->id])->count();
            		if(!$row)
						if(User::disabled()) {
							$url = '#';
						}
            			return Html::a('防疫', $url, ['class'=>'btn btn-success']);
            		},
                                
               	]
       	 	],
        ],
    ]); ?>
    <?= GridView::widget([
        'dataProvider' => $preventionData,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			[
				'label' => '养殖品种',
				'attribute' => 'breedinfo_id',
				'value' => function($model) {
					$breedinfo = Breedinfo::find()->where(['id'=>$model->breedinfo_id])->one();
					$breed = Breedtype::find()->where(['id'=>$breedinfo->breedtype_id])->one();
					return $breed->typename;
				}
			],
           //'breedinfo_id',
           'preventionnumber',
           'breedinfonumber',
           'preventionrate',
           'isepidemic',
            
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
