<?php

namespace frontend\controllers;

use Yii;
use app\models\Yields;
use frontend\models\yieldsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Plantingstructure;
/**
 * YieldsController implements the CRUD actions for Yields model.
 */
class YieldsController extends Controller
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
    	$action = Yii::$app->controller->action->id;
    	if(\Yii::$app->user->can($action)){
    		return true;
    	}else{
    		throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
    	}
    }
    /**
     * Lists all Yields models.
     * @return mixed
     */
    public function actionYieldsindex($farms_id)
    {
        $planting = Plantingstructure::find()->where(['farms_id'=>$farms_id])->all();
		
        return $this->render('yieldsindex', [
            'plantings' => $planting,
        ]);
    }

    /**
     * Displays a single Yields model.
     * @param integer $id
     * @return mixed
     */
    public function actionYieldsview($id)
    {
        return $this->render('yieldsview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Yields model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionYieldscreate($planting_id,$farms_id)
    {
    	$id = Yields::find()->where(['planting_id'=>$planting_id])->one()['id'];
    	if($id)    
    		$model = $this->findModel($id);
    	else 
        	$model = new Yields();
        $planting = Plantingstructure::find()->where(['farms_id'=>$farms_id])->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	
            return $this->redirect(['yieldsindex', 'farms_id' => $farms_id,'plantings'=>$planting]);
        } else {
            return $this->render('yieldscreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Yields model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionYieldsupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['yieldsview', 'id' => $model->id]);
        } else {
            return $this->render('yieldsupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Yields model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionYieldsdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['yieldsindex']);
    }

    /**
     * Finds the Yields model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Yields the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Yields::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
