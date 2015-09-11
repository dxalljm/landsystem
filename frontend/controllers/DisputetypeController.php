<?php

namespace frontend\controllers;

use Yii;
use app\models\Disputetype;
use frontend\models\disputetypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DisputetypeController implements the CRUD actions for Disputetype model.
 */
class DisputetypeController extends Controller
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
     * Lists all Disputetype models.
     * @return mixed
     */
    public function actionDisputetypeindex()
    {
        $searchModel = new disputetypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('disputetypeindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Disputetype model.
     * @param integer $id
     * @return mixed
     */
    public function actionDisputetypeview($id)
    {
        return $this->render('disputetypeview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Disputetype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDisputetypecreate()
    {
        $model = new Disputetype();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['disputetypeview', 'id' => $model->id]);
        } else {
            return $this->render('disputetypecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Disputetype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDisputetypeupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['disputetypeview', 'id' => $model->id]);
        } else {
            return $this->render('disputetypeupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Disputetype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDisputetypedelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['disputetypeindex']);
    }

    /**
     * Finds the Disputetype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Disputetype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Disputetype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}