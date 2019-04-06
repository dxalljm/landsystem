<?php

namespace frontend\controllers;

use Yii;
use app\models\Plant;
use frontend\models\plantSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Logs;

/**
 * PlantController implements the CRUD actions for Plant model.
 */
class PlantController extends Controller
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
     * Lists all Plant models.
     * @return mixed
     */
    public function actionPlantindex()
    {
    	//print_r(Yii::$app->request->queryParams);
        $searchModel = new plantSearch();
        $dataProvider = $searchModel->search('id>1','with');
		Logs::writeLog('作物管理');
        return $this->render('plantindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Plant model.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantview($id)
    {
        $model = $this->findModel($id);
    	Logs::writeLogs('查看作物信息',$model);
        return $this->render('plantview', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Plant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPlantcreate()
    {
        $model = new Plant();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	Logs::writeLogs('添加作物',$model);
            return $this->redirect(['plantview', 'id' => $model->id]);
        } else {
            return $this->render('plantcreate', [
                'model' => $model,
            ]);
        }
    }

    public function actionPlantgetson($father_id)
    {
    	$plantSon = Plant::find()->where(['father_id'=>$father_id])->all();
    	$newData = NULL;
    	foreach($plantSon as $key=>$val){
    		$newData[$key] = $val->attributes;
    	}
    	echo json_encode(['status'=>1,'son'=>$newData]);
    }
    
    public function objectToArray($e){
    	$e=(array)$e;
    	foreach($e as $k=>$v){
    		if( gettype($v)=='resource' ) return;
    		if( gettype($v)=='object' || gettype($v)=='array' )
    			$e[$k]=(array)$this->objectToArray($v);
    	}
    	return $e;
    }
    
    /**
     * Updates an existing Plant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantupdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	Logs::writeLogs('更新作物信息',$model);
            return $this->redirect(['plantview', 'id' => $model->id]);
        } else {
            return $this->render('plantupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Plant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantdelete($id)
    {
        $model = $this->findModel($id);
    	Logs::writeLogs('删除作物',$model);
        $model->delete();

        return $this->redirect(['plantindex']);
    }

    /**
     * Finds the Plant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
