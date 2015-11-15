<?php

namespace frontend\controllers;

use Yii;
use app\models\Disastertype;
use frontend\models\disastertypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DisastertypeController implements the CRUD actions for Disastertype model.
 */
class DisastertypeController extends Controller
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
     * Lists all Disastertype models.
     * @return mixed
     */
    public function actionDisastertypeindex()
    {
        $searchModel = new disastertypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('disastertypeindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Disastertype model.
     * @param integer $id
     * @return mixed
     */
    public function actionDisastertypeview($id)
    {
        return $this->render('disastertypeview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Disastertype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDisastertypecreate()
    {
        $model = new Disastertype();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['disastertypeview', 'id' => $model->id]);
        } else {
            return $this->render('disastertypecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Disastertype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDisastertypeupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['disastertypeview', 'id' => $model->id]);
        } else {
            return $this->render('disastertypeupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Disastertype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDisastertypedelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['disastertypeindex']);
    }

    /**
     * Finds the Disastertype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Disastertype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Disastertype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
