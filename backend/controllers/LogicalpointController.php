<?php

namespace backend\controllers;

use Yii;
use app\models\Logicalpoint;
use backend\models\LogicalpointSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LogicalpointController implements the CRUD actions for Logicalpoint model.
 */
class LogicalpointController extends Controller
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
     * Lists all Logicalpoint models.
     * @return mixed
     */
    public function actionLogicalpointindex()
    {
        $searchModel = new LogicalpointSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('logicalpointindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Logicalpoint model.
     * @param integer $id
     * @return mixed
     */
    public function actionLogicalpointview($id)
    {
        return $this->render('logicalpointview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Logicalpoint model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionLogicalpointcreate()
    {
        $model = new Logicalpoint();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['logicalpointview', 'id' => $model->id]);
        } else {
            return $this->render('logicalpointcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Logicalpoint model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionLogicalpointupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['logicalpointview', 'id' => $model->id]);
        } else {
            return $this->render('logicalpointupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Logicalpoint model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionLogicalpointdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['logicalpointindex']);
    }

    /**
     * Finds the Logicalpoint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Logicalpoint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Logicalpoint::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
