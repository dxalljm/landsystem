<?php

namespace frontend\controllers;

use Yii;
use app\models\Plantinputproduct;
use frontend\models\plantinputproductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Plantingstructure;

/**
 * PlantinputproductController implements the CRUD actions for Plantinputproduct model.
 */
class PlantinputproductController extends Controller
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
     * Lists all Plantinputproduct models.
     * @return mixed
     */
    public function actionPlantinputproductindex($farms_id)
    {
        $planting = Plantingstructure::find()->where(['farms_id'=>$farms_id])->all();

        return $this->render('plantinputproductindex', [
            'plantings' => $planting,
        ]);
    }

    /**
     * Displays a single Plantinputproduct model.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantinputproductview($id)
    {
        return $this->renderAjax('plantinputproductview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Plantinputproduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPlantinputproductcreate($planting_id)
    {
        $model = new Plantinputproduct();
		$planting = Plantingstructure::findOne($planting_id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['plantingstructure/plantingstructurecreate', 'lease_id'=>$lease_id,'farms_id' => $farms_id]);
        } else {
            return $this->render('plantinputproductcreate', [
                'model' => $model,
            	'planting' => $planting,
            ]);
        }
    }

    /**
     * Updates an existing Plantinputproduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantinputproductupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['plantinputproductview', 'id' => $model->id]);
        } else {
            return $this->renderAjax('plantinputproductupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Plantinputproduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantinputproductdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['plantinputproductindex']);
    }

    /**
     * Finds the Plantinputproduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plantinputproduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plantinputproduct::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
