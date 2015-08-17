<?php

namespace frontend\controllers;

use Yii;
use app\models\Fireprevention;
use frontend\models\firepreventionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FirepreventionController implements the CRUD actions for Fireprevention model.
 */
class FirepreventionController extends Controller
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
     * Lists all Fireprevention models.
     * @return mixed
     */
    public function actionFirepreventionindex()
    {
        $searchModel = new firepreventionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('firepreventionindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Fireprevention model.
     * @param integer $id
     * @return mixed
     */
    public function actionFirepreventionview($id)
    {
        return $this->render('firepreventionview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Fireprevention model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionFirepreventioncreate()
    {
        $model = new Fireprevention();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['firepreventionview', 'id' => $model->id]);
        } else {
            return $this->render('firepreventioncreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Fireprevention model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFirepreventionupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['firepreventionview', 'id' => $model->id]);
        } else {
            return $this->render('firepreventionupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Fireprevention model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFirepreventiondelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['firepreventionindex']);
    }

    /**
     * Finds the Fireprevention model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Fireprevention the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fireprevention::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
