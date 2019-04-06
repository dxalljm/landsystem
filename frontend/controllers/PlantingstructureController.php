<?php

namespace frontend\controllers;

use app\models\Goodseedinfo;
use app\models\User;
use Yii;
use app\models\Plantingstructurecheck;
use app\models\Plantingstructure;
use frontend\models\plantingstructureSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use app\models\Lease;
use frontend\models\plantingstructurecheckSearch;
use app\models\Plantinputproduct;
use app\models\Plantpesticides;
use app\models\Logs;
use app\models\Theyear;
use app\models\Plant;
use app\models\ManagementArea;
use app\models\Plantingstructureyearfarmsid;
use frontend\models\PlantingstructureyearfarmsidSearch;
use app\models\Plantingstructureyearfarmsidplan;
use frontend\models\PlantingstructureyearfarmsidplanSearch;
/**
 * PlantingstructureController implements the CRUD actions for Plantingstructure model.
 */
class PlantingstructureController extends Controller
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
			return $this->redirect(['site/login']);
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

    public function actionPlantingstructurearea()
    {
    	$planting = Plantingstructurecheck::find()->all();
    	foreach ($planting as $value) {
    		$model = $this->findModel($value['id']);
    		$model->management_area = Farms::getFarmsAreaID($farms_id);
    		$model->save();
    	}
    }

    /**
     * Lists all Plantingstructure models.
     * @return mixed
     */
    public function actionPlantingstructureindex($farms_id)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
        $lease = Lease::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
		$farmname = Farms::findOne($farms_id)['farmname'];
		Logs::writeLog($farmname.'的种植结构');
        return $this->render('plantingstructureindex', [
             'leases' => $lease,
        ]);
    }

	public function actionPlantingstructuresixcheck($farms_id)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		$lease = Lease::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
		$farmname = Farms::findOne($farms_id)['farmname'];
		Logs::writeLogs($farmname.'的租赁及种植结构信息');
		return $this->renderajax('plantingstructuresixcheck', [
			'leases' => $lease,
			'noarea' => Lease::getNoArea($farms_id),
			'overarea' => Lease::getOverArea($farms_id),
		]);
	}

    public function actionPlantingstructureinfo()
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
		$whereArray = Farms::getManagementArea()['id'];
		//计划表---以作物
		$searchModel = new plantingstructureSearch();
		$params = Yii::$app->request->queryParams;
		$params ['plantingstructureSearch'] ['management_area'] = $whereArray;
		$params ['plantingstructureSearch'] ['year'] = User::getYear();
		$dataProvider = $searchModel->searchIndex ( $params );

		//核实表---以作物
		$searchCheckModel = new plantingstructurecheckSearch();
		$paramscheck = Yii::$app->request->queryParams;

		$paramscheck ['plantingstructurecheckSearch'] ['management_area'] = $whereArray;

		$paramscheck ['plantingstructurecheckSearch'] ['year'] = User::getYear();
		$dataProviderCheck = $searchCheckModel->searchIndex ( $paramscheck );

		//核实表---以农场
		$searchModel2 = new PlantingstructureyearfarmsidSearch();

		$params2 = Yii::$app->request->queryParams;
		$params2 ['PlantingstructureyearfarmsidSearch'] ['management_area'] = $whereArray;
		$params2 ['PlantingstructureyearfarmsidSearch'] ['year'] = User::getYear();
//		}
		$dataProvider2 = $searchModel2->search ( $params2 );

		//计划表---以农场
		$searchModel2plan = new PlantingstructureyearfarmsidplanSearch();

		$params2plan = Yii::$app->request->queryParams;
		$params2plan ['PlantingstructureyearfarmsidplanSearch'] ['management_area'] = $whereArray;
		$params2plan ['PlantingstructureyearfarmsidplanSearch'] ['year'] = User::getYear();
