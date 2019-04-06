<?php

namespace frontend\controllers;

use app\models\Plant;
use frontend\helpers\Pinyin;
use Yii;
use app\models\Insurancetype;
use frontend\models\InsurancetypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InsurancetypeController implements the CRUD actions for Insurancetype model.
 */
class InsurancetypeController extends Controller
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
     * Lists all Insurancetype models.
     * @return mixed
     */
    public function actionInsurancetypeindex()
    {
        $searchModel = new InsurancetypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('insurancetypeindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Insurancetype model.
     * @param integer $id
     * @return mixed
     */
    public function actionInsurancetypeview($id)
    {
        return $this->render('insurancetypeview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Insurancetype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionInsurancetypecreate()
    {
        $model = new Insurancetype();

        if ($model->load(Yii::$app->request->post())) {
            $model->pinyin = Pinyin::encode(Plant::find()->where(['id'=>$model->plant_id])->one()['typename']);
            $model->save();
            return $this->redirect(['insurancetypeindex']);
        } else {
            return $this->render('insurancetypecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Insurancetype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInsurancetypeupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->pinyin = Pinyin::encode(Plant::find()->where(['id'=>$model->plant_id])->one()['typename']);
            $model->save();
            return $this->redirect(['insurancetypeindex']);
        } else {
            return $this->render('insurancetypeupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Insurancetype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInsurancetypedelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['insurancetypeindex']);
    }

    /**
     * Finds the Insurancetype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Insurancetype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Insurancetype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
