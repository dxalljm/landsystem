<?php

namespace frontend\controllers;

use Yii;
use app\models\Inputproduct;
use frontend\models\inputproductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InputproductController implements the CRUD actions for Inputproduct model.
 */
class InputproductController extends Controller
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
     * Lists all Inputproduct models.
     * @return mixed
     */
    public function actionInputproductindex()
    {
        $searchModel = new inputproductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('inputproductindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Inputproduct model.
     * @param integer $id
     * @return mixed
     */
    public function actionInputproductview($id)
    {
        return $this->render('inputproductview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Inputproduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionInputproductcreate()
    {
        $model = new Inputproduct();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['inputproductview', 'id' => $model->id]);
        } else {
            return $this->render('inputproductcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Inputproduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInputproductupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['inputproductview', 'id' => $model->id]);
        } else {
            return $this->render('inputproductupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Inputproduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInputproductdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['inputproductindex']);
    }

    /**
     * Finds the Inputproduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Inputproduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Inputproduct::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionGetnextfather($father_id)
    {
    	$location = Inputproduct::findOne($father_id);
    	echo Json::encode($location);
    }
}
