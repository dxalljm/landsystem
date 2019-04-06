<?php

namespace frontend\controllers;

use Yii;
use app\models\Inputproduct;
use frontend\models\inputproductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Logs;
use app\models\Inputproductbrandmodel;

/**
 * InputproductController implements the CRUD actions for Inputproduct model.
 */
class InputproductController extends Controller
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
    /**
     * Lists all Inputproduct models.
     * @return mixed
     */
    public function actionInputproductindex()
    {
        $searchModel = new inputproductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		Logs::writeLog('投入品管理');
        return $this->render('inputproductindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Inputproduct model.
     * @param integer $id
     * @return mixed
     */
    public function actionInputproductview($id)
    {
        $model = $this->findModel($id);
    	Logs::writeLogs('查看投入品信息',$model);
        return $this->render('inputproductview', [
            'model' => $model,
        ]);
    }

    public function  actionInputproductgetfertilizer($father_id)
    {
    	$inputproduct = Inputproduct::find()->where(['father_id'=>$father_id])->all();
    	$newData = NULL;
    	foreach($inputproduct as $key=>$val){
    		$newData[$key] = $val->attributes;
    	}
        if(empty($newData)) {
            $status = 0;
        } else {
            $status = 1;
        }
    	echo json_encode(['status'=>$status,'inputproductson'=>$newData]);
    }
    
    /**
     * Creates a new Inputproduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionInputproductcreate()
    {
        $model = new Inputproduct();
		$brandModel = new Inputproductbrandmodel();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	Logs::writeLogs('创建投入品',$model);
            return $this->redirect(['inputproductview', 'id' => $model->id]);
        } else {
            return $this->render('inputproductcreate', [
                'model' => $model,
            	'brandModel' => $brandModel,
            ]);
        }
    }

    /**
     * Updates an existing Inputproduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInputproductupdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	Logs::writeLogs('更新调入品信息',$model);
            return $this->redirect(['inputproductview', 'id' => $model->id]);
        } else {
            return $this->render('inputproductupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Inputproduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInputproductdelete($id)
    {
    	$model = $this->findModel($id);
    	Logs::writeLogs('删除投入品',$model);
        $model->delete();

        return $this->redirect(['inputproductindex']);
    }

    /**
     * Finds the Inputproduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Inputproduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Inputproduct::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionGetnextfather($father_id)
    {
    	$location = Inputproduct::findOne($father_id);
    	echo Json::encode($location);
    }
}
