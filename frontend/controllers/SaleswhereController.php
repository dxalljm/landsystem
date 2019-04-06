<?php

namespace frontend\controllers;

use app\models\Logs;
use Yii;
use frontend\models\Saleswhere;
use frontend\models\SaleswhereSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SaleswhereController implements the CRUD actions for Saleswhere model.
 */
class SaleswhereController extends Controller
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
     * Lists all Saleswhere models.
     * @return mixed
     */
    public function actionSaleswhereindex()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $searchModel = new SaleswhereSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Logs::writeLogs('销售去向列表');
        return $this->render('saleswhereindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Saleswhere model.
     * @param integer $id
     * @return mixed
     */
    public function actionSaleswhereview($id)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $model = $this->findModel($id);
        Logs::writeLogs('查看销售去向',$model)
        return $this->render('saleswhereview', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Saleswhere model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSaleswherecreate()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $model = new Saleswhere();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Logs::writeLogs('新增销售去向',$model);
            return $this->redirect(['saleswhereview', 'id' => $model->id]);
        } else {
            return $this->render('saleswherecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Saleswhere model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSaleswhereupdate($id)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Logs::writeLogs('更新销售去向',$model);
            return $this->redirect(['saleswhereview', 'id' => $model->id]);
        } else {
            return $this->render('saleswhereupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Saleswhere model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSaleswheredelete($id)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $model= $this->findModel($id);
        $model->delete();
        Logs::writeLogs('删除销售去向',$model);

        return $this->redirect(['saleswhereindex']);
    }

    /**
     * Finds the Saleswhere model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Saleswhere the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Saleswhere::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
