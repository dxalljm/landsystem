<?php

namespace frontend\controllers;

use Yii;
use app\models\Subsidyratio;
use frontend\models\SubsidyratioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubsidyratioController implements the CRUD actions for Subsidyratio model.
 */
class SubsidyratioController extends Controller
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
     * Lists all Subsidyratio models.
     * @return mixed
     */
    public function actionSubsidyratioindex()
    {
        $searchModel = new SubsidyratioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('subsidyratioindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Subsidyratio model.
     * @param integer $id
     * @return mixed
     */
    public function actionSubsidyratioview($id)
    {
        return $this->render('subsidyratioview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Subsidyratio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSubsidyratiocreate()
    {
        $model = new Subsidyratio();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('subsidyratiocreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Subsidyratio model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSubsidyratioupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['subsidyratioview', 'id' => $model->id]);
        } else {
            return $this->render('subsidyratioupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Subsidyratio model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSubsidyratiodelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['subsidyratioindex']);
    }

    /**
     * Finds the Subsidyratio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Subsidyratio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subsidyratio::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
