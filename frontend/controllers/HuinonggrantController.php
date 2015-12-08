<?php

namespace frontend\controllers;

use Yii;
use app\models\Huinonggrant;
use frontend\models\HuinonggrantSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HuinonggrantController implements the CRUD actions for Huinonggrant model.
 */
class HuinonggrantController extends Controller
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
     * Lists all Huinonggrant models.
     * @return mixed
     */
    public function actionHuinonggrantindex()
    {
        $searchModel = new HuinonggrantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('huinonggrantindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Huinonggrant model.
     * @param integer $id
     * @return mixed
     */
    public function actionHuinonggrantview($id)
    {
        return $this->render('huinonggrantview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Huinonggrant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionHuinonggrantcreate()
    {
        $model = new Huinonggrant();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['huinonggrantview', 'id' => $model->id]);
        } else {
            return $this->render('huinonggrantcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Huinonggrant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionHuinonggrantupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['huinonggrantview', 'id' => $model->id]);
        } else {
            return $this->render('huinonggrantupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Huinonggrant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionHuinonggrantdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['huinonggrantindex']);
    }

    /**
     * Finds the Huinonggrant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Huinonggrant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Huinonggrant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