//		}
		$dataProvider2plan = $searchModel2plan->search ( $params2plan );

		Logs::writeLogs('首页十大板块-精准农业');
		return $this->render('plantingstructureinfo',[
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'params' => $params,
			'searchModel2' => $searchModel2,
			'dataProvider2' => $dataProvider2,
			'searchModel2plan' => $searchModel2plan,
			'dataProvider2plan' => $dataProvider2plan,
			'searchCheckModel' => $searchCheckModel,
			'dataProviderCheck' => $dataProviderCheck,
			'params2' => $params2,
		]);
    }

	public function actionDelplan($id)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		$model = Plantingstructure::findOne($id);
		Logs::writeLogs('删除种植结构',$model);
		$state = $model->delete();

		$info = Goodseedinfo::find()->where(['planting_id'=>$id,'year'=>User::getYear()])->all();
		if($info) {
			foreach ($info as $value) {
				$goodseedinfoModel = Goodseedinfo::findOne($value['id']);
				Logs::writeLogs('删除种植结构的关联良种信息',$goodseedinfoModel);
				$goodseedinfoModel->delete();
			}
		}

		$input = Plantinputproduct::find()->where(['planting_id'=>$id])->all();
		if($input) {
			foreach ($input as $value) {
				$im = Plantinputproduct::findOne($value['id']);
				Logs::writeLogs('删除相关投入品信息',$im);
				$im->delete();
			}
		}
		$pes = Plantpesticides::find()->where(['planting_id'=>$id])->all();
		if($pes) {
			foreach ($pes as $value) {
				$pm = Plantpesticides::findOne($value['id']);
				Logs::writeLogs('删除相关农药信息', $pm);
				$pm->delete();
			}
		}
		echo json_encode(['state'=>$state]);
	}


    public function actionPlantingstructuresearch($tab,$begindate,$enddate)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
    	if(isset($_GET['tab']) and $_GET['tab'] !== \Yii::$app->controller->id) {
    		if($_GET['tab'] == 'yields')
    			$class = 'plantingstructurecSearch';
    		else
    			$class = $_GET['tab'].'Search';

    		return $this->redirect ([$_GET['tab'].'/'.$_GET['tab'].'search',
    				'tab' => $_GET['tab'],
    				'begindate' => strtotime($_GET['begindate']),
    				'enddate' => strtotime($_GET['enddate']),
//					$class =>['management_area' =>  $_GET['management_area']],
    		]);
    	}
