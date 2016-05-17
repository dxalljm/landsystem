<?php

namespace frontend\controllers;

use Yii;
use app\models\Insurance;
use frontend\models\insuranceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InsuranceController implements the CRUD actions for Insurance model.
 */
class InsuranceController extends Controller
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
     * Lists all Insurance models.
     * @return mixed
     */
    public function actionInsuranceindex($farms_id)
    {
        $searchModel = new insuranceSearch();
        $params = Yii::$app->request->queryParams;
        $params['insuranceSearch']['farms_id'] = $farms_id;
        $dataProvider = $searchModel->search($params);

        return $this->render('insuranceindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'farms_id' => $farms_id,
        ]);
    }
	//农业保险审核
    public function actionInsurancereview($farms_id)
    {
    	$searchModel = new insuranceSearch();
    	$params = Yii::$app->request->queryParams;
    	$params['insuranceSearch']['farms_id'] = $farms_id;
    	$dataProvider = $searchModel->search($params);
    
    	return $this->render('insuranceindex', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'farms_id' => $farms_id,
    	]);
    }
    
    /**
     * Displays a single Insurance model.
     * @param integer $id
     * @return mixed
     */
    public function actionInsuranceview($id)
    {
        return $this->render('insuranceview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Insurance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionInsurancecreate($farms_id)
    {
        $model = new Insurance();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['insuranceview', 'id' => $model->id]);
        } else {
            return $this->render('insurancecreate', [
                'model' => $model,
            	'farms_id' => $farms_id,
            ]);
        }
    }

    /**
     * Updates an existing Insurance model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInsuranceupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['insuranceview', 'id' => $model->id]);
        } else {
            return $this->render('insuranceupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Insurance model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInsurancedelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['insuranceindex']);
    }

    /**
     * Finds the Insurance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Insurance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Insurance::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
