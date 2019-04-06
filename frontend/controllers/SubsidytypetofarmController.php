<?php

namespace frontend\controllers;

use Yii;
use app\models\Subsidytypetofarm;
use frontend\models\SubsidytypetofarmSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubsidytypetofarmController implements the CRUD actions for Subsidytypetofarm model.
 */
class SubsidytypetofarmController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Subsidytypetofarm models.
     * @return mixed
     */
    public function actionSubsidytypetofarmindex()
    {
        $searchModel = new SubsidytypetofarmSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('subsidytypetofarmindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Subsidytypetofarm model.
     * @param integer $id
     * @return mixed
     */
    public function actionSubsidytypetofarmview($id)
    {
        return $this->render('subsidytypetofarmview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Subsidytypetofarm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSubsidytypetofarmcreate()
    {
        $model = new Subsidytypetofarm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['subsidytypetofarmindex']);
        } else {
            return $this->render('subsidytypetofarmcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Subsidytypetofarm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSubsidytypetofarmupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['subsidytypetofarmindex']);
        } else {
            return $this->render('subsidytypetofarmupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Subsidytypetofarm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSubsidytypetofarmdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['subsidytypetofarmindex']);
    }

    /**
     * Finds the Subsidytypetofarm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Subsidytypetofarm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subsidytypetofarm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
