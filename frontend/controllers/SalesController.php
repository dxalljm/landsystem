<?php

namespace frontend\controllers;

use Yii;
use app\models\Sales;
use frontend\models\salesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Plantingstructure;
use app\models\Yields;
use app\models\Theyear;
use app\models\Farms;
use app\models\Plant;

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
        $planting = Plantingstructure::find()->where(['farms_id'=>$farms_id])->all();
        
        return $this->render('salesindex', [
			'plantings' => $planting,
        ]);
    }

    /**
     * Displays a single Sales model.
     * @param integer $id
     * @return mixed
     */
    public function actionSalesview($id)
    {
        return $this->render('salesview', [
            'model' => $this->findModel($id),
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
        if ($model->load(Yii::$app->request->post())) {
        	$model->create_at = time();
        	$model->update_at = $model->create_at;
        	$model->management_area = Farms::getFarmsAreaID($farms_id);
        	$model->plant_id = Plantingstructure::find()->where(['id'=>$planting_id])->one()['plant_id'];
        	$model->save();
            return $this->redirect(['salesindex', 'farms_id' => $farms_id]);
        } else {
            return $this->render('salescreate', [
                'model' => $model,
            	'volume' => $volume,
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
        $planting = Plantingstructure::find()->where(['farms_id'=>$farms_id])->all();
        $volume = Sales::getVolume($planting_id);
        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->save();
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
        $this->findModel($id)->delete();

        return $this->redirect(['salesindex','farms_id' => $farms_id]);
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
					$class =>['management_area' =>  $_GET['management_area']],
    		]);
    	} 
    	$searchModel = new salesSearch();
		if(!is_numeric($_GET['begindate']))
			 $_GET['begindate'] = strtotime($_GET['begindate']);
		if(!is_numeric($_GET['enddate']))
			 $_GET['enddate'] = strtotime($_GET['enddate']);
// 		var_dump($_GET);exit;
    	$dataProvider = $searchModel->searchIndex ( $_GET );
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
