<?php

namespace frontend\controllers;

use app\models\BankAccount;
use app\models\Creditscore;
use app\models\Goodseedinfo;
use app\models\Goodseedinfocheck;
use app\models\Plantingstructure;
use app\models\Plantingstructureyearfarmsid;
use app\models\User;
use app\models\Huinong;
use Yii;
use app\models\Plantingstructurecheck;
use frontend\models\plantingstructurecheckSearch;
use frontend\models\PlantingstructureyearfarmsidSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use app\models\Lease;
use app\models\Plantinputproductcheck;
use app\models\Plantpesticidescheck;
use app\models\Plantinputproduct;
use app\models\Plantpesticides;
use app\models\Logs;
use app\models\Theyear;
use app\models\Plant;
use app\models\ManagementArea;
use frontend\models\bankaccountSearch;
use app\models\Subsidyratio;
/**
 * PlantingstructurecheckController implements the CRUD actions for Plantingstructurecheck model.
 */
class PlantingstructurecheckController extends Controller
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
    
    public function actionPlantingstructurecheckarea()
    {
    	$planting = Plantingstructurecheck::find()->all();
    	foreach ($planting as $value) {
    		$model = $this->findModel($value['id']);
    		$model->management_area = Farms::getFarmsAreaID($farms_id);
    		$model->save();
    	}
    }
    
    /**
     * Lists all Plantingstructurecheck models.
     * @return mixed
     */
    public function actionPlantingstructurecheckindex($farms_id)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
        $lease = Lease::find()->where(['farms_id'=>$farms_id])->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->all();
		$plantings = Plantingstructure::find()->where(['farms_id'=>$farms_id])->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->all();
		$checks = Plantingstructurecheck::find()->where(['farms_id'=>$farms_id])->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->all();
		$farmname = Farms::findOne($farms_id)['farmname'];
		Logs::writeLogs($farmname.'的种植结构复核数据');

		$searchModel = new bankaccountSearch();
		$params = Yii::$app->request->queryParams;
		$params['bankaccountSearch']['farms_id'] = $farms_id;
		$dataProvider = $searchModel->search($params);
		Logs::writeLog('进入银账号管理页面');

        return $this->render('plantingstructurecheckindex', [
            'leases' => $lease,
			'plantings' => $plantings,
			'checks' => $checks,
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPlantingstructurecheckinfo()
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
    	$searchModel = new plantingstructurecheckSearch();
    	$params = Yii::$app->request->queryParams;
//    	$whereArray = Farms::getManagementArea()['id'];

//		if(count($whereArray) == 1)
//			$whereArray = null;
//
////    	if (empty($params['plantingstructurecheckSearch']['management_area'])) {
//			$params ['plantingstructurecheckSearch'] ['management_area'] = $whereArray;
////		}


		$params ['plantingstructurecheckSearch'] ['year'] = User::getYear();
		$dataProvider = $searchModel->searchIndex ( $params );


		$searchModel2 = new PlantingstructureyearfarmsidSearch();

		$params2 = Yii::$app->request->queryParams;
// 		$params ['farmsSearch'] ['state'] = [1,2,3,4,5];
		// 管理区域是否是数组
//		if (empty($params2['PlantingstructureyearfarmsidSearch']['management_area'])) {
			$params2 ['PlantingstructureyearfarmsidSearch'] ['management_area'] = $whereArray;
//		}
		$dataProvider2 = $searchModel2->search ( $params2 );
//        var_dump($dataProvider->getModels());exit;
		//种植结构查询
//		$plantsearchModel = new plantingstructurecheckSearch();
//		$plantparams = Yii::$app->request->queryParams;
//		if (empty($plantparams['plantingstructurecheckSearch']['management_area'])) {
//			$plantparams ['plantingstructurecheckSearch'] ['management_area'] = $whereArray;
//		}
//		$plantparams['plantingstructurecheckSearch']['year'] = User::getYear();
//		$plantdataProvider = $plantsearchModel->searchIndex ( $plantparams );
//		if (is_array($plantsearchModel->management_area)) {
//			$plantsearchModel->management_area = null;
//		}
//		$plantdataProvider = $plantsearchModel->search ( $plantparams );
		Logs::writeLogs('首页十大板块-精准农业复核数据');
    	return $this->render('plantingstructurecheckinfo',[
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'params' => $params,
				'searchModel2' => $searchModel2,
				'dataProvider2' => $dataProvider2,
				'params2' => $params2,
    	]);
    }
    
    
    
    public function actionPlantingstructurechecksearch($tab,$begindate,$enddate)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
//		var_dump('search');exit;
    	if(isset($_GET['tab']) and $_GET['tab'] !== \Yii::$app->controller->id) {
    		if($_GET['tab'] == 'yields')
    			$class = 'plantingstructurecheckSearch';
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
    	$searchModel = new plantingstructurecheckSearch();
		if(!is_numeric($_GET['begindate']))
			 $_GET['begindate'] = strtotime($_GET['begindate']);
		if(!is_numeric($_GET['enddate']))
			 $_GET['enddate'] = strtotime($_GET['enddate']);

    	$dataProvider = $searchModel->searchIndex ( $_GET );
		Logs::writeLogs('综合查询-种植结构复核数据');
    	return $this->render('plantingstructurechecksearch',[
	    			'searchModel' => $searchModel,
	    			'dataProvider' => $dataProvider,
	    			'tab' => $_GET['tab'],
	    			'begindate' => $_GET['begindate'],
	    			'enddate' => $_GET['enddate'],
	    			'params' => $_GET,
    	]);    	
    }
    
    /**
     * Displays a single Plantingstructurecheck model.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantingstructurecheckview($id)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
    	$model = $this->findModel($id);
    	$farm = Farms::find()->where(['id'=>$model->farms_id])->one();
    	$plantinputproductModel = Plantinputproductcheck::find()->where(['planting_id' => $id])->all();
    	$plantpesticidesModel = Plantpesticidescheck::find()->where(['planting_id'=>$id])->all();
    	Logs::writeLogs('查看种植结构复核数据',$model);
        return $this->render('plantingstructurecheckview', [
            'model' => $model,
        	'plantinputproductModel' => $plantinputproductModel,
        	'plantpesticidesModel' => $plantpesticidesModel,
        	'farm' => $farm,
        ]);
    }

    /**
     * Creates a new Plantingstructurecheck model.
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
    
    
    
    //对Plantingstructurecheck中获取的面积进程累加处理
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
    public function actionPlantingstructurecheckgetarea($zongdi) 
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

	public function actionPlantingstructurecheckhnlist()
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
//		var_dump(Plantingstructurecheck::find()->where(['year'=>User::getYear(),'isbank'=>1])->all());exit;
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		$searchModel = new plantingstructurecheckSearch();
		$params = Yii::$app->request->queryParams;
//		$params['plantingstructurecheckSearch']['plant_id'] = $plant;
		$params ['plantingstructurecheckSearch'] ['year'] = User::getYear();
//		$params ['plantingstructurecheckSearch'] ['isbank'] = 1;
		if(User::getItemname('地产科')) {
			$params ['plantingstructurecheckSearch'] ['bankstate'] = 1;
		}
		$dataProvider = $searchModel->searchHuinong( $params );
		Logs::writeLogs(User::getLastYear().'年度惠农政策列表');
		return $this->render('plantingstructurecheckhnlist', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionGetcheck($id)
	{
		$result = [];
		$model = Plantingstructurecheck::findOne($id);
		$farm = Farms::findOne($model->farms_id);
		if($model->lease_id == 0) {
			$bank = BankAccount::find()->where(['cardid'=>$farm['cardid']])->one();
			$result = [
				'id' => $bank['id'],
				'farms_id' => $farm['id'],
				'lessee' => $farm['farmername'],
				'cardid' => $farm['cardid'],
				'bank' => $bank['bank'],
				'accountnumber' => $bank['accountnumber'],
			];
		} else {
			$lease = Lease::findOne($model->lease_id);
			$bank = BankAccount::find()->where(['cardid'=>$lease['lessee_cardid']])->one();
			$result = [
				'id' => $bank['id'],
				'farms_id' => $farm['id'],
				'lessee' => $lease['lessee'],
				'cardid' => $lease['lessee_cardid'],
				'bank' => $bank['bank'],
				'accountnumber' => $bank['accountnumber'],
			];
		}
		echo json_encode($result);
	}
	
	public function actionPlantingstructurechecksame($planting_id)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		$model = new Plantingstructurecheck();
		$load = Plantingstructure::findOne($planting_id);
		$farm = Farms::findOne($load->farms_id);
		$model->plant_id = $load->plant_id;
		$model->area = $load->area;
		$model->goodseed_id = $load->goodseed_id;
		$model->zongdi = $load->zongdi;
		$model->farms_id = $load->farms_id;
		$model->lease_id = $load->lease_id;
		$model->management_area = $load->management_area;
		$model->plant_father = $load->plant_father;
		$model->create_at = time();
		$model->update_at = $model->create_at;
		$model->year = User::getYear();
		$model->issame = 1;
		$model->verifydate = date('Y-m-d');
		$model->contractarea = $farm->contractarea;
		$model->state = $farm->state;
		$model->same_id = $planting_id;
		if($load->lease_id == 0) {
			$cardid = $farm['cardid'];
		} else {
			$cardid = Lease::findOne($load->lease_id)->lessee_cardid;
		}
		$bank = BankAccount::find()->where(['cardid'=>$cardid])->one();
		if($bank) {
			$model->isbank = 1;
			$model->bankstate = $bank['state'];
		}
		$save = $model->save();
		if($save) {
			Plantingstructureyearfarmsid::newCheck($load->farms_id,1);
		}
//		var_dump($model->getErrors());exit;
		Creditscore::run($load->farms_id,$save);

		$goodseedinfo = Goodseedinfo::find()->where(['planting_id'=>$planting_id,'year'=>User::getYear()])->all();
		foreach ($goodseedinfo as $value) {
			$goodseedinfocheckModel = new Goodseedinfocheck();
			$goodseedinfocheckModel->farms_id = $value['farms_id'];
			$goodseedinfocheckModel->management_area = $value['management_area'];
			$goodseedinfocheckModel->lease_id = $value['lease_id'];
			$goodseedinfocheckModel->planting_id = $model->id;
			$goodseedinfocheckModel->plant_id = $value['plant_id'];
			$goodseedinfocheckModel->goodseed_id = $value['goodseed_id'];
			$goodseedinfocheckModel->area = $value['area'];
			$goodseedinfocheckModel->create_at = time();
			$goodseedinfocheckModel->update_at = $model->create_at;
			$goodseedinfocheckModel->year = $value['year'];
			$goodseedinfocheckModel->total_area = $value['area'];
			$goodseedinfocheckModel->save();
		}

		$plantInput = Plantinputproduct::find()->where(['planting_id'=>$planting_id])->all();
		foreach ($plantInput as $value) {
			$plantinputModel = new Plantinputproductcheck();
			Logs::writeLogs('新增复核种植结构的关联投入品',$model);
			$plantinputModel->farms_id = $value['farms_id'];
			$plantinputModel->lessee_id = $value['lessee_id'];
        	$plantinputModel->planting_id = $model->id;
            $plantinputModel->father_id = $value['father_id'];
            $plantinputModel->son_id = $value['son_id'];
            $plantinputModel->inputproduct_id = $value['inputproduct_id'];
            $plantinputModel->pconsumption = $value['pconsumption'];
            $plantinputModel->zongdi = $value['zongdi'];
            $plantinputModel->plant_id = $value['plant_id'];
        	$plantinputModel->create_at = time();
        	$plantinputModel->update_at = $plantinputModel->create_at;
        	$plantinputModel->management_area = $value['management_area'];
			$plantinputModel->save();
		}
		$plantpesticides = Plantpesticides::find()->where(['planting_id'=>$planting_id])->all();
		foreach ($plantpesticides as $value) {
			$plantpesticidesModel = new Plantpesticidescheck();
			Logs::writeLogs('新增复核种植结构的关联农药',$model);
			$plantpesticidesModel->planting_id = $model->id;
            $plantpesticidesModel->farms_id = $value['farms_id'];
            $plantpesticidesModel->lessee_id = $value['lessee_id'];
        	$plantpesticidesModel->plant_id = $value['plant_id'];
            $plantpesticidesModel->pesticides_id = $value['pesticides_id'];
            $plantpesticidesModel->pconsumption = $value['pconsumption'];
        	$plantpesticidesModel->create_at = time();
        	$plantpesticidesModel->update_at = $plantpesticidesModel->create_at;
        	$plantpesticidesModel->management_area = $value['management_area'];
			$plantpesticidesModel->save();
		}
		return $this->redirect(['plantingstructurecheckindex', 'farms_id' => $load->farms_id]);

	}

	public function actionPlantingstructurecheckhnxls($where)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
		Logs::writeLogs('种植结构汇总表');
		$f = 0;
		$l = 0;
		$whereArray = json_decode($where,true);
		$result = [];
//		var_dump($whereArray);
		$data = Plantingstructurecheck::find()->where($whereArray)->all();
//		var_dump(Plantingstructurecheck::find()->where($whereArray)->count());exit;
		$i = 0;
		$farmsAllid = [];
		foreach ($data as $key => $val) {

			$ddarea = 0;
			$ymarea = 0;
			$content = '';
//			$plantid = Huinong::getPlant('id');
			//获取法人种植信息
			$farm = Farms::findOne($val['farms_id']);
			$sub = Subsidyratio::getSubsidyratio($val['plant_id'],$val['farms_id'],$val['lease_id']);
			$management_area = ManagementArea::getAreaname($farm->management_area);
			$farmerp = (float)$sub['farmer']/100;
			$lesseep = (float)$sub['lessee']/100;
			$ymareaf = 0;
			$ddareaf = 0;
			$ymareal = 0;
			$ddareal = 0;
			$ddfarmerbfb = '0';
			$ddleasebfb = '';
			$ymfarmerbfb = '';
			$ymleasebfb = '';
//			if($val['lease_id'] == 676) {
//				var_dump($sub);
//			}
			switch ($val['plant_id']) {
				case 6:
					if($val['lease_id'] == 0) {
						$ddarea = $val['area'];
						$ddfarmerbfb = '100%';
					} else {
						if (bccomp($farmerp, 1) == 0) {
							$p = false;
							$ddarea = $val['area'];
							$ddfarmerbfb = '100%';
						}
						if (bccomp($lesseep, 1) == 0) {
							$p = false;
							$ddarea = $val['area'];
							$ddleasebfb = '100%';
						}
//						if (bccomp($farmerp, 0) == 1 and bccomp($farmerp, 1) == -1) {
						if($farmerp > 0 and $farmerp < 1) {
							$p = true;
							$ddareaf = bcmul($val['area'], $farmerp, 3);
							$ddfarmerbfb = $sub['farmer'];
							$ddareal = bcmul($val['area'], $lesseep, 3);
							$ddleasebfb = $sub['lessee'];
						}
					}
					break;
				case 3:
					if($val['lease_id'] == 0) {
						$ymarea = $val['area'];
						$ymfarmerbfb = '100%';
					} else {
						if (bccomp($farmerp, 1) == 0) {
							$p = false;
							$ymarea = bcmul($val['area'], $farmerp, 2);
							$ymfarmerbfb = '100%';
						}
						if (bccomp($lesseep, 1) == 0) {
							$p = false;
							$ymarea = bcmul($val['area'], $lesseep, 2);
							$ymleasebfb = '100%';
						}
//						if (bccomp($farmerp, 0) == 1 and bccomp($farmerp, 1) == -1) {
						if($farmerp > 0 and $farmerp < 1) {
							$p = true;
							$ymareaf = bcmul($val['area'], $farmerp, 3);
							$ymfarmerbfb = $sub['farmer'];
							$ymareal = bcmul($val['area'], $lesseep, 3);
							$ymleasebfb = $sub['lessee'];
						}
					}
					break;

			}
			if($val['lease_id'] == 0) {
				$bank = BankAccount::find()->where(['cardid' => $farm['cardid'], 'farms_id' => $farm['id'],'lease_id'=>$val['lease_id']])->one();
				$accountnumber = $bank['accountnumber'];
				$cardid = $farm['cardid'];
				$lessee = $farm['farmername'];
				$telephone = $farm['telephone'];
				$f++;
				$i++;
				$result[] = [
					'row' => $i,
					'management_area' => $management_area,
					'farmname' => $farm['farmname'],
					'farmername' => $farm['farmername'],
					'contractnumber' => $farm['contractnumber'],
					'contractarea' => $farm['contractarea'],
					'lease' => $lessee,
					'cardid' => $cardid,
					'accountnumber' => $accountnumber,
					'telephone' => $telephone,
					'ddarea' => $ddarea.'('.$ddfarmerbfb.')',
					'ymarea' => $ymarea.'('.$ymfarmerbfb.')',
					'content' => $content,
				];
			} else {
				$lease = Lease::findOne($val['lease_id']);
//				if($val['lease_id'] == 676) {
//					var_dump($lease);
//				}

//				if($farm->id == 617) {
//					var_dump($val['area']);
//					var_dump($ddarea);
//				}
				$bank = BankAccount::find()->where(['cardid' => $lease['lessee_cardid'], 'farms_id' => $farm['id'],'lease_id'=>$val['lease_id']])->one();
//				if($val['lease_id'] == 676) {
//					var_dump($lesseep);
//				}
//				$accountnumber = $bank['accountnumber'];
//				$cardid = $lease['lessee_cardid'];
//				$lessee = $lease['lessee'];
//				$telephone = $lease['lessee_telephone'];
//				if($val['lease_id'] == 676) {
//					var_dump($farmerp);
//				}
				if(bccomp($farmerp,1) == 0) {
//					if($val['lease_id'] == 676) {
//						var_dump($ddarea);
//					}
					$bank = BankAccount::find()->where(['cardid' => $farm['cardid'], 'farms_id' => $farm['id'],'lease_id'=>0])->one();
//					if($val['lease_id'] == 676) {
//						var_dump($bank);
//					}
					$accountnumber = $bank['accountnumber'];
					$cardid = $farm['cardid'];
					$lessee = $farm['farmername'];
					$telephone = $farm['telephone'];
					$f++;
					$i++;
					$result[] = [
						'row' => $i,
						'management_area' => $management_area,
						'farmname' => $farm['farmname'],
						'farmername' => $farm['farmername'],
						'contractnumber' => $farm['contractnumber'],
						'contractarea' => $farm['contractarea'],
						'lease' => $lessee,
						'cardid' => $cardid,
						'accountnumber' => $accountnumber,
						'telephone' => $telephone,
						'ddarea' => $ddarea.'('.$ddfarmerbfb.')',
						'ymarea' => $ymarea.'('.$ymfarmerbfb.')',
						'content' => $content,
					];
				}
//				if($farm->id == 617) {
//					var_dump($lesseep);
//				}
				if(bccomp($lesseep,1) == 0) {

					$accountnumber = $bank['accountnumber'];
					$cardid = $lease['lessee_cardid'];
					$lessee = $lease['lessee'];
					$telephone = $lease['lessee_telephone'];
					$f++;
					$i++;
					$result[] = [
						'row' => $i,
						'management_area' => $management_area,
						'farmname' => $farm['farmname'],
						'farmername' => $farm['farmername'],
						'contractnumber' => $farm['contractnumber'],
						'contractarea' => $farm['contractarea'],
						'lease' => $lessee,
						'cardid' => $cardid,
						'accountnumber' => $accountnumber,
						'telephone' => $telephone,
						'ddarea' => $ddarea.'('.$ddleasebfb.')',
						'ymarea' => $ymarea.'('.$ymleasebfb.')',
						'content' => $content,
					];
				}
//				var_dump($farmerp);
//				if ((bccomp($farmerp, 0) == 1) and (bccomp($farmerp, 1) == -1)) {
				if($farmerp > 0 and $farmerp < 1) {
					$bank = BankAccount::find()->where(['cardid' => $farm['cardid'], 'farms_id' => $farm['id'],'lease_id'=>0])->one();
					$accountnumber = $bank['accountnumber'];
					$cardid = $farm['cardid'];
					$lessee = $farm['farmername'];
					$telephone = $farm['telephone'];
					$f++;
					$i++;
					$result[] = [
						'row' => $i,
						'management_area' => $management_area,
						'farmname' => $farm['farmname'],
						'farmername' => $farm['farmername'],
						'contractnumber' => $farm['contractnumber'],
						'contractarea' => $farm['contractarea'],
						'lease' => $lessee,
						'cardid' => $cardid,
						'accountnumber' => $accountnumber,
						'telephone' => $telephone,
						'ddarea' => $ddareaf.'('.$ddfarmerbfb.')',
						'ymarea' => $ymareaf.'('.$ymfarmerbfb.')',
						'content' => $content,
					];
					$bank = BankAccount::find()->where(['cardid' =>  $lease['lessee_cardid'], 'farms_id' => $farm['id'],'lease_id'=>$val['lease_id']])->one();
					$accountnumber = $bank['accountnumber'];
					$cardid = $lease['lessee_cardid'];
					$lessee = $lease['lessee'];
					$telephone = $lease['lessee_telephone'];
					$f++;
					$i++;
					$result[] = [
						'row' => $i,
						'management_area' => $management_area,
						'farmname' => $farm['farmname'],
						'farmername' => $farm['farmername'],
						'contractnumber' => $farm['contractnumber'],
						'contractarea' => $farm['contractarea'],
						'lease' => $lessee,
						'cardid' => $cardid,
						'accountnumber' => $accountnumber,
						'telephone' => $telephone,
						'ddarea' => $ddareal.'('.$ddleasebfb.')',
						'ymarea' => $ymareal.'('.$ymleasebfb.')',
						'content' => $content,
					];
//					if($farm->id == 617) {
//						var_dump($result);
//					}
				}

			}


				$plantsum = 0;

//					$plantsum += $val['area'];
//					$content = $val['content'];


//                $contractareaSum += $farm['contractarea'];
//                $plantAllSum += $plantsum;


		}
//		exit;
//        var_dump($farmsAllid);
//        var_dump(array_unique($farmsAllid));exit;
//        $contractareaSum = Farms::find()->where(['id'=>array_unique($farmsAllid)])->sum("contractarea");
//        $plantAllSum = Plantingstructurecheck::find()->where(['farms_id'=>array_unique($farmsAllid)])->sum('area');
//        var_dump($result);exit;
		return $this->render('plantingstructurecheckhnxls', [
			'result' => $result,
			'areaname' => Farms::getManagementArea()['areaname'],
//			'contractareaSum' => sprintf('%2.f',$sum),
//			'testdata' => [$f,$l],
		]);
	}

    public function actionPlantingstructurecheckcreate($lease_id,$farms_id)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
//    	var_dump($lease_id);exit;
    	$model = new Plantingstructurecheck();

    	$farm = Farms::find()->where(['id'=>$farms_id])->one();
		if($lease_id == 0) {
			$cardid = $farm['cardid'];
		} else {
			$cardid = Lease::findOne($lease_id)->lessee_cardid;
		}
    	$noarea = Plantingstructurecheck::getNoArea($lease_id, $farms_id);
    	$overarea = Plantingstructurecheck::getOverArea($lease_id,$farms_id);
//		var_dump($overarea);
//     	var_dump($noarea);exit;
		$plantinputproductModel = new Plantinputproductcheck();
    	$plantpesticidesModel = new Plantpesticidescheck();
    	if ($model->load(Yii::$app->request->post())) {
    		
    		//$model->zongdi = Lease::getZongdi($model->zongdi);
    		$model->create_at = time();
    		$model->update_at = time();
    		$model->management_area = Farms::getFarmsAreaID($farms_id);
			$model->issame = 0;
			$model->contractarea = $farm['contractarea'];
			$model->year = User::getYear();
			if(BankAccount::isBank($cardid,$farms_id)) {
				$model->isbank = 1;
			}
			$model->verifydate = date('Y-m-d');
    		$save = $model->save();
    		if($save) {
				Plantingstructureyearfarmsid::newCheck($farms_id,1);
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
					$plantinputproductModel = new Plantinputproductcheck();
    				$plantinputproductModel->farms_id = $model->farms_id;
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
    				Logs::writeLogs('添加投入品',$plantinputproductModel);
    			}
    		}
    		$parmembersPesticides = Yii::$app->request->post('PlantpesticidesPost');
    		//var_dump($parmembersPesticides);
    		if($parmembersPesticides) {
    			for($i=1;$i<count($parmembersPesticides['pesticides_id']);$i++) {
    				$plantpesticidesModel = new Plantpesticidescheck();
    				$plantpesticidesModel->farms_id = $model->farms_id;
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
    		
    		return $this->redirect(['plantingstructurecheckindex', 'farms_id' => $farms_id]);
    	} else {
    		return $this->render('plantingstructurecheckcreate', [
    				'plantinputproductModel' => $plantinputproductModel,
    				'plantpesticidesModel' => $plantpesticidesModel,
    				'model' => $model,
    				'farm' => $farm,
    				'noarea' => $noarea,
    				'overarea' => $overarea,
    		]);
    	}
    }
    /**
     * Updates an existing Plantingstructurecheck model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantingstructurecheckupdate($id,$lease_id,$farms_id)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
        $model = $this->findModel($id);
        $farm = Farms::find()->where(['id'=>$model->farms_id])->one();
    
//        $lease_area = Lease::getAllLeaseArea($lease_id, $farms_id);
//		if($lease_area == 0) {
//
//		}
        $overarea = Plantingstructurecheck::getOverArea($lease_id, $farms_id);
//		var_dump($overarea);exit;
		if($overarea)
			$noarea = $farm['contractarea'] - $overarea;
		else 
			$noarea = $farm['contractarea'];
        $plantings = Plantingstructurecheck::find()->where(['lease_id'=>$lease_id,'farms_id'=>$farms_id])->all();
        $plantinputproductModel = Plantinputproductcheck::find()->where(['planting_id' => $id])->all();
        $plantpesticidesModel = Plantpesticidescheck::find()->where(['planting_id'=>$id])->all();
        
        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$save = $model->save();
        	Logs::writeLogs('更新租赁信息',$model);
//         	var_dump($model->farms_id);
//         	exit;
        	$parmembersInputproduct = Yii::$app->request->post('PlantInputproductPost');
        	$this->deletePlantinput($plantinputproductModel, $parmembersInputproduct['id']);
        	if ($parmembersInputproduct) {
        		//var_dump($parmembers);
        		for($i=1;$i<count($parmembersInputproduct['inputproduct_id']);$i++) {
        			$plantinputproductModel = Plantinputproductcheck::findOne($parmembersInputproduct['id'][$i]);
        			if(empty($plantinputproductModel))
        				$plantinputproductModel = new Plantinputproductcheck();
        			$plantinputproductModel->id = $parmembersInputproduct['id'][$i];
        			$plantinputproductModel->farms_id = $model->farms_id;
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
        			Logs::writeLogs('添加投入品',$plantinputproductModel);
        		}
        	}
        	//exit;
        	$parmembersPesticides = Yii::$app->request->post('PlantpesticidesPost');
        	$this->deletePlantpesticides($plantpesticidesModel, $parmembersPesticides['id']);
        	if($parmembersPesticides) {
        		for($i=1;$i<count($parmembersPesticides['pesticides_id']);$i++) {
        			$plantpesticidesModel = Plantpesticidescheck::findOne($parmembersPesticides['id'][$i]);
        			if(empty($plantpesticidesModel))
        				$plantpesticidesModel = new Plantpesticidescheck();
        			$plantpesticidesModel->farms_id = $model->farms_id;
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
            return $this->redirect(['plantingstructurecheckindex', 'farms_id' => $model->farms_id]);
        } else {
            return $this->render('plantingstructurecheckupdate', [
            	'plantinputproductModel' => $plantinputproductModel,
            	'plantpesticidesModel' => $plantpesticidesModel,
                'model' => $model,
            	'farm' => $farm,
            	'noarea' => $noarea + $model->area,
            	'overarea' =>$overarea,
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
    			Logs::writeLogs('删除投入品',$model);
    			$model->delete();
    		}
    		return true;
    	} else
    		return false;
    }
    
    /**
     * Deletes an existing Plantingstructurecheck model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantingstructurecheckdelete($id)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		}
        $model = $this->findModel($id);
    	Logs::writeLogs('删除租赁信息',$model);
        $del = $model->delete();

		$goodseedinfocheck = Goodseedinfocheck::find()->where(['planting_id'=>$id,'year'=>User::getYear()])->all();
		foreach ($goodseedinfocheck as $value) {
			$goodseedinfocheckModel = Goodseedinfocheck::findOne($value['id']);
			Logs::writeLogs('删除种植结构的关联良种信息',$goodseedinfocheckModel);
			$goodseedinfocheckModel->delete();
		}

		$plantInput = Plantinputproductcheck::find()->where(['planting_id'=>$id])->all();
		foreach ($plantInput as $value) {
			$plantinputModel = Plantinputproductcheck::findOne($value['id']);
			Logs::writeLogs('删除种植结构的关联投入品',$plantinputModel);
			$plantinputModel->delete();
		}
		$plantpesticides = Plantpesticidescheck::find()->where(['planting_id'=>$id])->all();
		foreach ($plantpesticides as $value) {
			$plantpesticidesModel = Plantpesticidescheck::findOne($value['id']);
			Logs::writeLogs('删除种植结构的关联农药',$plantpesticidesModel);
			$plantpesticidesModel->delete();
		}
		Creditscore::run($model->farms_id,$del);
        return $this->redirect(['plantingstructurecheckindex','farms_id'=>$model->farms_id]);
    }

    /**
     * Finds the Plantingstructurecheck model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plantingstructurecheck the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plantingstructurecheck::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
