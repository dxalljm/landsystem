<?php

namespace frontend\controllers;

use app\models\Logs;
use Yii;
use app\models\Infrastructuretype;
use frontend\models\infrastructuretypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InfrastructuretypeController implements the CRUD actions for Infrastructuretype model.
 */
class InfrastructuretypeController extends Controller
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
     * Lists all Infrastructuretype models.
     * @return mixed
     */
    public function actionInfrastructuretypeindex()
    {
        $searchModel = new infrastructuretypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Logs::writeLogs('项目类别列表');
        return $this->render('infrastructuretypeindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Infrastructuretype model.
     * @param integer $id
     * @return mixed
     */
    public function actionInfrastructuretypeview($id)
    {
        $model = $this->findModel($id);
        Logs::writeLogs('查看项目类别',$model);
        return $this->render('infrastructuretypeview', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Infrastructuretype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionInfrastructuretypecreate()
    {
        $model = new Infrastructuretype();

        if ($model->load(Yii::$app->request->post())) {
        	$model->save();
        	Logs::writeLogs('新增项目类别',$model);
            return $this->redirect(['infrastructuretypeview', 'id' => $model->id]);
        } else {
            return $this->render('infrastructuretypecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Infrastructuretype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInfrastructuretypeupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Logs::writeLogs('更新项目类别',$model);
            return $this->redirect(['infrastructuretypeview', 'id' => $model->id]);
        } else {
            return $this->render('infrastructuretypeupdate', [
                'model' => $model,
            ]);
        }
    }

    public function actionGetson($father_id)
    {
    	$result = Infrastructuretype::find()->where(['father_id'=>$father_id])->all();
    	foreach($result as $key=>$val){
    		$newData[$key] = $val->attributes;
    	}
    	if($result)
    		$son = 1;
    	else 
    		$son = 0;
		echo json_encode(['son'=>$son,'data'=>$newData]);
    }
    
    /**
     * Deletes an existing Infrastructuretype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInfrastructuretypedelete($id)
    {
        $model = $this->findModel($id)->delete();
        Logs::writeLogs('删除项目类别',$model);
        return $this->redirect(['infrastructuretypeindex']);
    }

    /**
     * Finds the Infrastructuretype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Infrastructuretype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Infrastructuretype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
