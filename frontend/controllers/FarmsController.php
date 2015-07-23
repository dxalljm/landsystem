<?php

namespace frontend\controllers;

use Yii;
use app\models\farms;
use frontend\models\farmsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Theyear;
/**
 * FarmsController implements the CRUD actions for farms model.
 */
class FarmsController extends Controller
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
    	$action = Yii::$app->controller->action->id;
    	if(\Yii::$app->user->can($action)){
    		return true;
    	}else{
    		throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
    	}
    }
    /**
     * Lists all farms models.
     * @return mixed
     */
    public function actionFarmsindex()
    {
        $searchModel = new farmsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('farmsindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionFarmsbusiness()
    {
    	$searchModel = new farmsSearch();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
    	return $this->render('farmsbusiness', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    }

    public function actionFarmsmenu($id,$areaid)
    {
    	$farm = $this->findModel($id);
    	return $this->render('farmsmenu',[
    		'farm' => $farm,
    		'year' => Theyear::findOne(1),
    		'areaid' => $areaid,
    	]);
    }
    
    /**
     * Displays a single Farms model.
     * @param integer $id
     * @return mixed
     */
    public function actionFarmsview($id)
    {
        return $this->render('farmsview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new farms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionFarmscreate()
    {
        $model = new farms();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['farmsview', 'id' => $model->id]);
        } else {
            return $this->render('farmscreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Farms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFarmsupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['farmsview', 'id' => $model->id]);
        } else {
            return $this->render('farmsupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Farms model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFarmsdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['farmsindex']);
    }

    /**
     * Finds the farms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return farms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = farms::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
