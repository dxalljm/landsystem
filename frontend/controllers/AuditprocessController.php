<?php

namespace frontend\controllers;

use Yii;
use app\models\Auditprocess;
use frontend\models\AuditprocessSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuditprocessController implements the CRUD actions for Auditprocess model.
 */
class AuditprocessController extends Controller
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
     * Lists all Auditprocess models.
     * @return mixed
     */
    public function actionAuditprocessindex()
    {
        $searchModel = new AuditprocessSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('auditprocessindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Auditprocess model.
     * @param integer $id
     * @return mixed
     */
    public function actionAuditprocessview($id)
    {
        return $this->render('auditprocessview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Auditprocess model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAuditprocesscreate()
    {
        $model = new Auditprocess();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['auditprocessview', 'id' => $model->id]);
        } else {
            return $this->render('auditprocesscreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Auditprocess model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionAuditprocessupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['auditprocessview', 'id' => $model->id]);
        } else {
            return $this->render('auditprocessupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Auditprocess model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionAuditprocessdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['auditprocessindex']);
    }

    /**
     * Finds the Auditprocess model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Auditprocess the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Auditprocess::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
