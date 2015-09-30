<?php

namespace frontend\controllers;

use Yii;
use app\models\Tempprintbill;
use frontend\models\tempprintbillSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TempprintbillController implements the CRUD actions for Tempprintbill model.
 */
class TempprintbillController extends Controller
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
     * Lists all Tempprintbill models.
     * @return mixed
     */
    public function actionTempprintbillindex()
    {
        $searchModel = new tempprintbillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('tempprintbillindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tempprintbill model.
     * @param integer $id
     * @return mixed
     */
    public function actionTempprintbillview($id)
    {
        return $this->render('tempprintbillview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tempprintbill model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionTempprintbillcreate()
    {
        $model = new Tempprintbill();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['tempprintbillview', 'id' => $model->id]);
        } else {
            return $this->render('tempprintbillcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Tempprintbill model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTempprintbillupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['tempprintbillview', 'id' => $model->id]);
        } else {
            return $this->render('tempprintbillupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Tempprintbill model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTempprintbilldelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['tempprintbillindex']);
    }

    /**
     * Finds the Tempprintbill model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tempprintbill the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tempprintbill::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
