<?php

namespace frontend\controllers;

use app\models\Breed;
use app\models\Logs;
use app\models\User;
use frontend\models\farmsSearch;
use Yii;
use app\models\Breedinfo;
use frontend\models\breedinfoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Theyear;
use app\models\Farms;
/**
 * BreedinfoController implements the CRUD actions for Breedinfo model.
 */
class BreedinfoController extends Controller
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
     * Lists all Breedinfo models.
     * @return mixed
     */
    public function actionBreedinfoindex()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $searchModel = new breedinfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('breedinfoindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTemp()
    {
        $breeds = Breed::find()->where(['year'=>User::getYear()])->all();
        foreach ($breeds as $breed) {
            $farmModel = Farms::findOne($breed['farms_id']);
            var_dump($breed['farms_id']);
            $farmModel->isbreed = 1;
            $farmModel->save();
            var_dump($farmModel->getErrors());
        }
        echo 'finished';
    }


    public function actionBreedinfoinfo()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
    	$searchModel = new breedinfoSearch();
    	$params = Yii::$app->request->queryParams;
    	$whereArray = Farms::getManagementArea()['id'];
    
    	if (empty($params['breedinfoSearch']['management_area'])) {
    		$params ['breedinfoSearch'] ['management_area'] = $whereArray;
    	}
//        $params['breedinfoSearch']['year'] = User::getYear();
    	$dataProvider = $searchModel->search ( $params );
    	if (is_array($searchModel->management_area)) {
    		$searchModel->management_area = null;
    	}

        $farmsSearch = new farmsSearch();
        $farmsparams = Yii::$app->request->queryParams;
        $farmsparams['farmsSearch']['isbreed'] = 1;
        if (empty($farmsparams['farmsSearch']['management_area'])) {
            $farmsparams ['farmsSearch'] ['management_area'] = $whereArray;
        }
        $farmsData = $farmsSearch->searchbreed ( $farmsparams );
        if (is_array($farmsSearch->management_area)) {
            $farmsSearch->management_area = null;
        }
        Logs::writeLogs('首页畜牧业板块');
    	return $this->render('breedinfoinfo', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'params' => $params,
                'farmsSearch' => $farmsSearch,
                'farmsData' => $farmsData,
                'farmsparams' => $farmsparams,
    	]);
    }

    /**
     * Displays a single Breedinfo model.
     * @param integer $id
     * @return mixed
     */
    public function actionBreedinfoview($id)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $model = $this->findModel($id);
        Logs::writeLogs('查看养殖情况',$model);
        return $this->render('breedinfoview', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Breedinfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBreedinfocreate($farms_id)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $model = new Breedinfo();

        if ($model->load(Yii::$app->request->post())) {
        	$model->management_area = Farms::getFarmsAreaID($farms_id);
        	$model->create_at = time();
        	$model->update_at = $model->create_at;
        	$model->management_area = Farms::getFarmsAreaID($farms_id);
            $model->year = User::getYear();
        	$save = $model->save();
            if($save) {
                $farmModel = Farms::findOne($farms_id);
                $farmModel->isbreed = 1;
                $farmModel->save();
            }
            Logs::writeLogs('新增养殖信息',$model);
            return $this->redirect(['breedinfoview', 'id' => $model->id]);
        } else {
            return $this->render('breedinfocreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Breedinfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBreedinfoupdate($id)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->save();
            Logs::writeLogs('更新养殖信息',$model);
            return $this->redirect(['breedinfoview', 'id' => $model->id]);
        } else {
            return $this->render('breedinfoupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Breedinfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBreedinfodelete($id)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $this->findModel($id)->delete();

        return $this->redirect(['breedinfoindex']);
    }

    public function actionBreedinfodeleteajax($id)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $model = $this->findModel($id);
        $model->delete();
        Logs::writeLogs('删除养殖信息',$model);
    }
    
    public function actionBreedinfosearch($tab,$begindate,$enddate)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
    	if(isset($_GET['tab']) and $_GET['tab'] !== \Yii::$app->controller->id) {
    		if($_GET['tab'] == 'yields')
    			$class = 'plantingstructurecheckSearch';
    		else
    			$class = $_GET['tab'].'Search';
    		return $this->redirect ([$_GET['tab'].'/'.$_GET['tab'].'search',
    				'tab' => $_GET['tab'],
    				'begindate' => strtotime($_GET['begindate']),
    				'enddate' => strtotime($_GET['enddate']),
// 					$class =>['management_area' =>  $_GET['management_area']],
    		]);
    	} 
    	$searchModel = new breedinfoSearch();
		if(!is_numeric($_GET['begindate']))
			 $_GET['begindate'] = strtotime($_GET['begindate']);
		if(!is_numeric($_GET['enddate']))
			 $_GET['enddate'] = strtotime($_GET['enddate']);

    	$dataProvider = $searchModel->searchIndex ( $_GET );
        Logs::writeLogs('综合查询-养殖信息');
    	return $this->render('breedinfosearch',[
	    			'searchModel' => $searchModel,
	    			'dataProvider' => $dataProvider,
	    			'tab' => $_GET['tab'],
	    			'begindate' => $_GET['begindate'],
	    			'enddate' => $_GET['enddate'],
	    			'params' => $_GET,
    	]);    	
    }

    /**
     * Finds the Breedinfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Breedinfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Breedinfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
