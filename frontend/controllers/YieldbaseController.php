<?php

namespace frontend\controllers;

use Yii;
use app\models\Yieldbase;
use frontend\models\yieldbaseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Plant;
use app\models\Plantingstructure;
use app\models\User;

/**
 * YieldbaseController implements the CRUD actions for Yieldbase model.
 */
class YieldbaseController extends Controller
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
     * Lists all Yieldbase models.
     * @return mixed
     */
    public function actionYieldbaseindex()
    {
        $searchModel = new yieldbaseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('yieldbaseindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Yieldbase model.
     * @param integer $id
     * @return mixed
     */
    public function actionYieldbaseview($id)
    {
        return $this->render('yieldbaseview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Yieldbase model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionYieldbasecreate()
    {
        
		$plant = Plantingstructure::getPlantname();
// 		var_dump($plant);exit;
        if (Yii::$app->request->post()) {
        	$yields = Yii::$app->request->post('yields');
        	$plantids = Yii::$app->request->post('plantids');
        	for($i=0;$i<count($yields);$i++) {
        		$model = new Yieldbase();
        		$model->plant_id = $plantids[$i];
        		$model->yield = $yields[$i];
        		$model->year = User::getYear();
        		$model->save();
        	}
            return $this->redirect(['yieldbaseindex']);
        } else {
            return $this->render('yieldbasecreate', [
            	'plants' => $plant['id'],
            ]);
        }
    }

    /**
     * Updates an existing Yieldbase model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionYieldbaseupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['yieldbaseview', 'id' => $model->id]);
        } else {
            return $this->render('yieldbaseupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Yieldbase model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionYieldbasedelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['yieldbaseindex']);
    }

    /**
     * Finds the Yieldbase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Yieldbase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Yieldbase::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
