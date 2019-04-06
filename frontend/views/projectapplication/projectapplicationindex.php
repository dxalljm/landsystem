<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Infrastructuretype;
use yii\helpers\Url;
use app\models\Reviewprocess;
use app\models\Processname;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\projectapplicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'projectapplication';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projectapplication-index">

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"></h3>
					</div>
					<div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['projectapplicationcreate','farms_id'=>$farms_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
//         'id' => function ($model) {
//         	return $model->id;
//     	},
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//             'id',
            [
            	'attribute' => 'projecttype',
            	'value' => function($model) {
            		return Infrastructuretype::find()->where(['id'=>$model->projecttype])->one()['typename'];
            }
            ],
            'content',
            [
            	'attribute' => 'projectdata',
            	'value' => function ($model) {
            		return $model->projectdata.$model->unit;
            }
            ],
			[
				'label' => '审核状态',
				'attribute' => 'state',
				'format'=>'raw',
				'value' => function ($model) {
					$reviewprocess = Reviewprocess::find()->where(['id'=>$model->reviewprocess_id])->one();
					$html = '';
					$result = Reviewprocess::state($reviewprocess['state']);
					if($reviewprocess['state'] == 5) {
						$html.= '<div class="btn-group">';
						$html.=  '<div class="dropdown-toggle" data-toggle="dropdown" data-trigger="hover">';
						$html.= $result.'<span class="caret"></span>';
						$html.=  '</div>';
						$html.=  '<ul class="dropdown-menu" role="menu">';
						foreach (Reviewprocess::getProcess($reviewprocess['actionname']) as $val) {
							//$html.= '<li><a href="#">111</a></li>';
							$html.=  '<li>'.Processname::find()->where(['Identification'=>$val])->one()['processdepartment'].':'.Reviewprocess::state($reviewprocess[$val]).'</li>';
						}
						$html.=  '</ul>';
						$html.=  '</div>';
						return Html::a($html,'#',['class' => 'dropdown-toggle', ]);
					} else 
						return $result;
					
			} ], 
			['class' => 'frontend\helpers\eActionColumn'], 
			[
				'label'=>'操作', 
				'format'=>'raw',  
				'value' => function($model,$key){ 
					$url = Url::to(['print/printproject','id'=>$model->id]);
					$option = '打印申请'; 
					$title = ''; 
					return Html::a($option,$url, [ 
							'id' => 'farmerland', 
							'title' => $title, 
							'class' => 'btn btn-primary	btn-xs', 
							
					]); 
			}], 
    	], 
    ]); ?>
			</div>
		</div>

</div>
</div>
</section>
</div>
