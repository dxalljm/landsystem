<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Userlevel;
use yii\grid\DataColumn;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\userSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建用户', ['usercreate'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
             'passwordshow',
            // 'status',
            // 'created_at',
             [
                 'attribute' => 'level',
			 	'value'=>function($model){
			 		$result = Userlevel::find()->where(['id'=>$model->level])->one()['levelname'];
                    return $result;
                },
			],
            // 'groups',

            [
            	'class' => 'backend\helpers\eActionColumn','header' => '操作',
            	
            ],
//            [
//                'label'=>'更多操作',
//                'format'=>'raw',
//            	//'class' => 'btn btn-primary btn-lg',
//                'value' => function($model,$key){
//                   // $url = ['/user/userassign','id'=>$model->id];
//                    return Html::a('配置向导','#', [
//                    'id' => 'createassign',
//                    'title' => '给用户分配权限',
//                    //'class' => 'btn btn-primary btn-lg',
//                    'data-toggle' => 'modal',
//                    'data-target' => '#activity-modal',
//                    //'data-id' => $key,
//                    'onclick'=> 'assign('.$key.')',
//                    //'data-pjax' => '0',
//
//                ]);
//                }
//            ]
            [
                //'label'=>'更多操作',
                'format'=>'raw',
                //'class' => 'btn btn-primary btn-lg',
                'value' => function($model,$key){
                    // $url = ['/user/userassign','id'=>$model->id];
                    return Html::a('配置向导','#', [
                        'id' => 'createassign',
                        'title' => '给用户分配权限',
                        //'class' => 'btn btn-primary btn-lg',
//                        'data-toggle' => 'modal',
//                        'data-target' => '#activity-modal',
                        //'data-id' => $key,
                        'onclick' => "javascript:window.open('".yii::$app->urlManager->createUrl(['/user/userconfigure','user_id'=>$model->id])."','','width=700,height=400,top=200,left=300, location=no, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');return false;",
                        //'data-pjax' => '0',

                    ]);
                }
            ]
        ],
    ]); ?>
<?php
\yii\bootstrap\Modal::begin([
    'id' => 'activity-modal',
	'size'=>'modal-lg',

]);

?>

<?php \yii\bootstrap\Modal::end(); ?>
</div>
