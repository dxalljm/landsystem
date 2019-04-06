<?php

namespace frontend\controllers;

use Yii;
use app\models\Cooperative;
use frontend\models\cooperativeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Theyear;
use app\models\Log;
use app\models\Logs;
/**
 * CooperativeController implements the CRUD actions for Cooperative model.
 */
class CooperativeController extends Controller
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
     * Lists all Cooperative models.
     * @return mixed
     */
    public function actionCooperativeindex()
    {
        $searchModel = new cooperativeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		Logs::writeLogs('合作社信息');
        return $this->render('cooperativeindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cooperative model.
     * @param integer $id
     * @return mixed
     */
    public function actionCooperativeview($id)
    {
    	Logs::writeLog('查看合作社信息',$id);
        return $this->render('cooperativeview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Cooperative model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCooperativecreate()
    {
        $model = new Cooperative();

        if ($model->load(Yii::$app->request->post())) {
        	$model->create_at = time();
        	$model->update_at = $model->create_at;
        	$model->save();
        	Logs::writeLogs('创建合作社',$model);
            return $this->redirect(['cooperativeview', 'id' => $model->id]);
        } else {
            return $this->render('cooperativecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Cooperative model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCooperativeupdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->save();
        	Logs::writeLogs('更新合作社信息',$model);
            return $this->redirect(['cooperativeview', 'id' => $model->id]);
        } else {
            return $this->render('cooperativeupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Cooperative model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCooperativedelete($id)
    {
    	$model = $this->findModel($id);
    	Logs::writeLogs('删除合作社信息',$model);
        $model->delete();
		
        return $this->redirect(['cooperativeindex']);
    }

    /**
     * Finds the Cooperative model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cooperative the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cooperative::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
