<?php

namespace frontend\controllers;

use Yii;
use app\models\Estate;
use frontend\models\estateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EstateController implements the CRUD actions for Estate model.
 */
class EstateController extends Controller
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
     * Lists all Estate models.
     * @return mixed
     */
    public function actionEstateindex()
    {
        $searchModel = new estateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('estateindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Estate model.
     * @param integer $id
     * @return mixed
     */
    public function actionEstateview($id)
    {
        return $this->render('estateview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Estate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionEstatecreate()
    {
        $model = new Estate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['estateview', 'id' => $model->id]);
        } else {
            return $this->render('estatecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Estate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEstateupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['estateview', 'id' => $model->id]);
        } else {
            return $this->render('estateupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Estate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEstatedelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['estateindex']);
    }

    /**
     * Finds the Estate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Estate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Estate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
