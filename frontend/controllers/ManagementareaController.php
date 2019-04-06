<?php

namespace frontend\controllers;

use Yii;
use app\models\ManagementArea;
use frontend\models\managementareaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Logs;

/**
 * ManagementareaController implements the CRUD actions for ManagementArea model.
 */
class ManagementareaController extends Controller
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
     * Lists all ManagementArea models.
     * @return mixed
     */
    public function actionManagementareaindex()
    {
        $searchModel = new managementareaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		Logs::writeLog('管理区管理');
        return $this->render('managementareaindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Managementarea model.
     * @param integer $id
     * @return mixed
     */
    public function actionManagementareaview($id)
    {
        $model = $this->findModel($id);
    	Logs::writeLogs('查看管理区信息',$model);
        return $this->render('managementareaview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ManagementArea model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionManagementareacreate()
    {
        $model = new ManagementArea();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	Logs::writeLogs('创建管理区',$model);
            return $this->redirect(['managementareaview', 'id' => $model->id]);
        } else {
            return $this->render('managementareacreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Managementarea model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionManagementareaupdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	Logs::writeLogs('更新管理区信息',$model);
            return $this->redirect(['managementareaview', 'id' => $model->id]);
        } else {
            return $this->render('managementareaupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Managementarea model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionManagementareadelete($id)
    {
    	$model = $this->findModel($id);
    	Logs::writeLogs('删除管理区',$model);
        $model->delete();

        return $this->redirect(['managementareaindex']);
    }

    /**
     * Finds the ManagementArea model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ManagementArea the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ManagementArea::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
