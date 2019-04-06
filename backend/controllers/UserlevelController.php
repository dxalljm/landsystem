<?php

namespace backend\controllers;

use Yii;
use app\models\Userlevel;
use backend\models\userlevelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserlevelController implements the CRUD actions for Userlevel model.
 */
class UserlevelController extends Controller
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
     * Lists all Userlevel models.
     * @return mixed
     */
    public function actionUserlevelindex()
    {
        $searchModel = new userlevelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('userlevelindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Userlevel model.
     * @param integer $id
     * @return mixed
     */
    public function actionUserlevelview($id)
    {
        return $this->render('userlevelview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Userlevel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUserlevelcreate()
    {
        $model = new Userlevel();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['userlevelview', 'id' => $model->id]);
        } else {
            return $this->render('userlevelcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Userlevel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUserlevelupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['userlevelview', 'id' => $model->id]);
        } else {
            return $this->render('userlevelupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Userlevel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUserleveldelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['userlevelindex']);
    }

    /**
     * Finds the Userlevel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Userlevel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Userlevel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
