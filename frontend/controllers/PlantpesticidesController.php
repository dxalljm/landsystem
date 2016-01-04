<?php

namespace frontend\controllers;

use Yii;
use app\models\Plantpesticides;
use frontend\models\plantpesticidesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Logs;
use app\models\Plantingstructure;
/**
 * PlantpesticidesController implements the CRUD actions for Plantpesticides model.
 */
class PlantpesticidesController extends Controller
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

//     public function beforeAction($action)
//     {
//     	$action = Yii::$app->controller->action->id;
//     	if(\Yii::$app->user->can($action)){
//     		return true;
//     	}else{
//     		throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
//     	}
//     }
    /**
     * Lists all Plantpesticides models.
     * @return mixed
     */
    public function actionPlantpesticidesindex($farms_id)
    {
        $planting = Plantingstructure::find()->where(['farms_id'=>$farms_id])->all();
		Logs::writeLog('农药使用情况');
        return $this->render('plantpesticidesindex', [
            'plantings' => $planting,
        ]);
    }

    /**
     * Displays a single Plantpesticides model.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantpesticidesview($id)
    {
    	Logs::writeLog('查看农药使用情况',$id);
        return $this->render('plantpesticidesview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Plantpesticides model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPlantpesticidescreate($lease_id,$plant_id,$farms_id)
    {
        $model = new Plantpesticides();
		
        if ($model->load(Yii::$app->request->post())) {
        	$model->create_at = time();
        	$model->update_at = time();
        	$model->save();
        	$new = $model->attributes;
        	Logs::writeLog('添加农药情况情况',$model->id,'',$new);
            return $this->redirect(['plantpesticidesindex', 'farms_id'=>$farms_id]);
        } else {
            return $this->render('plantpesticidescreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Plantpesticides model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantpesticidesupdate($id,$farms_id)
    {
        $model = $this->findModel($id);
		$old = $model->attributes;
        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->save();
        	$new = $model->attributes;
        	Logs::writeLog('更新农药使用情况',$id,$old,$new);
            return $this->redirect(['plantpesticidesindex', 'farms_id'=>$farms_id]);
        } else {
            return $this->render('plantpesticidesupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Plantpesticides model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantpesticidesdelete($id,$farms_id)
    {
        $model = $this->findModel($id);
    	$old = $model->attributes;
    	Logs::writeLog('删除农药使用情况',$id,$old);
        $model->delete();

        return $this->redirect(['plantpesticidesindex','farms_id'=>$farms_id]);
    }

    /**
     * Finds the Plantpesticides model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plantpesticides the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plantpesticides::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
