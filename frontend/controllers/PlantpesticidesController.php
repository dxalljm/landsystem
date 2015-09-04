<?php

namespace frontend\controllers;

use Yii;
use app\models\Plantpesticides;
use frontend\models\plantpesticidesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PlantpesticidesController implements the CRUD actions for Plantpesticides model.
 */
class PlantpesticidesController extends Controller
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
     * Lists all Plantpesticides models.
     * @return mixed
     */
    public function actionPlantpesticidesindex()
    {
        $searchModel = new plantpesticidesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('plantpesticidesindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Plantpesticides model.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantpesticidesview($id)
    {
        return $this->render('plantpesticidesview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Plantpesticides model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPlantpesticidescreate()
    {
        $model = new Plantpesticides();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['plantpesticidesview', 'id' => $model->id]);
        } else {
            return $this->render('plantpesticidescreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Plantpesticides model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantpesticidesupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['plantpesticidesview', 'id' => $model->id]);
        } else {
            return $this->render('plantpesticidesupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Plantpesticides model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantpesticidesdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['plantpesticidesindex']);
    }

    /**
     * Finds the Plantpesticides model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plantpesticides the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plantpesticides::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
