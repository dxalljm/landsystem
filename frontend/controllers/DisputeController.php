<?php

namespace frontend\controllers;

use Yii;
use app\models\Dispute;
use frontend\models\disputeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Logs;

/**
 * DisputeController implements the CRUD actions for Dispute model.
 */
class DisputeController extends Controller
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
     * Lists all Dispute models.
     * @return mixed
     */
    public function actionDisputeindex($farms_id)
    {
        $searchModel = new disputeSearch();
        $params = Yii::$app->request->queryParams;
        $params ['disputeSearch'] ['farms_id'] = $farms_id;
        $dataProvider = $searchModel->search($params);
		Logs::writeLog('农场纠纷情况');
        return $this->render('disputeindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Dispute model.
     * @param integer $id
     * @return mixed
     */
    public function actionDisputeview($id)
    {
    	$model = $this->findModel($id);
        Logs::writeLogs('查看纠纷',$model);
        return $this->render('disputeview', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Dispute model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDisputecreate()
    {
        $model = new Dispute();
		
        if ($model->load(Yii::$app->request->post())) {
        	$model->create_at = time();
        	$model->update_at = time();
        	$model->state = 1;
        	$model->save();
        	Logs::writeLogs('创建纠纷',$model);
            //var_dump($model->getErrors());
            return $this->redirect(['disputeview', 'id' => $model->id,'farms_id' => $model->farms_id]);
        } else {
            return $this->render('disputecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Dispute model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDisputeupdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->save();
        	Logs::writeLogs('更新纠纷',$model);
            return $this->redirect(['disputeview', 'id' => $model->id,'farms_id' => $model->farms_id]);
        } else {
            return $this->render('disputeupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Dispute model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDisputedelete($id,$farms_id)
    {
    	$model = $this->findModel($id);
        $model->delete();
        Logs::writeLogs('删除纠纷',$model);
        return $this->redirect(['disputeindex','farms_id'=>$farms_id]);
    }

    public function actionDisputestate($id,$farms_id)
    {
    	$model = $this->findModel($id);
    	$model->state = 1;
    	$model->save();
        Logs::writeLogs('解决纠纷',$model);
    	return $this->redirect(['disputeindex','farms_id'=>$farms_id]);
    }
    /**
     * Finds the Dispute model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dispute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dispute::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