//     	var_dump($_GET);exit;
    	$searchModel = new plantingstructureSearch();
		if(!is_numeric($_GET['begindate']))
			 $_GET['begindate'] = strtotime($_GET['begindate']);
		if(!is_numeric($_GET['enddate']))
			 $_GET['enddate'] = strtotime($_GET['enddate']);

    	$dataProvider = $searchModel->searchIndex ( $_GET );

		$searchCheckModel = new plantingstructurecheckSearch();
		if(!is_numeric($_GET['begindate']))
			$_GET['begindate'] = strtotime($_GET['begindate']);
		if(!is_numeric($_GET['enddate']))
			$_GET['enddate'] = strtotime($_GET['enddate']);

		$dataProviderCheck = $searchCheckModel->searchIndex ( $_GET );
		Logs::writeLogs('综合查询-精准农业');
    	return $this->render('plantingstructuresearch',[
	    			'searchModel' => $searchModel,
	    			'dataProvider' => $dataProvider,
					'searchCheckModel' => $searchCheckModel,
					'dataProviderCheck' => $dataProviderCheck,
	    			'tab' => $_GET['tab'],
	    			'begindate' => $_GET['begindate'],
	    			'enddate' => $_GET['enddate'],
	    			'params' => $_GET,
    	]);
    }

    /**
     * Displays a single Plantingstructure model.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantingstructureview($id)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
//		var_dump($id);exit;
    	$model = $this->findModel($id);
    	$farm = Farms::find()->where(['id'=>$model->farms_id])->one();
    	$plantinputproductModel = Plantinputproduct::find()->where(['planting_id' => $id])->all();
    	$plantpesticidesModel = Plantpesticides::find()->where(['planting_id'=>$id])->all();
    	Logs::writeLogs('查看种植结构',$model);
        return $this->render('plantingstructureview', [
            'model' => $model,
        	'plantinputproductModel' => $plantinputproductModel,
        	'plantpesticidesModel' => $plantpesticidesModel,
        	'farm' => $farm,
        ]);
    }

    /**
     * Creates a new Plantingstructure model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */


    //获取承租人的宗地信息，如果已经添加过，则过滤掉
    public function getListZongdi($lease_id)
    {
    	//$zongdi = array();
    	$lease = Lease::find()->where(['id'=>$lease_id])->one();
    	$zongdiarr = explode('、', $lease['lease_area']);
    	$plantings = Plantingstructurecheck::find()->where(['lease_id'=>$lease_id])->all();

    	$zongdi = [];
    	if($plantings) {
	    	foreach($zongdiarr as $value) {
	    		foreach ($plantings as $plants) {
	    			$plantArray = explode('、', $plants['zongdi']);
	    			foreach($plantArray as $plant) {
	    				//echo Lease::getArea($value) .'-'. Lease::getArea($plant).'<br>';
		    			if(Lease::getZongdi($value) == Lease::getZongdi($plant)){
		    				if(Lease::getArea($value) !== Lease::getArea($plant)){
		    					//echo Lease::getArea($value) .'-'. Lease::getArea($plant).'<br>';
		    					$areac = Lease::getArea($value) - Lease::getArea($plant);
		    					$v = Lease::getZongdi($value).'('.$areac.')';
		    					//var_dump($v);
		    					$zongdi[$v] = $v;
		    					//echo 'zongdi_l=';var_dump($zongdi);
		    				}
		    			} else {
		    				//var_dump($zongdiarr);
		    				$zongdi[$value] = $value;
		    				//var_dump($zongdi);
		    				//$zongdi = array_diff($zongdi,$zongdiarr);
		    			}
		    		}
	    		}
	    	}
	    	//var_dump($zongdi);
	    	return $zongdi;
    	}
    	else {
    		foreach($zongdiarr as $key => $value) {
    			$zongdi[$value] = $value;
    		}
    		//var_dump($zongdi);
    		return $zongdi;
    	}
    }



    //对plantingstructure中获取的面积进程累加处理
    public function zongdiAreaSum($arrayArea)
    {
    	//var_dump($arrayArea[0]['zongdi']);

    	for($i=0;$i<count($arrayArea);$i++) {
    		for($j=$i+1;$j<count($arrayArea);$j++) {
    			if(isset($arrayArea[$j]['zongdi'])) {
	    			if(Lease::getZongdi($arrayArea[$i]['zongdi']) == Lease::getZongdi($arrayArea[$j]['zongdi'])) {
	    				$areaSum = $arrayArea[$i]['area']+$arrayArea[$j]['area'];
	    				//$arrayArea[$i]['zongdi'] = Lease::getZongdi($arrayArea[$i]['zongdi']).'('.$areaSum.')';
	    				$arrayArea[$i]['area'] = $areaSum;
	    				unset($arrayArea[$j]);
	    				sort($arrayArea);
	    				//var_dump($arrayArea);
	    				$arrayArea = self::zongdiAreaSum($arrayArea);
	    			}
    			}
    		}
    	}
    	return $arrayArea;
    }
    //已经使用投入品的面积
    public function actionPlantingstructuregetarea($zongdi)
    {
    	$area = Lease::getListArea($zongdi);
    	echo json_encode(['status'=>1,'area'=>$area]);
    }
    //获取作物面积
    public function actionGetplantarea($farms_id,$plant_id)
    {
    	$area = 0;
    	$planting = Plantingstructurecheck::find()->where(['farms_id'=>$farms_id,'plant_id'=>$plant_id])->all();
    	foreach ($planting as $value) {
    		$area += $value['area'];
    	}
    	echo json_encode(['status'=>1,'area'=>$area]);
    }

	//获取剩余面积
	public function actionGetarea($farms_id)
	{
		$sum = 0.0;
		$contractarea = Farms::find()->where(['id'=>$farms_id])->one()['contractarea'];
		$planting = Plantingstructurecheck::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
		if($planting) {
			foreach ($planting as $value) {
				$sum += $value['area'];
			}
			$area = bcsub($contractarea, $sum,2);
		} else {
			$area = $contractarea;
		}
		echo json_encode(['area'=>$area]);
	}

    public function actionPlantingstructurecreate($lease_id,$farms_id,$class=null)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
    	$model = new Plantingstructure();

    	$farm = Farms::find()->where(['id'=>$farms_id])->one();
    	$noarea = Plantingstructure::getNoArea($lease_id, $farms_id);
    	$overarea = (float)Plantingstructure::getOverArea($lease_id, $farms_id);
