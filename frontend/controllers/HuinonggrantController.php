<?php

namespace frontend\controllers;

use app\models\Huinong;
use app\models\Logs;
use frontend\models\MachineapplySearch;
use Yii;
use app\models\Huinonggrant;
use frontend\models\HuinonggrantSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use app\models\Theyear;
use frontend\models\farmsSearch;
use frontend\models\plantingstructurecheckSearch;
use app\models\User;
/**
 * HuinonggrantController implements the CRUD actions for Huinonggrant model.
 */
class HuinonggrantController extends Controller
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
     * Lists all Huinonggrant models.
     * @return mixed
     */
    public function actionHuinonggrantindex()
    {
        $searchModel = new huinonggrantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('huinonggrantindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Huinonggrant model.
     * @param integer $id
     * @return mixed
     */
    public function actionHuinonggrantview($id)
    {
        return $this->render('huinonggrantview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Huinonggrant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionHuinonggrantcreate()
    {
        $model = new Huinonggrant();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['huinonggrantview', 'id' => $model->id]);
        } else {
            return $this->render('huinonggrantcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Huinonggrant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionHuinonggrantupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['huinonggrantview', 'id' => $model->id]);
        } else {
            return $this->render('huinonggrantupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Huinonggrant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionHuinonggrantdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['huinonggrantindex']);
    }

//    public function actionHuinonggrantinfo()
//    {
//        if(Yii::$app->user->isGuest) {
//            return $this->redirect(['site/login']);
//        }
//    	$searchModel = new HuinonggrantSearch();
//    	$params = Yii::$app->request->queryParams;
//    	$whereArray = Farms::getManagementArea()['id'];
//    	if (empty($params['huinonggrantSearch']['management_area'])) {
//    		$params ['huinonggrantSearch'] ['management_area'] = $whereArray;
//    	}
//    	$params['begindate'] = Theyear::getYeartime()[0];
//    	$params['enddate'] = Theyear::getYeartime()[1];
////    	$params['state'] = 1;
//    	// 		var_dump($params);
//    	$dataProvider = $searchModel->searchIndex ( $params );
//    	if (is_array($searchModel->management_area)) {
//    		$searchModel->management_area = null;
//    	}
//
//        $farmsSearch = new farmsSearch();
//        $farmsparams = Yii::$app->request->queryParams;
////        $farmsparams['farmsSearch']['state'] = [1,2,3,4,5];
//        if (empty($farmsparams['farmsSearch']['management_area'])) {
//            $farmsparams ['farmsSearch'] ['management_area'] = $whereArray;
//        }
//        $farmsData = $farmsSearch->search ( $farmsparams );
//        if (is_array($farmsSearch->management_area)) {
//            $farmsSearch->management_area = null;
//        }
//        Logs::writeLogs('首页板块-惠农政策');
//    	return $this->render('huinonggrantinfo',[
//    			'searchModel' => $searchModel,
//    			'dataProvider' => $dataProvider,
//    			'params' => $params,
//                'farmsSearch' => $farmsSearch,
//                'farmsData' => $farmsData,
//                'farmsparams' => $farmsparams,
//    	]);
//    }

    public function actionHuinonggrantinfo()
    {
//		var_dump(Plantingstructurecheck::find()->where(['year'=>User::getYear(),'isbank'=>1])->all());exit;
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        }
        $whereArray = Farms::getManagementArea()['id'];

        $searchModel = new plantingstructurecheckSearch();
        $params = Yii::$app->request->queryParams;
//		$params['plantingstructurecheckSearch']['plant_id'] = $plant;
        $params ['plantingstructurecheckSearch'] ['year'] = User::getYear();
//		$params ['plantingstructurecheckSearch'] ['isbank'] = 1;
        if(User::getItemname('地产科') or User::getItemname('项目科')) {
            $params ['plantingstructurecheckSearch'] ['bankstate'] = 1;
        }
        if (empty($params['plantingstructurecheckSearch']['management_area'])) {
            $params ['plantingstructurecheckSearch'] ['management_area'] = $whereArray;
        }
        $dataProvider = $searchModel->searchHuinong( $params );
        Logs::writeLogs(User::getLastYear().'年度惠农政策列表');

        //已经申请完成的农机器具
        $machineSearch = new MachineapplySearch();
        $mparams = Yii::$app->request->queryParams;
        $mparams['MachineapplySearch']['scanfinished'] = 1;
        $mparams['MachineapplySearch']['state'] = 1;
        $mparams['MachineapplySearch']['year'] = User::getYear();
        if (empty($mparams['MachineapplySearch']['management_area'])) {
            $mparams ['MachineapplySearch'] ['management_area'] = $whereArray;
        }
        $dataMachine = $machineSearch->search( $mparams );
        return $this->render('huinonggrantinfo', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'machineSearch' => $machineSearch,
            'dataMachine' => $dataMachine,
        ]);
    }
    
    
    public function actionHuinonggrantsearch($tab,$begindate,$enddate)
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
        $searchModel = new plantingstructurecheckSearch();
        if (!is_numeric($_GET['begindate']))
            $_GET['begindate'] = strtotime($_GET['begindate']);
        if (!is_numeric($_GET['enddate']))
            $_GET['enddate'] = strtotime($_GET['enddate']);

        $year = Theyear::getYearArray(strtotime($begindate),strtotime($enddate));
        $huinong = Huinong::find()->where(['year' => $year])->all();
//        var_dump($huinong);exit;
        if($huinong) {

            $plantid = [];
            $plantids = [];
            $years = [];
            foreach ($huinong as $h) {
                $years[] = $h['year'];
                $plantids[] = $h['typeid'];
            }
//            var_dump($years);exit;
            $newyear = array_unique($years);
            $newplantid = array_unique($plantids);
//        var_dump($plantid);exit;
            $beginArray = explode('-', $begindate);
            $endArray = explode('-', $enddate);
            $newbegindate = '';
            $newenddate = '';
            $newbegindate = reset($newyear) . '-' . $beginArray[1] . '-' . $beginArray[2];
            $newenddate = end($newyear) . '-' . $endArray[1] . '-' . $endArray[2];

            if ($newbegindate) {
                $_GET['begindate'] = strtotime($newbegindate);
            }
            if ($newenddate) {
                $_GET['enddate'] = strtotime($newenddate);
            }
            $_GET['plantingstructurecheckSearch']['plant_id'] = $newplantid;
        }
//    	$_GET['state'] = 1;
    	$dataProvider = $searchModel->searchHuinongsearch ( $_GET );
        Logs::writeLogs('综合查询-惠农政策');
    	return $this->render('huinonggrantsearch',[
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'tab' => $_GET['tab'],
    			'begindate' => $_GET['begindate'],
    			'enddate' => $_GET['enddate'],
    			'params' => $_GET,
    	]);
    }
    
    /**
     * Finds the Huinonggrant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Huinonggrant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Huinonggrant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
