<?php

namespace frontend\controllers;

use Yii;
use app\models\Insurancedck;
use frontend\models\insurancedckSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InsurancedckController implements the CRUD actions for Insurancedck model.
 */
class InsurancedckController extends Controller
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
     * Lists all Insurancedck models.
     * @return mixed
     */
    public function actionInsurancedckindex()
    {
        $searchModel = new insurancedckSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('insurancedckindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Insurancedck model.
     * @param integer $id
     * @return mixed
     */
    public function actionInsurancedckview($id)
    {
        return $this->render('insurancedckview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Insurancedck model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionInsurancedckcreate()
    {
        $model = new Insurancedck();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['insurancedckview', 'id' => $model->id]);
        } else {
            return $this->render('insurancedckcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Insurancedck model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInsurancedckupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['insurancedckview', 'id' => $model->id]);
        } else {
            return $this->render('insurancedckupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Insurancedck model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInsurancedckdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['insurancedckindex']);
    }

    /**
     * Finds the Insurancedck model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Insurancedck the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Insurancedck::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
