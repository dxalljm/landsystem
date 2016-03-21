<?php

namespace frontend\controllers;

use Yii;
use app\models\Yields;
use frontend\models\yieldsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Plantingstructure;
use app\models\Farms;
use app\models\Theyear;
use frontend\models\plantingstructureSearch;
/**
 * YieldsController implements the CRUD actions for Yields model.
 */
class YieldsController extends Controller
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

    public function actionSetmanagementarea()
    {
    	$data=Yields::find()->all();
    	foreach ($data as $value) {
    		$model = $this->findModel($value['id']);
    		$model->management_area = Farms::getFarmsAreaID($value['farms_id']);
    		$model->save();
    	}
    	return 'finished';
    }
    /**
     * Lists all Yields models.
     * @return mixed
     */
    public function actionYieldsindex($farms_id)
    {
        $planting = Plantingstructure::find()->where(['farms_id'=>$farms_id])->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->all();
		
        return $this->render('yieldsindex', [
            'plantings' => $planting,
        ]);
    }

    public function actionYieldsinfo()
    {
    	$searchModel = new plantingstructureSearch();
    	$params = Yii::$app->request->queryParams;
    	$whereArray = Farms::getManagementArea()['id'];
    	if (empty($params['plantingstructureSearch']['management_area'])) {
    		$params ['plantingstructureSearch'] ['management_area'] = $whereArray;
    	}
    	$dataProvider = $searchModel->search ( $params );
    	if (is_array($searchModel->management_area)) {
			$searchModel->management_area = null;
		}
    	 
    	
    	return $this->render('yieldsinfo',[
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'params' => $params,
    	]);
    }
    
    /**
     * Displays a single Yields model.
     * @param integer $id
     * @return mixed
     */
    public function actionYieldsview($id)
    {
        return $this->render('yieldsview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Yields model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionYieldscreate($planting_id,$farms_id)
    {
    	$id = Yields::find()->where(['planting_id'=>$planting_id])->one()['id'];
    	if($id)    
    		$model = $this->findModel($id);
    	else 
        	$model = new Yields();
        $planting = Plantingstructure::find()->where(['farms_id'=>$farms_id])->all();
        if ($model->load(Yii::$app->request->post())) {
        	$model->management_area = Farms::getFarmsAreaID($farms_id);
        	$model->create_at = time();
        	$model->update_at = $model->create_at;
        	$model->plant_id = Plantingstructure::find()->where(['id'=>$planting_id])->one()['plant_id'];
        	$model->save();
            return $this->redirect(['yieldsindex', 'farms_id' => $farms_id,'plantings'=>$planting]);
        } else {
            return $this->render('yieldscreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Yields model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionYieldsupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->save();
            return $this->redirect(['yieldsview', 'id' => $model->id]);
        } else {
            return $this->render('yieldsupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Yields model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionYieldsdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['yieldsindex']);
    }
    
    public function actionYieldssearch($tab,$begindate,$enddate)
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
    	$searchModel = new plantingstructureSearch();
		if(!is_numeric($_GET['begindate']))
			 $_GET['begindate'] = strtotime($_GET['begindate']);
		if(!is_numeric($_GET['enddate']))
			 $_GET['enddate'] = strtotime($_GET['enddate']);

    	$dataProvider = $searchModel->searchIndex ( $_GET );
    	return $this->render('yieldssearch',[
	    			'searchModel' => $searchModel,
	    			'dataProvider' => $dataProvider,
	    			'tab' => $_GET['tab'],
	    			'begindate' => $_GET['begindate'],
	    			'enddate' => $_GET['enddate'],
	    			'params' => $_GET,
    	]);    	
    }

    /**
     * Finds the Yields model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Yields the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Yields::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
