<?php

namespace frontend\controllers;

use Yii;
use app\models\Otherfarms;
use frontend\models\otherfarmsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OtherfarmsController implements the CRUD actions for Otherfarms model.
 */
class OtherfarmsController extends Controller
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
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        } else {
            return true;
        }
    }
    /**
     * Lists all Otherfarms models.
     * @return mixed
     */
    public function actionOtherfarmsindex()
    {
        $searchModel = new otherfarmsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('otherfarmsindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Otherfarms model.
     * @param integer $id
     * @return mixed
     */
    public function actionOtherfarmsview($id)
    {
        return $this->render('otherfarmsview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Otherfarms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionOtherfarmscreate()
    {
        $model = new Otherfarms();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['otherfarmsview', 'id' => $model->id]);
        } else {
            return $this->render('otherfarmscreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Otherfarms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionOtherfarmsupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['otherfarmsview', 'id' => $model->id]);
        } else {
            return $this->render('otherfarmsupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Otherfarms model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionOtherfarmsdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['otherfarmsindex']);
    }

    /**
     * Finds the Otherfarms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Otherfarms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Otherfarms::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
