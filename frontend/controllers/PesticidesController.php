<?php

namespace frontend\controllers;

use Yii;
use app\models\Pesticides;
use frontend\models\pesticidesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Logs;

/**
 * PesticidesController implements the CRUD actions for Pesticides model.
 */
class PesticidesController extends Controller
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
     * Lists all Pesticides models.
     * @return mixed
     */
    public function actionPesticidesindex()
    {
        $searchModel = new pesticidesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		Logs::writeLog('农药管理');
        return $this->render('pesticidesindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pesticides model.
     * @param integer $id
     * @return mixed
     */
    public function actionPesticidesview($id)
    {
    	Logs::writeLog('查看农药信息',$id);
        return $this->render('pesticidesview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Pesticides model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPesticidescreate()
    {
        $model = new Pesticides();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$new = $model->attributes;
        	Logs::writeLog('添加农药',$model->id,'',$new);
            return $this->redirect(['pesticidesview', 'id' => $model->id]);
        } else {
            return $this->render('pesticidescreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Pesticides model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPesticidesupdate($id)
    {
        $model = $this->findModel($id);
		$old = $model->attributes;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$new = $model->attributes;
        	Logs::writeLog('更新农药信息',$id,$old,$new);
            return $this->redirect(['pesticidesview', 'id' => $model->id]);
        } else {
            return $this->render('pesticidesupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Pesticides model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPesticidesdelete($id)
    {
        $model = $this->findModel($id);
    	$old = $model->attributes;
    	Logs::writeLog('删除农药',$id,$old);
        $model->delete();

        return $this->redirect(['pesticidesindex']);
    }

    /**
     * Finds the Pesticides model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pesticides the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pesticides::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
