<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Reviewprocess;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\loanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'loan';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loan-index">

   <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= Farms::find()->where(['id'=>$_GET['farms_id']])->one()['farmname']; ?>
                    </h3>
                </div>
                <?php Farms::showRow($_GET['farms_id']);?>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<?php if(!Farms::getLocked($_GET['farms_id'])) {?>
    <p>
        <?= Html::a('添加', ['loancreate','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success']) ?>
    </p>
<?php }?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'farms_id',
            'mortgagearea',
            'mortgagebank',
            'mortgagemoney',
            // 'mortgagetimelimit',
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
//             		'label'=>'操作',
            		'format'=>'raw',
            		'value' => function($model,$key){
            			$url = Url::to(['print/printloan','loan_id'=>$model->id,'farms_id'=>$model->farms_id]);
            			$option = '打印申请';
            			$title = '';
            			$html = '';
            			$html .= Html::a($option,$url, [
            					'title' => $title,
            					'class' => 'btn btn-primary	btn-xs',
            			]);
            			$html .= '&nbsp;&nbsp;';
            			$url2 = Url::to(['loan/loanunlock','id'=>$model->id]);
            			
            			$option2 = '解冻';
            			$title2 = '';
            			if(Farms::getLocked($model->farms_id) == 1) {
            				$option2 .= '&nbsp;<i class="fa fa-lock text-red"></i>';
            				$title2 = '已冻结';
            			}
            			$html .= Html::a($option2,$url2, [
            					 
            					'title' => $title2,
            					'class' => 'btn btn-primary	btn-xs',
            		
            			]);
            			return $html;
            		}],            
        ],
    ]); ?>
              </div>
            </div>
        </div>
    </div>
</section>
</div>
