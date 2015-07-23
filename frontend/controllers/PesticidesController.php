<?php

namespace frontend\controllers;

use Yii;
use app\models\Pesticides;
use frontend\models\pesticidesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PesticidesController implements the CRUD actions for Pesticides model.
 */
class PesticidesController extends Controller
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
     * Lists all Pesticides models.
     * @return mixed
     */
    public function actionPesticidesindex()
    {
        $searchModel = new pesticidesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('pesticidesindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pesticides model.
     * @param integer $id
     * @return mixed
     */
    public function actionPesticidesview($id)
    {
        return $this->render('pesticidesview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Pesticides model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPesticidescreate()
    {
        $model = new Pesticides();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['pesticidesview', 'id' => $model->id]);
        } else {
            return $this->render('pesticidescreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Pesticides model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPesticidesupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['pesticidesview', 'id' => $model->id]);
        } else {
            return $this->render('pesticidesupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Pesticides model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPesticidesdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['pesticidesindex']);
    }

    /**
     * Finds the Pesticides model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pesticides the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pesticides::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
