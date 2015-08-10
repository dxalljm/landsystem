<?php

namespace frontend\controllers;

use Yii;
use app\models\CooperativeOfFarm;
use frontend\models\cooperativeoffarmSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CooperativeoffarmController implements the CRUD actions for CooperativeOfFarm model.
 */
class CooperativeoffarmController extends Controller
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
     * Lists all CooperativeOfFarm models.
     * @return mixed
     */
    public function actionCooperativeoffarmindex()
    {
        $searchModel = new cooperativeoffarmSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('cooperativeoffarmindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cooperativeoffarm model.
     * @param integer $id
     * @return mixed
     */
    public function actionCooperativeoffarmview($id)
    {
        return $this->render('cooperativeoffarmview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CooperativeOfFarm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCooperativeoffarmcreate($farms_id)
    {
        $model = new CooperativeOfFarm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['cooperativeoffarmview', 'id' => $model->id,'farms_id'=>$farms_id]);
        } else {
            return $this->render('cooperativeoffarmcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Cooperativeoffarm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCooperativeoffarmupdate($id,$farms_id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['cooperativeoffarmview', 'id' => $model->id,'farms_id'=>$farms_id]);
        } else {
            return $this->render('cooperativeoffarmupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Cooperativeoffarm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCooperativeoffarmdelete($id,$farms_id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['cooperativeoffarmindex','farms_id'=>$farms_id]);
    }

    /**
     * Finds the CooperativeOfFarm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CooperativeOfFarm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CooperativeOfFarm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
