<?php

namespace frontend\controllers;

use Yii;
use app\models\Plantingstructure;
use frontend\models\plantingstructureSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use app\models\Parcel;
use app\models\Lease;

/**
 * PlantingstructureController implements the CRUD actions for Plantingstructure model.
 */
class PlantingstructureController extends Controller
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
     * Lists all Plantingstructure models.
     * @return mixed
     */
    public function actionPlantingstructureindex($farms_id)
    {
        $searchModel = new leaseSearch();
         $dataProvider = $searchModel->search(['farms_id'=>$farms_id]);
		//$this->getView()->registerJsFile($url)
        return $this->render('Plantingstructureindex', [
             'searchModel' => $searchModel,
             'dataProvider' => $dataProvider,
        	 'areas' => Lease::getNoArea($farms_id),
        ]);
    }

    /**
     * Displays a single Plantingstructure model.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantingstructureview($id)
    {
        return $this->render('plantingstructureview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Plantingstructure model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPlantingstructurecreate($farms_id)
    {
        $model = new Plantingstructure();
		$farm = Farms::find()->where(['id'=>$farms_id])->one();
		$zongdiarr = explode('、', $farm->zongdi);
		foreach($zongdiarr as $value) {
			$zongdi[] = Parcel::find()->where(['unifiedserialnumber'=>$value])->one();
		}
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['plantingstructureview', 'id' => $model->id]);
        } else {
            return $this->render('plantingstructurecreate', [
                'model' => $model,
            	'farm' => $farm,
            	'zongdi' => $zongdi,
            ]);
        }
    }

    /**
     * Updates an existing Plantingstructure model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantingstructureupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['plantingstructureview', 'id' => $model->id]);
        } else {
            return $this->render('plantingstructureupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Plantingstructure model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantingstructuredelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['plantingstructureindex']);
    }

    /**
     * Finds the Plantingstructure model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plantingstructure the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plantingstructure::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
