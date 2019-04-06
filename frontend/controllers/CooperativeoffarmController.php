<?php

namespace frontend\controllers;

use Yii;
use app\models\CooperativeOfFarm;
use frontend\models\cooperativeoffarmSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Logs;

/**
 * CooperativeoffarmController implements the CRUD actions for CooperativeOfFarm model.
 */
class CooperativeoffarmController extends Controller
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
     * Lists all CooperativeOfFarm models.
     * @return mixed
     */
    public function actionCooperativeoffarmindex($farms_id)
    {
        $searchModel = new cooperativeoffarmSearch();
        $params = Yii::$app->request->queryParams;
        $params ['cooperativeoffarmSearch'] ['farms_id'] = $farms_id;
        $dataProvider = $searchModel->search($params);
		Logs::writeLogs('农场合作社信息');
        return $this->render('cooperativeoffarmindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cooperativeoffarm model.
     * @param integer $id
     * @return mixed
     */
    public function actionCooperativeoffarmview($id)
    {
    	Logs::writeLog('查看农场合作社信息',$id);
        return $this->render('cooperativeoffarmview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CooperativeOfFarm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCooperativeoffarmcreate($farms_id)
    {
        $model = new CooperativeOfFarm();
		
        if ($model->load(Yii::$app->request->post())) {
        	$model->create_at = time();
        	$model->update_at = time();
        	$model->save();
        	Logs::writeLogs('创建农场合作社信息',$model);
            return $this->redirect(['cooperativeoffarmview', 'id' => $model->id,'farms_id'=>$farms_id]);
        } else {
            return $this->render('cooperativeoffarmcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Cooperativeoffarm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCooperativeoffarmupdate($id,$farms_id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->save();
        	Logs::writeLogs('更新农场合作社信息',$model);
            return $this->redirect(['cooperativeoffarmview', 'id' => $model->id,'farms_id'=>$farms_id]);
        } else {
            return $this->render('cooperativeoffarmupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Cooperativeoffarm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCooperativeoffarmdelete($id,$farms_id)
    {
    	$model = $this->findModel($id);
        Logs::writeLogs('删除农场合作社信息',$model);

        return $this->redirect(['cooperativeoffarmindex','farms_id'=>$farms_id]);
    }

    /**
     * Finds the CooperativeOfFarm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CooperativeOfFarm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CooperativeOfFarm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
