<?php

namespace frontend\controllers;

use app\models\Goodseed;
use app\models\Logs;
use app\models\Saleswhere;
use Yii;
use app\models\Sales;
use frontend\models\salesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Plantingstructurecheck;
use app\models\Yields;
use app\models\Theyear;
use app\models\Farms;
use app\models\Plant;
use app\models\User;

/**
 * SalesController implements the CRUD actions for Sales model.
 */
class SalesController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        } else {
            return true;
        }
    }
//     public function beforeAction($action)
//     {
//     	$action = Yii::$app->controller->action->id;
//     	if(\Yii::$app->user->can($action)){
//     		return true;
//     	}else{
//     		throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
//     	}
//     }
    /**
     * Lists all Sales models.
     * @return mixed
     */
    public function actionSalesindex($farms_id)
    {
        $planting = Plantingstructurecheck::find()->where(['farms_id'=>$farms_id,'year'=>User::getLastYear()])->all();
        Logs::writeLogs('农产品销售信息');
        return $this->render('salesindex', [
			'plantings' => $planting,
        ]);
    }

    public function actionSalesindexajax($farms_id)
    {
        $planting = Plantingstructurecheck::find()->where(['farms_id'=>$farms_id,'year'=>User::getLastYear()])->all();
        Logs::writeLogs('农产品销售信息');
        return $this->renderAjax('salesindexajax', [
            'plantings' => $planting,
        ]);
    }

    public function actionSaleslist($planting_id,$farms_id)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $searchModel = new SalesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Logs::writeLogs('农产品销售列表');
        return $this->render('saleslist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'planting_id' =>$planting_id,
            'farms_id' => $farms_id,
        ]);
    }

    public function actionSaleslistajax($planting_id,$farms_id)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $searchModel = new SalesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Logs::writeLogs('农产品销售列表');
        return $this->renderAjax('saleslistajax', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'planting_id' =>$planting_id,
            'farms_id' => $farms_id,
        ]);
    }
    /**
     * Displays a single Sales model.
     * @param integer $id
     * @return mixed
     */
    public function actionSalesview($id)
    {
        $model = $this->findModel($id);
        Logs::writeLogs('查看农产品销售信息',$model);
        return $this->render('salesview', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Sales model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSalescreate($farms_id,$planting_id)
    {
    	
        $model = new Sales();
//         $planting = Plantingstructure::find()->where(['farms_id'=>$farms_id])->all();
		$volume = Sales::getVolume($planting_id);
        $plantcheck = Plantingstructurecheck::find()->where(['id'=>$planting_id])->one();
        $goodseed = '';
        $plant = Plant::findOne($plantcheck['plant_id'])->typename;
        if($plantcheck['goodseed_id']) {
            $goodseed = Goodseed::findOne($plantcheck['goodseed_id'])->typename;
        }
        if ($model->load(Yii::$app->request->post())) {
//            var_dump($model->whereabouts);
            $where = Saleswhere::find()->where(['wherename'=>$model->whereabouts])->one();
//            var_dump($where);exit;
            if($where) {
                $model->whereabouts = (string)$where['id'];
            } else {
                $whereModel = new Saleswhere();
                $whereModel->wherename = $model->whereabouts;
                $whereModel->save();
//                var_dump($whereModel);
                $model->whereabouts = (string)$whereModel->id;
            }
            $model->year = User::getLastYear();
        	$model->create_at = time();
        	$model->update_at = $model->create_at;
        	$model->management_area = Farms::getFarmsAreaID($farms_id);
        	$model->plant_id = $plantcheck['plant_id'];
//        	var_dump($model);exit;
            $model->save();
//            var_dump($model->getErrors());exit;
            Logs::writeLogs('创建农产品销售信息',$model);
            return $this->redirect(['salesindex', 'farms_id' => $farms_id]);
        } else {
            return $this->render('salescreate', [
                'model' => $model,
            	'volume' => $volume,
                'plant' => $plant,
                'goodseed' => $goodseed,
                'plant_id' => $plantcheck['plant_id'],
            ]);
        }
    }

    public function actionSalescreateajax($farms_id,$planting_id)
    {

        $model = new Sales();
//         $planting = Plantingstructure::find()->where(['farms_id'=>$farms_id])->all();
        $volume = Sales::getVolume($planting_id);
        $plantcheck = Plantingstructurecheck::find()->where(['id'=>$planting_id])->one();
        $plant = Plant::findOne($plantcheck['plant_id'])->typename;
        $goodseed = '';
        if($plantcheck['goodseed_id']) {
            $goodseed = Goodseed::findOne($plantcheck['goodseed_id'])->typename;
        }
        if ($model->load(Yii::$app->request->post())) {
            $where = Saleswhere::find()->where(['wherename'=>$model->whereabouts])->one();
//            var_dump($where);exit;
            if($where) {
                $model->whereabouts = (string)$where['id'];
            } else {
                $whereModel = new Saleswhere();
                $whereModel->wherename = $model->whereabouts;
                $whereModel->save();
//                var_dump($whereModel);
                $model->whereabouts = (string)$whereModel->id;
            }
            $model->year = User::getLastYear();
            $model->create_at = time();
            $model->update_at = $model->create_at;
            $model->management_area = Farms::getFarmsAreaID($farms_id);
            $model->plant_id = $plantcheck['plant_id'];
//        	var_dump($model);exit;
            $save = $model->save();
//            var_dump($model->getErrors());exit;
            Logs::writeLogs('创建农产品销售信息',$model);
            echo json_encode(['state' => $save,'farms_id'=>$farms_id]);
        } else {
            return $this->renderAjax('salescreateajax', [
                'model' => $model,
                'volume' => $volume,
                'plant' => $plant,
                'goodseed' => $goodseed,
                'plant_id' => $plantcheck['plant_id'],
            ]);
        }
    }

   
    
    /**
     * Updates an existing Sales model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSalesupdate($id,$farms_id,$planting_id)
    {
        $model = $this->findModel($id);
        $planting = Plantingstructurecheck::find()->where(['farms_id'=>$farms_id])->all();
        $volume = Sales::getVolume($planting_id);
        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->save();
            Logs::writeLogs('更新农产品销售信息',$model);
            return $this->redirect(['salesview', 'id' => $model->id]);
        } else {
            return $this->render('salesupdate', [
                'model' => $model,
            	'volume' => $volume,
            ]);
        }
    }

    /**
     * Deletes an existing Sales model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSalesdelete($id,$farms_id)
    {
        $model =  $this->findModel($id);
        $model->delete();
        Logs::writeLogs('删除农产品销售信息',$model);

        return $this->redirect(['salesindex','farms_id' => $farms_id]);
    }

    public function actionSalesdeleteajax($id)
    {
        $model =  $this->findModel($id);
        $state = $model->delete();
        Logs::writeLogs('删除农产品销售信息',$model);
        echo json_encode(['state'=>1]);
    }

    public function actionSalessearch($begindate,$enddate)
    {
    	if(isset($_GET['tab']) and $_GET['tab'] !== \Yii::$app->controller->id) {
    		if($_GET['tab'] == 'yields')
    			$class = 'plantingstructureSearch';
    		else
    			$class = $_GET['tab'].'Search';
    		return $this->redirect ([$_GET['tab'].'/'.$_GET['tab'].'search',
    				'tab' => $_GET['tab'],
    				'begindate' => strtotime($_GET['begindate']),
    				'enddate' => strtotime($_GET['enddate']),
// 					$class =>['management_area' =>  $_GET['management_area']],
    		]);
    	} 
    	$searchModel = new salesSearch();
		if(!is_numeric($_GET['begindate']))
			 $_GET['begindate'] = strtotime($_GET['begindate']);
		if(!is_numeric($_GET['enddate']))
			 $_GET['enddate'] = strtotime($_GET['enddate']);
// 		var_dump($_GET);exit;
    	$dataProvider = $searchModel->searchSearch ( $_GET );
        Logs::writeLogs('综合查询-销售信息');
    	return $this->render('salessearch',[
	    			'searchModel' => $searchModel,
	    			'dataProvider' => $dataProvider,
	    			'tab' => $_GET['tab'],
	    			'begindate' => $_GET['begindate'],
	    			'enddate' => $_GET['enddate'],
	    			'params' => $_GET,
    	]);    	
    }
    /**
     * Finds the Sales model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sales the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sales::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
