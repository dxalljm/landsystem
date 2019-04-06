<?php

namespace frontend\controllers;

use app\models\Logs;
use Yii;
use app\models\Disaster;
use frontend\models\disasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Theyear;
use app\models\Farms;
/**
 * DisasterController implements the CRUD actions for Disaster model.
 */
class DisasterController extends Controller
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
     * Lists all Disaster models.
     * @return mixed
     */
    public function actionDisasterindex($farms_id)
    {
        $searchModel = new disasterSearch();
        $params = Yii::$app->request->queryParams;
        $params['disasterSearch']['farms_id'] = $farms_id;
        $dataProvider = $searchModel->search($params);
        Logs::writeLog('农场灾害情况',$farms_id);
        return $this->render('disasterindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDisasterinfo()
    {
    	$searchModel = new disasterSearch();
    	$params = Yii::$app->request->queryParams;
    	$whereArray = Farms::getManagementArea()['id'];
    
    	if (empty($params['disasterSearch']['management_area'])) {
    		$params ['disasterSearch'] ['management_area'] = $whereArray;
    	}
    	$dataProvider = $searchModel->search ( $params );
    	if (is_array($searchModel->management_area)) {
    		$searchModel->management_area = null;
    	}
        Logs::writeLogs('灾害查询');
    	return $this->render('disasterinfo', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'params' => $params,
    	]);
    }
    
    /**
     * Displays a single Disaster model.
     * @param integer $id
     * @return mixed
     */
    public function actionDisasterview($id)
    {
        $model = $this->findModel($id);
        Logs::writeLogs('查看灾害情况',$model);
        return $this->render('disasterview', [
            'model' => $model
        ]);
    }

    /**
     * Creates a new Disaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDisastercreate($farms_id)
    {
        $model = new Disaster();

        if ($model->load(Yii::$app->request->post())) {
        	$model->create_at = time();
        	$model->update_at = $model->create_at;
        	$model->management_area = Farms::getFarmsAreaID($farms_id);
        	$model->save();
            Logs::writeLogs('新增农场灾害情况',$model);
            return $this->redirect(['disasterindex', 'farms_id' => $farms_id]);
        } else {
            return $this->render('disastercreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Disaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDisasterupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->save();
            Logs::writeLogs('更新农场灾害情况',$model);
            return $this->redirect(['disasterview', 'id' => $model->id]);
        } else {
            return $this->render('disasterupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Disaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDisasterdelete($id)
    {
        $model= $this->findModel($id);
        $model->delete();
        Logs::writeLogs('删除农场灾害情况',$model);
        return $this->redirect(['disasterindex']);
    }

    public function actionDisastersearch($tab,$begindate,$enddate)
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
    	$searchModel = new disasterSearch();
		if(!is_numeric($_GET['begindate']))
			 $_GET['begindate'] = strtotime($_GET['begindate']);
		if(!is_numeric($_GET['enddate']))
			 $_GET['enddate'] = strtotime($_GET['enddate']);

    	$dataProvider = $searchModel->searchIndex ( $_GET );
        Logs::writeLogs('综合查询-灾害');
    	return $this->render('disastersearch',[
	    			'searchModel' => $searchModel,
	    			'dataProvider' => $dataProvider,
	    			'tab' => $_GET['tab'],
	    			'begindate' => $_GET['begindate'],
	    			'enddate' => $_GET['enddate'],
	    			'params' => $_GET,
    	]);    	
    }
    /**
     * Finds the Disaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Disaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Disaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
