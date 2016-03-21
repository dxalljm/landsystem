<?php

namespace frontend\controllers;

use Yii;
use app\models\Huinonggrant;
use frontend\models\huinonggrantSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use app\models\Theyear;
/**
 * HuinonggrantController implements the CRUD actions for Huinonggrant model.
 */
class HuinonggrantController extends Controller
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

    /**
     * Lists all Huinonggrant models.
     * @return mixed
     */
    public function actionHuinonggrantindex()
    {
        $searchModel = new huinonggrantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('huinonggrantindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Huinonggrant model.
     * @param integer $id
     * @return mixed
     */
    public function actionHuinonggrantview($id)
    {
        return $this->render('huinonggrantview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Huinonggrant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionHuinonggrantcreate()
    {
        $model = new Huinonggrant();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['huinonggrantview', 'id' => $model->id]);
        } else {
            return $this->render('huinonggrantcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Huinonggrant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionHuinonggrantupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['huinonggrantview', 'id' => $model->id]);
        } else {
            return $this->render('huinonggrantupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Huinonggrant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionHuinonggrantdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['huinonggrantindex']);
    }

    public function actionHuinonggrantinfo()
    {
    	$searchModel = new huinonggrantSearch();
    	$params = Yii::$app->request->queryParams;
    	$whereArray = Farms::getManagementArea()['id'];
    	if (empty($params['huinonggrantSearch']['management_area'])) {
    		$params ['huinonggrantSearch'] ['management_area'] = $whereArray;
    	}
    	$params['begindate'] = Theyear::getYeartime()[0];
    	$params['enddate'] = Theyear::getYeartime()[1];
    	$params['state'] = 1;
    	// 		var_dump($params);
    	$dataProvider = $searchModel->searchIndex ( $params );
    	if (is_array($searchModel->management_area)) {
    		$searchModel->management_area = null;
    	}
    	 
    
    	return $this->render('huinonggrantinfo',[
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'params' => $params,
    	]);
    }
    
    
    
    public function actionHuinonggrantsearch($tab,$begindate,$enddate)
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
    	$searchModel = new huinonggrantSearch();
    	if(!is_numeric($_GET['begindate']))
    		$_GET['begindate'] = strtotime($_GET['begindate']);
    	if(!is_numeric($_GET['enddate']))
    		$_GET['enddate'] = strtotime($_GET['enddate']);
    	$_GET['state'] = 1;
    	$dataProvider = $searchModel->searchIndex ( $_GET );
    	return $this->render('huinonggrantsearch',[
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'tab' => $_GET['tab'],
    			'begindate' => $_GET['begindate'],
    			'enddate' => $_GET['enddate'],
    			'params' => $_GET,
    	]);
    }
    
    /**
     * Finds the Huinonggrant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Huinonggrant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Huinonggrant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
