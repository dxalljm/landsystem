<?php

namespace frontend\controllers;

use Yii;
use app\models\Cooperative;
use frontend\models\cooperativeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Theyear;
/**
 * CooperativeController implements the CRUD actions for Cooperative model.
 */
class CooperativeController extends Controller
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
     * Lists all Cooperative models.
     * @return mixed
     */
    public function actionCooperativeindex()
    {
        $searchModel = new cooperativeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('cooperativeindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cooperative model.
     * @param integer $id
     * @return mixed
     */
    public function actionCooperativeview($id)
    {
        return $this->render('cooperativeview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Cooperative model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCooperativecreate()
    {
        $model = new Cooperative();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['cooperativeview', 'id' => $model->id]);
        } else {
            return $this->render('cooperativecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Cooperative model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCooperativeupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['cooperativeview', 'id' => $model->id]);
        } else {
            return $this->render('cooperativeupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Cooperative model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCooperativedelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['cooperativeindex']);
    }

    /**
     * Finds the Cooperative model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cooperative the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cooperative::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
