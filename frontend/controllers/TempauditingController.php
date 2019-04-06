<?php

namespace frontend\controllers;

use app\models\Logs;
use Yii;
use app\models\Tempauditing;
use frontend\models\TempauditingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Theyear;

/**
 * TempauditingController implements the CRUD actions for Tempauditing model.
 */
class TempauditingController extends Controller
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
    /**
     * Lists all Tempauditing models.
     * @return mixed
     */
    public function actionTempauditingindex()
    {
    	
        $searchModel = new TempauditingSearch();
        $params = Yii::$app->request->queryParams;
        $params['TempauditingSearch']['user_id'] = Yii::$app->getUser()->id;
        $dataProvider = $searchModel->search($params);

        return $this->render('tempauditingindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tempauditing model.
     * @param integer $id
     * @return mixed
     */
    public function actionTempauditingview($id)
    {
        return $this->render('tempauditingview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tempauditing model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionTempauditingcreate()
    {
        $model = new Tempauditing();
        $searchModel = new TempauditingSearch();
        $params = Yii::$app->request->queryParams;
        $params['TempauditingSearch']['user_id'] = Yii::$app->getUser()->id;
        $dataProvider = $searchModel->search($params);
        if ($model->load(Yii::$app->request->post())) {
        	if(Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->count()) {
        		$this->error('该用户目前有临时授权，同一人不能多人授权，请选择其他用户。');
        	}

        	$model->user_id = Yii::$app->getUser()->id;
        	$model->create_at = time();
        	$model->update_at = $model->create_at;
        	$model->begindate = (string)strtotime($model->begindate);
        	$model->enddate = (string)strtotime($model->enddate);
        	$model->state = 1;
        	$model->save();
            Logs::writeLogs('创建临时授权',$model);
//         	var_dump($model->getErrors());exit;
            return $this->redirect(['tempauditingcreate']);
        } else {
            return $this->render('tempauditingcreate', [
                'model' => $model,
            	'searchModel' => $searchModel,
            	'dataProvider' => $dataProvider,
            ]);
        }
    }
	//回收授权
    public function actionTempauditingback($id)
    {
    	$model = $this->findModel($id);
//     	var_dump($model);
    	$model->state = 0;
    	$model->save();
        Logs::writeLogs('回收授权',$model);
//     	var_dump($model->getErrors());exit;
    	return $this->redirect(['tempauditingcreate']);
    	
    }
    
    //申请延长时间，申请一次延长3天
    public function actionTempauditingextend($id)
    {
    	$model = $this->findModel($id);
    	$model->enddate = (string)Theyear::extendDate($model->enddate, 'day', 3);
    	$model->save();
        Logs::writeLogs('申请延长授权时间',$model);
//     	var_dump(date('Y-m-d',Theyear::extendDate($model->enddate, 'day', 3)));
//     	var_dump($model->getErrors());exit;
    	return $this->redirect(['tempauditing/extend']);
    }
    
    public function actionExtend()
    {
    	return $this->render('extend');
    }
    /**
     * Updates an existing Tempauditing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTempauditingupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['tempauditingview', 'id' => $model->id]);
        } else {
            return $this->render('tempauditingupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Tempauditing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTempauditingdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['tempauditingindex']);
    }

    /**
     * Finds the Tempauditing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tempauditing the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tempauditing::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function error($msg)
    {
    	$errors = [$msg];
    	return $this->redirect(['error']);
    }
}
