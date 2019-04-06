<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\AssignmentForm;
use yii\grid\DataColumn;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\userSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '业务管理';
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
            // 'email:email',
            // 'status',
            // 'created_at',
            /*  [
			 	'label'=>'角色',
			 	'value'=>function($model){
			 		$arr = [];
                    $assignment = AssignmentForm::find()->where(['user_id'=>$model->id])->all();
                    foreach($assignment as $val)
                    {
                    	$arr[] = $val->item_name;
                    }
                    $roles = implode(',', $arr);
                    return $roles;
                },
			], */
            // 'groups',

           /*  [
            	'class' => 'backend\helpers\eActionColumn','header' => '操作',
            	
            ], */
            [
                'label'=>'更多操作',
                'format'=>'raw',
            	//'class' => 'btn btn-primary btn-lg',
                'value' => function($model,$key){
                   // $url = ['/user/userassign','id'=>$model->id];
                    return Html::a('业务更新权限','#', [
                    'id' => 'businessupdate',
                    'title' => '管理用户是否有更新某业务的权限',
                    //'class' => 'btn btn-primary btn-lg',
                    'data-toggle' => 'modal',
                    'data-target' => '#businessupdate-modal',
                    //'data-id' => $key,
                    'onclick'=> 'businessupdate('.$key.')',
                    //'data-pjax' => '0',

                ]);
                }
            ]        
        ],
    ]); ?>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'businessupdate-modal',
	'size'=>'modal-lg',

]); 

?>

<?php \yii\bootstrap\Modal::end(); ?>
</div>