//     	var_dump($overarea);var_dump($noarea);exit;s
    	$plantinputproductModel = new Plantinputproduct();
    	$plantpesticidesModel = new Plantpesticides();
    	if ($model->load(Yii::$app->request->post())) {

    		//$model->zongdi = Lease::getZongdi($model->zongdi);
    		$model->create_at = time();
    		$model->update_at = $model->create_at;
    		$model->management_area = Farms::getFarmsAreaID($farms_id);
			$model->year = User::getYear();
    		$state = $model->save();
			if($state) {
				Plantingstructureyearfarmsidplan::newPlan($farms_id,1);
			}
    		$new = $model->attributes;
    		//var_dump($new);
    		Logs::writeLogs('为'.Lease::find()->where(['id'=>$lease_id])->one()['lessee'].'创建种植结构信息',$model);


    		//$plantinputproducts = Plantinputproduct::find()->where(['farms_id'=>$planting->farms_id,'lessee_id'=>$planting->lease_id,'plant_id'=>$planting->plant_id,'zongdi'=>$planting->zongdi])->all();
    		$parmembersInputproduct = Yii::$app->request->post('PlantInputproductPost');
    		//var_dump($parmembersInputproduct);
    		if ($parmembersInputproduct) {
    			//var_dump($parmembers);
    			for($i=1;$i<count($parmembersInputproduct['inputproduct_id']);$i++) {
    				$plantinputproductModel = new Plantinputproduct();
    				$plantinputproductModel->farms_id = $model->farms_id;
					$plantinputproductModel->management_area = $model->management_area;
    				$plantinputproductModel->lessee_id = $model->lease_id;
    				$plantinputproductModel->zongdi = $model->zongdi;
    				$plantinputproductModel->plant_id = $model->plant_id;
    				$plantinputproductModel->planting_id = $model->id;
    				$plantinputproductModel->father_id = $parmembersInputproduct['father_id'][$i];
    				$plantinputproductModel->son_id = $parmembersInputproduct['son_id'][$i];
    				$plantinputproductModel->inputproduct_id = $parmembersInputproduct['inputproduct_id'][$i];
    				$plantinputproductModel->pconsumption = $parmembersInputproduct['pconsumption'][$i];
    				$plantinputproductModel->create_at = time();
    				$plantinputproductModel->update_at = time();
    				$plantinputproductModel->save();
    				//var_dump($new);
    				Logs::writeLogs('添加投入品',$plantinputproductModel);
    			}
    		}
    		$parmembersPesticides = Yii::$app->request->post('PlantpesticidesPost');
    		//var_dump($parmembersPesticides);
    		if($parmembersPesticides) {
    			for($i=1;$i<count($parmembersPesticides['pesticides_id']);$i++) {
    				$plantpesticidesModel = new Plantpesticides();
    				$plantpesticidesModel->farms_id = $model->farms_id;
					$plantpesticidesModel->management_area = $model->management_area;
    				$plantpesticidesModel->lessee_id = $model->lease_id;
    				$plantpesticidesModel->plant_id = $model->plant_id;
    				$plantpesticidesModel->planting_id = $model->id;
    				$plantpesticidesModel->pesticides_id = $parmembersPesticides['pesticides_id'][$i];
    				$plantpesticidesModel->pconsumption = $parmembersPesticides['pconsumption'][$i];
    				$plantpesticidesModel->create_at = time();
    				$plantpesticidesModel->update_at = time();
    				$plantpesticidesModel->save();
    				Logs::writeLogs('添加投入品',$plantpesticidesModel);
    			}
    		}
    		if(empty($class)) {
    			return $this->redirect(['plantingstructureindex', 'farms_id' => $farms_id]);
    		} else {
    			return $this->redirect(['lease/leaseindex', 'farms_id' => $farms_id]);
    		}

    	} else {
    		return $this->render('plantingstructurecreate', [
    				'plantinputproductModel' => $plantinputproductModel,
    				'plantpesticidesModel' => $plantpesticidesModel,
    				'lease' => Lease::findOne($lease_id),
    				'model' => $model,
    				'farm' => $farm,
    				'noarea' => $noarea,
    				'overarea' => $overarea,
					'year' => date('Y')
    		]);
    	}
    }

    public function actionPlantingstructureleasecreate($lease_id,$farms_id,$year=null)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		if(empty($year)) {
			$year = User::getYear();
		}
    	$model = new Plantingstructure();
		$lease = Lease::findOne($lease_id);
    	$farm = Farms::find()->where(['id'=>$farms_id])->one();
    	$noarea = Plantingstructure::getNoArea($lease_id, $farms_id,$year);
    	$overarea = Plantingstructure::getLeaseArea($lease_id);
    	//     	var_dump($overarea);var_dump($noarea);exit;
    	$plantinputproductModel = new Plantinputproduct();
    	$plantpesticidesModel = new Plantpesticides();
    	if ($model->load(Yii::$app->request->post())) {

    		//$model->zongdi = Lease::getZongdi($model->zongdi);
    		$model->create_at = time();
    		$model->update_at = $model->create_at;
    		$model->management_area = Farms::getFarmsAreaID($farms_id);
    		$model->year = $year;
    		$state = $model->save();
			if($state) {
				Plantingstructureyearfarmsidplan::newPlan($farms_id,1,$year);
			}

    		$new = $model->attributes;
    		//var_dump($new);
    		Logs::writeLogs('为'.Lease::find()->where(['id'=>$lease_id])->one()['lessee'].'创建种植结构信息',$model);


    		//$plantinputproducts = Plantinputproduct::find()->where(['farms_id'=>$planting->farms_id,'lessee_id'=>$planting->lease_id,'plant_id'=>$planting->plant_id,'zongdi'=>$planting->zongdi])->all();
    		$parmembersInputproduct = Yii::$app->request->post('PlantInputproductPost');
    		//var_dump($parmembersInputproduct);
    		if ($parmembersInputproduct) {
    			//var_dump($parmembers);
    			for($i=1;$i<count($parmembersInputproduct['inputproduct_id']);$i++) {
    				$plantinputproductModel = new Plantinputproduct();
    				$plantinputproductModel->farms_id = $model->farms_id;
    				$plantinputproductModel->management_area = $model->management_area;
    				$plantinputproductModel->lessee_id = $model->lease_id;
    				$plantinputproductModel->zongdi = $model->zongdi;
    				$plantinputproductModel->plant_id = $model->plant_id;
    				$plantinputproductModel->planting_id = $model->id;
    				$plantinputproductModel->father_id = $parmembersInputproduct['father_id'][$i];
    				$plantinputproductModel->son_id = $parmembersInputproduct['son_id'][$i];
    				$plantinputproductModel->inputproduct_id = $parmembersInputproduct['inputproduct_id'][$i];
    				$plantinputproductModel->pconsumption = $parmembersInputproduct['pconsumption'][$i];
    				$plantinputproductModel->create_at = time();
    				$plantinputproductModel->update_at = time();
    				$plantinputproductModel->save();
    				$new = $plantinputproductModel->attributes;
    				//var_dump($new);
    				Logs::writeLogs('添加投入品',$plantinputproductModel);
    			}
    		}
    		$parmembersPesticides = Yii::$app->request->post('PlantpesticidesPost');
    		//var_dump($parmembersPesticides);
    		if($parmembersPesticides) {
    			for($i=1;$i<count($parmembersPesticides['pesticides_id']);$i++) {
    				$plantpesticidesModel = new Plantpesticides();
    				$plantpesticidesModel->farms_id = $model->farms_id;
    				$plantpesticidesModel->management_area = $model->management_area;
    				$plantpesticidesModel->lessee_id = $model->lease_id;
    				$plantpesticidesModel->plant_id = $model->plant_id;
    				$plantpesticidesModel->planting_id = $model->id;
    				$plantpesticidesModel->pesticides_id = $parmembersPesticides['pesticides_id'][$i];
    				$plantpesticidesModel->pconsumption = $parmembersPesticides['pconsumption'][$i];
    				$plantpesticidesModel->create_at = time();
    				$plantpesticidesModel->update_at = time();
    				$plantpesticidesModel->save();
    				$new = $plantpesticidesModel->attributes;
    				Logs::writeLogs('添加投入品',$plantpesticidesModel);
    			}
    		}

    			return $this->redirect(['lease/leaseindex', 'farms_id' => $farms_id,'year'=>$year]);

    	} else {
    		return $this->renderAjax('plantingstructurecreate', [
    				'plantinputproductModel' => $plantinputproductModel,
    				'plantpesticidesModel' => $plantpesticidesModel,
    				'model' => $model,
					'lease' => $lease,
    				'farm' => $farm,
    				'noarea' => $noarea,
    				'overarea' => $overarea,
					'year' => $year,
    		]);
    	}
    }
    /**
     * Updates an existing Plantingstructure model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantingstructureupdate($id,$lease_id,$farms_id)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
        $model = $this->findModel($id);
        $old = $model->attributes;
        $farm = Farms::find()->where(['id'=>$model->farms_id])->one();

        $area = Lease::getAllLeaseArea($farms_id);
//		var_dump($area);exit;
        $overarea = Plantingstructure::getOverArea($lease_id, $farms_id);
//		var_dump($overarea);exit;
		if($overarea)
			$noarea = $area - $overarea;
		else
			$noarea = $area;
        $plantings = Plantingstructure::find()->where(['lease_id'=>$lease_id,'farms_id'=>$farms_id])->all();
        $plantinputproductModel = Plantinputproduct::find()->where(['planting_id' => $id])->all();
        $plantpesticidesModel = Plantpesticides::find()->where(['planting_id'=>$id])->all();

        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$state = $model->save();
			if($state) {
				Plantingstructureyearfarmsidplan::newPlan($farms_id,1);
			}
        	Logs::writeLogs('更新租赁信息',$model);
//         	var_dump($model->farms_id);
//         	exit;
        	$parmembersInputproduct = Yii::$app->request->post('PlantInputproductPost');
        	$this->deletePlantinput($plantinputproductModel, $parmembersInputproduct['id']);
        	if ($parmembersInputproduct) {
        		//var_dump($parmembers);
        		for($i=1;$i<count($parmembersInputproduct['inputproduct_id']);$i++) {
        			$plantinputproductModel = Plantinputproduct::findOne($parmembersInputproduct['id'][$i]);
        			if(empty($plantinputproductModel))
        				$plantinputproductModel = new Plantinputproduct();
        			$plantinputproductModel->id = $parmembersInputproduct['id'][$i];
        			$plantinputproductModel->farms_id = $model->farms_id;
					$plantinputproductModel->management_area = $model->management_area;
        			$plantinputproductModel->lessee_id = $model->lease_id;
        			$plantinputproductModel->zongdi = $model->zongdi;
        			$plantinputproductModel->plant_id = $model->plant_id;
        			$plantinputproductModel->planting_id = $model->id;
        			$plantinputproductModel->father_id = $parmembersInputproduct['father_id'][$i];
        			$plantinputproductModel->son_id = $parmembersInputproduct['son_id'][$i];
        			$plantinputproductModel->inputproduct_id = $parmembersInputproduct['inputproduct_id'][$i];
        			$plantinputproductModel->pconsumption = $parmembersInputproduct['pconsumption'][$i];
        			$plantinputproductModel->create_at = time();
        			$plantinputproductModel->update_at = time();
					$model->management_area = Farms::getFarmsAreaID($farms_id);
        			$plantinputproductModel->save();
        			Logs::writeLogs('添加投入品',$plantinputproductModel);
        		}
        	}
        	//exit;
        	$parmembersPesticides = Yii::$app->request->post('PlantpesticidesPost');
        	$this->deletePlantpesticides($plantpesticidesModel, $parmembersPesticides['id']);
        	if($parmembersPesticides) {
        		for($i=1;$i<count($parmembersPesticides['pesticides_id']);$i++) {
        			$plantpesticidesModel = Plantpesticides::findOne($parmembersPesticides['id'][$i]);
        			if(empty($plantpesticidesModel))
        				$plantpesticidesModel = new Plantpesticides();
        			$plantpesticidesModel->farms_id = $model->farms_id;
					$plantpesticidesModel->management_area = $model->management_area;
        			$plantpesticidesModel->lessee_id = $model->lease_id;
        			$plantpesticidesModel->plant_id = $model->plant_id;
        			$plantpesticidesModel->planting_id = $model->id;
        			$plantpesticidesModel->pesticides_id = $parmembersPesticides['pesticides_id'][$i];
        			$plantpesticidesModel->pconsumption = $parmembersPesticides['pconsumption'][$i];
        			$plantpesticidesModel->create_at = time();
        			$plantpesticidesModel->update_at = time();
        			$plantpesticidesModel->save();
        			Logs::writeLogs('添加投入品',$plantpesticidesModel);
        		}
        	}
            return $this->redirect(['plantingstructureindex', 'farms_id' => $model->farms_id]);
        } else {
//			var_dump($noarea);
//			var_dump($model->area);
//			exit;
            return $this->render('plantingstructureupdate', [
            	'plantinputproductModel' => $plantinputproductModel,
            	'plantpesticidesModel' => $plantpesticidesModel,
                'model' => $model,
            	'farm' => $farm,
            	'noarea' => sprintf('%.2f',$noarea + $model->area),
            	'overarea' =>$overarea,
            	//'leases' => $lease,
            ]);
        }
    }


	public function actionPlantingstructuret($id)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		$model = $this->findModel($id);
		$old = $model->attributes;
		$farm = Farms::find()->where(['id'=>$model->farms_id])->one();

		$area = Lease::getAllLeaseArea($model->farms_id);
//		var_dump($area);exit;
		$overarea = Plantingstructure::getOverArea($model->lease_id, $model->farms_id);
//		var_dump($overarea);exit;
		if($overarea)
			$noarea = $area - $overarea;
		else
			$noarea = $area;
		$plantings = Plantingstructure::find()->where(['lease_id'=>$model->lease_id,'farms_id'=>$model->farms_id])->all();
		$plantinputproductModel = Plantinputproduct::find()->where(['planting_id' => $id])->all();
		$plantpesticidesModel = Plantpesticides::find()->where(['planting_id'=>$id])->all();

			return $this->renderAjax('plantingstructuret', [
				'plantinputproductModel' => $plantinputproductModel,
				'plantpesticidesModel' => $plantpesticidesModel,
				'model' => $model,
				'farm' => $farm,
				'noarea' => sprintf('%.2f',$noarea + $model->area),
				'overarea' =>$overarea,
				'planting_id' => $id,
				//'leases' => $lease,
			]);
	}


	public function actionPlantingstructureupdateajax($id,$lease_id,$farms_id)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		$model = $this->findModel($id);
		$farm = Farms::find()->where(['id'=>$model->farms_id])->one();

		$area = Lease::getAllLeaseArea($lease_id, $farms_id);
		$overarea = Plantingstructurecheck::getOverArea($lease_id, $farms_id);
		if($overarea)
			$noarea = $area - $overarea;
		else
			$noarea = $area;
		$plantings = Plantingstructurecheck::find()->where(['lease_id'=>$lease_id,'farms_id'=>$farms_id])->all();
		$plantinputproductModel = Plantinputproduct::find()->where(['planting_id' => $id])->all();
		$plantpesticidesModel = Plantpesticides::find()->where(['planting_id'=>$id])->all();

		if ($model->load(Yii::$app->request->post())) {
			$model->update_at = time();
			$model->save();
			Logs::writeLogs('更新租赁信息',$model);
//         	var_dump($model->farms_id);
//         	exit;
			$parmembersInputproduct = Yii::$app->request->post('PlantInputproductPost');
			$this->deletePlantinput($plantinputproductModel, $parmembersInputproduct['id']);
			if ($parmembersInputproduct) {
				//var_dump($parmembers);
				for($i=1;$i<count($parmembersInputproduct['inputproduct_id']);$i++) {
					$plantinputproductModel = Plantinputproduct::findOne($parmembersInputproduct['id'][$i]);
					if(empty($plantinputproductModel))
						$plantinputproductModel = new Plantinputproduct();
					$plantinputproductModel->id = $parmembersInputproduct['id'][$i];
					$plantinputproductModel->farms_id = $model->farms_id;
					$plantinputproductModel->management_area = $model->management_area;
					$plantinputproductModel->lessee_id = $model->lease_id;
					$plantinputproductModel->zongdi = $model->zongdi;
					$plantinputproductModel->plant_id = $model->plant_id;
					$plantinputproductModel->planting_id = $model->id;
					$plantinputproductModel->father_id = $parmembersInputproduct['father_id'][$i];
					$plantinputproductModel->son_id = $parmembersInputproduct['son_id'][$i];
					$plantinputproductModel->inputproduct_id = $parmembersInputproduct['inputproduct_id'][$i];
					$plantinputproductModel->pconsumption = $parmembersInputproduct['pconsumption'][$i];
					$plantinputproductModel->create_at = time();
					$plantinputproductModel->update_at = time();
					$model->management_area = Farms::getFarmsAreaID($farms_id);
					$plantinputproductModel->save();
					Logs::writeLogs('添加投入品',$plantinputproductModel);
				}
			}
			//exit;
			$parmembersPesticides = Yii::$app->request->post('PlantpesticidesPost');
			$this->deletePlantpesticides($plantpesticidesModel, $parmembersPesticides['id']);
			if($parmembersPesticides) {
				for($i=1;$i<count($parmembersPesticides['pesticides_id']);$i++) {
					$plantpesticidesModel = Plantpesticides::findOne($parmembersPesticides['id'][$i]);
					if(empty($plantpesticidesModel))
						$plantpesticidesModel = new Plantpesticides();
					$plantpesticidesModel->farms_id = $model->farms_id;
					$plantpesticidesModel->management_area = $model->management_area;
					$plantpesticidesModel->lessee_id = $model->lease_id;
					$plantpesticidesModel->plant_id = $model->plant_id;
					$plantpesticidesModel->planting_id = $model->id;
					$plantpesticidesModel->pesticides_id = $parmembersPesticides['pesticides_id'][$i];
					$plantpesticidesModel->pconsumption = $parmembersPesticides['pconsumption'][$i];
					$plantpesticidesModel->create_at = time();
					$plantpesticidesModel->update_at = time();
					$plantpesticidesModel->save();
					Logs::writeLogs('添加投入品',$plantpesticidesModel);
				}
			}
			return $this->redirect(['sixcheck/sixcheckindex', 'farms_id' => $model->farms_id]);
		} else {
			return $this->renderajax('plantingstructureupdate', [
				'plantinputproductModel' => $plantinputproductModel,
				'plantpesticidesModel' => $plantpesticidesModel,
				'model' => $model,
				'farm' => $farm,
				'noarea' => $noarea + $model->area,
				'overarea' => sprintf('%.2f',$overarea),
				//'leases' => $lease,
			]);
		}
	}

    private function deletePlantinput($nowdatabase,$postdataidarr) {
    	$databaseid = array();
    	foreach($nowdatabase as $value) {
    		$databaseid[] = $value['id'];
    	}
    	$result = array_diff($databaseid,$postdataidarr);
    	if($result) {
    		foreach($result as $val) {
    			$model = Plantinputproduct::findOne($val);
    			//var_dump($model->attributes);
    			$oldAttr = $model->attributes;
    			Logs::writeLogs('删除投入品',$model);
    			$model->delete();
    		}
    		return true;
    	} else
    		return false;
    }

    private function deletePlantpesticides($nowdatabase,$postdataidarr) {
    	$databaseid = array();
    	foreach($nowdatabase as $value) {
    		$databaseid[] = $value['id'];
    	}
    	$result = array_diff($databaseid,$postdataidarr);
    	if($result) {
    		foreach($result as $val) {
    			$model = Plantpesticides::findOne($val);
    			$oldAttr = $model->attributes;
    			Logs::writeLogs('删除投入品',$model);
    			$model->delete();
    		}
    		return true;
    	} else
    		return false;
    }

    /**
     * Deletes an existing Plantingstructure model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantingstructuredelete($id,$farms_id)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
        $model = $this->findModel($id);
    	Logs::writeLogs('删除租赁信息',$model);
		$plantings = Plantingstructure::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->count();
		if($plantings == 0) {
			$plan = Plantingstructureyearfarmsidplan::find()->where(['farms_id' => $farms_id, 'year' => User::getYear()])->one();
			if ($plan) {
				$plan->isfinished = 0;
				$plan->save();
			}
		}
        $model->delete();

		$info = Goodseedinfo::find()->where(['planting_id'=>$id,'year'=>User::getYear()])->all();
		if($info) {
			foreach ($info as $value) {
				$goodseedinfoModel = Goodseedinfo::findOne($value['id']);
				Logs::writeLogs('删除种植结构的关联良种信息',$goodseedinfoModel);
				$goodseedinfoModel->delete();
			}
		}

		$plantInput = Plantinputproduct::find()->where(['planting_id'=>$id])->all();
		foreach ($plantInput as $value) {
			$plantinputModel = Plantinputproduct::findOne($value['id']);
			Logs::writeLogs('删除种植结构的关联投入品',$plantinputModel);
			$plantinputModel->delete();
		}
		$plantpesticides = Plantpesticides::find()->where(['planting_id'=>$id])->all();
		foreach ($plantpesticides as $value) {
			$plantpesticidesModel = Plantpesticides::findOne($value['id']);
			Logs::writeLogs('删除种植结构的关联农药',$plantpesticidesModel);
			$plantpesticidesModel->delete();
		}
        return $this->redirect(['plantingstructureindex','farms_id'=>$farms_id]);
    }

    /**
     * Finds the Plantingstructure model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plantingstructure the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plantingstructure::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
