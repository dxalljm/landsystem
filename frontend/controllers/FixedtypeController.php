<?php

namespace frontend\controllers;

use Yii;
use app\models\Fixedtype;
use frontend\models\FixedtypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FixedtypeController implements the CRUD actions for Fixedtype model.
 */
class FixedtypeController extends Controller
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
     * Lists all Fixedtype models.
     * @return mixed
     */
    public function actionFixedtypeindex()
    {
        $searchModel = new FixedtypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('fixedtypeindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Fixedtype model.
     * @param integer $id
     * @return mixed
     */
    public function actionFixedtypeview($id)
    {
        return $this->render('fixedtypeview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Fixedtype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionFixedtypecreate()
    {
        $model = new Fixedtype();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['fixedtypeindex']);
        } else {
            return $this->render('fixedtypecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Fixedtype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFixedtypeupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['fixedtypeindex']);
        } else {
            return $this->render('fixedtypeupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Fixedtype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFixedtypedelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['fixedtypeindex']);
    }

    /**
     * Finds the Fixedtype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Fixedtype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fixedtype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
