<?php

namespace frontend\controllers;

use Yii;
use app\models\Plantinputproduct;
use frontend\models\plantinputproductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Plantingstructure;
use app\models\Logs;
use app\models\ManagementArea;
use app\models\Farms;

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
    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        } else {
            return true;
        }
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
	public function actionSetmanagement()
	{
		$input = Plantinputproduct::find()->all();
		foreach ($input as $value) {
			$model = $this->findModel($value['id']);
			$model->management_area = Farms::find()->where(['id'=>$value['farms_id']])->one()['management_area'];
			$model->save();
		}
		echo 'done';
	}
    /**
     * Lists all Plantinputproduct models.
     * @return mixed
     */
    public function actionPlantinputproductindex($farms_id)
    {
        $planting = Plantingstructure::find()->where(['farms_id'=>$farms_id])->all();
		Logs::writeLogs('投入品使用情况');
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
        $model = $this->findModel($id);
    	Logs::writeLogs('查看投入品使用情况',$model);
        return $this->renderAjax('plantinputproductview', [
            'model' => $model,
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
        $parmembers = Yii::$app->request->post('PlantInputproductPost');
		$planting = Plantingstructure::findOne($planting_id);
		//$plantinputproducts = Plantinputproduct::find()->where(['farms_id'=>$planting->farms_id,'lessee_id'=>$planting->lease_id,'plant_id'=>$planting->plant_id,'zongdi'=>$planting->zongdi])->all();
		
        if ($parmembers) {
        	//var_dump($parmembers);
        	for($i=0;$i<count($parmembers['id']);$i++) {
        		$model->farms_id = $parmembers['farms_id'][$i];
        		$model->lessee_id = $parmembers['lessee_id'][$i];
        		$model->zongdi = $parmembers['zongdi'][$i];
        		$model->plant_id = $parmembers['plant_id'][$i];
        		$model->father_id = $parmembers['father_id'][$i];
        		$model->son_id = $parmembers['son_id'][$i];
        		$model->inputproduct_id = $parmembers['inputproduct_id'][$i];
        		$model->pconsumption = $parmembers['pconsumption'][$i];
        		$model->create_at = time();
        		$model->update_at = time();
        		$model->save();
        		Logs::writeLogs('添加投入品',$model);
        	}
            return $this->redirect(['plantinputproductindex', 'farms_id' => $planting->farms_id]);
        } else {
            return $this->render('plantinputproductcreate', [
                'model' => $model,
            	'planting' => $planting,
            	//'plantinputproducts' => $plantinputproducts,
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
        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->save();
        	Logs::writeLogs('更新投入品使用情况',$model);
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
    	$model = $this->findModel($id);
    	Logs::writeLogs('删除投入品使用情况',$model);
        $model->delete();

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
