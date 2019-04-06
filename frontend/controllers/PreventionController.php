<?php

namespace frontend\controllers;

use app\models\Logs;
use Yii;
use app\models\Prevention;
use frontend\models\preventionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\breedinfoSearch;
use app\models\Breed;
use app\models\Theyear;
use app\models\Farms;
use app\models\Breedtype;
use app\models\Breedinfo;
/**
 * PreventionController implements the CRUD actions for Prevention model.
 */
class PreventionController extends Controller
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
     * Lists all Prevention models.
     * @return mixed
     */
    public function actionPreventionindex($farms_id)
    {
    	$breed = Breed::find()->where(['farms_id'=>$farms_id])->one();
        $searchModel = new breedinfoSearch();
        $paramsbreed = Yii::$app->request->queryParams;
        if($breed)
        	$paramsbreed['breedinfoSearch']['breed_id'] = $breed->id;
        else 
        	$paramsbreed['breedinfoSearch']['breed_id'] = 0;
        $dataProvider = $searchModel->search($paramsbreed);
        
        
		$preventionSearch = new preventionSearch();
		$params = Yii::$app->request->queryParams;
		$params['preventionSearch']['farms_id'] = $farms_id;
		$preventionData = $preventionSearch->search($params);
        Logs::writeLog('防疫情况列表',$farms_id);
        return $this->render('preventionindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'preventionSearch' => $preventionSearch,
        	'preventionData' => $preventionData,
        ]);
    }

    /**
     * Displays a single Prevention model.
     * @param integer $id
     * @return mixed
     */
    public function actionPreventionview($id)
    {
        $model = $this->findModel($id);
        Logs::writeLogs('查看防疫情况',$model);
        return $this->render('preventionview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Prevention model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPreventioncreate($id,$farms_id)
    {
        $model = new Prevention();

        if ($model->load(Yii::$app->request->post())) {
        	$model->create_at = time();
        	$model->update_at = $model->create_at;
        	$model->management_area = Farms::getFarmsAreaID($farms_id);
        	$model->breedtype_id = Breedinfo::find()->where(['id'=>$id])->one()['breedtype_id'];
        	$model->save();
            Logs::writeLogs('新增防疫情况',$model);
            return $this->redirect(['preventionindex', 'farms_id'=>$farms_id]);
        } else {
            return $this->render('preventioncreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Prevention model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPreventionupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Logs::writeLogs('更新防疫情况',$model);
            return $this->redirect(['preventionview', 'id' => $model->id]);
        } else {
            return $this->render('preventionupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Prevention model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPreventiondelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Logs::writeLogs('删除防疫情况',$model);
        return $this->redirect(['preventionindex']);
    }

    public function actionPreventionsearch($begindate,$enddate)
    {
    	if(isset($_GET['tab']) and $_GET['tab'] !== \Yii::$app->controller->id) {
    		if($_GET['tab'] == 'yields')
    			$class = 'plantingstructureSearch';
    		else
    			$class = $_GET['tab'].'Search';
    		return $this->redirect ([$_GET['tab'].'/'.$_GET['tab'].'search',
    				'tab' => $_GET['tab'],
    				'begindate' => strtotime($_GET['begindate']),
    				'enddate' => strtotime($_GET['enddate']),
// 					$class =>['management_area' =>  $_GET['management_area']],
    		]);
    	} 
    	$searchModel = new preventionSearch();
		if(!is_numeric($_GET['begindate']))
			 $_GET['begindate'] = strtotime($_GET['begindate']);
		if(!is_numeric($_GET['enddate']))
			 $_GET['enddate'] = strtotime($_GET['enddate']);

    	$dataProvider = $searchModel->searchIndex ( $_GET );
        Logs::writeLogs('综合查询-防疫情况');
    	return $this->render('preventionsearch',[
	    			'searchModel' => $searchModel,
	    			'dataProvider' => $dataProvider,
	    			'tab' => $_GET['tab'],
	    			'begindate' => $_GET['begindate'],
	    			'enddate' => $_GET['enddate'],
	    			'params' => $_GET,
    	]);    	
    }
    
    /**
     * Finds the Prevention model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Prevention the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Prevention::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
