<?php
/**
 * Created by PhpStorm.
 * User: liujiaming
 * Date: 2019/1/13
 * Time: 16:20
 */

namespace frontend\controllers;

use app\models\Picturelibrary;
use frontend\models\GoodseedinfoSearch;
use Yii;
use app\models\Logs;
use yii\web\Controller;
use app\models\Farms;
use frontend\models\farmsSearch;
use app\models\Plantingstructure;
use frontend\models\plantingstructureSearch;
use app\models\Plantingstructurecheck;
use frontend\models\plantingstructurecheckSearch;
use app\models\Collection;
use frontend\models\collectionSearch;
use app\models\Fireprevention;
use frontend\models\firepreventionSearch;
use app\models\Loan;
use frontend\models\loanSearch;
use app\models\Insurance;
use frontend\models\insuranceSearch;
use app\models\Projectapplication;
use frontend\models\projectapplicationSearch;
use app\models\Breedinfo;
use frontend\models\breedinfoSearch;
use app\models\Yields;
use frontend\models\yieldsSearch;
use app\models\User;
use app\models\Plantingstructureyearfarmsid;
use frontend\models\PlantingstructureyearfarmsidSearch;
use app\models\Plantingstructureyearfarmsidplan;
use frontend\models\PlantingstructureyearfarmsidplanSearch;
use app\models\Sales;
use frontend\models\salesSearch;
use frontend\models\MachinetypeSearch;
use frontend\models\MachineapplySearch;
use frontend\models\FixedSearch;
use frontend\models\MachineoffarmSearch;
use frontend\models\huinonggrantSearch;
use frontend\models\preventionSearch;
use frontend\models\disasterSearch;
use frontend\models\GoodseedinfocheckSearch;

class TeninfoController extends Controller
{
    public function actionFarmsland()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $whereArray = Farms::getManagementArea()['id'];
        if(count($whereArray) == 7)
            $whereArray = null;
        $searchModel = new farmsSearch ();
        $params = Yii::$app->request->queryParams;
// 		$params ['farmsSearch'] ['state'] = 1;
        // 管理区域是否是数组
        if (empty($params['farmsSearch']['management_area'])) {
            $params ['farmsSearch'] ['management_area'] = $whereArray;
        }

        $dataProvider = $searchModel->search ( $params );

        // 如果选择多个区域, 默认为空
        if (is_array($searchModel->management_area)) {
            $searchModel->management_area = null;
        }
//exit;
        Logs::writeLogs ( '首页宜农林地板块' );
        return $this->render ( 'farmsland', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ] );
    }

    public function actionGoodseedinfoinfo()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $whereArray = Farms::getManagementArea()['id'];
        if(count($whereArray) == 7)
            $whereArray = null;
        $searchModel = new GoodseedinfoSearch();
        $params = Yii::$app->request->queryParams;
// 		$params ['farmsSearch'] ['state'] = 1;
        // 管理区域是否是数组
        if (empty($params['farmsSearch']['management_area'])) {
            $params ['farmsSearch'] ['management_area'] = $whereArray;
        }

        $dataProvider = $searchModel->search ( $params );

        // 如果选择多个区域, 默认为空
        if (is_array($searchModel->management_area)) {
            $searchModel->management_area = null;
        }
//exit;
        Logs::writeLogs ( '首页宜农林地板块' );
        return $this->render ( 'farmsland', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ] );
    }


    public function actionFarmssearch($tab,$class,$begindate, $enddate)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        Logs::writeLogs('综合查询-农场信息');
        if($tab !== $class) {
//            var_dump($tab);exit;
            return $this->redirect ([$tab.'search',
                'tab' => $tab,
                'class' => $tab,
                'begindate' => strtotime($_GET['begindate']),
                'enddate' => strtotime($_GET['enddate']),
// 					$class =>['management_area' =>  $_GET['management_area']],
            ]);
        }
        $searchModel = new farmsSearch();
        if(!is_numeric($_GET['begindate']))
            $_GET['begindate'] = strtotime($_GET['begindate']);
        if(!is_numeric($_GET['enddate']))
            $_GET['enddate'] = strtotime($_GET['enddate']);

        $dataProvider = $searchModel->searchIndex ( $_GET );
        return $this->render('farmssearch',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tab' => $_GET['tab'],
            'class' => $class,
            'begindate' => $_GET['begindate'],
            'enddate' => $_GET['enddate'],
            'params' => $_GET,
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

        $goodseedSearch = new GoodseedinfocheckSearch();
        $goodseedparams = Yii::$app->request->queryParams;
        $goodseedparams ['GoodseedinfocheckSearch'] ['management_area'] = $whereArray;
        $goodseedparams ['GoodseedinfocheckSearch'] ['year'] = User::getYear();
        $goodseedData = $goodseedSearch->search ( $goodseedparams );

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
            'goodseedSearch' => $goodseedSearch,
            'goodseedData' => $goodseedData,
            'params2' => $params2,
        ]);
    }

    public function actionPlantingstructuresearch($tab,$class,$begindate,$enddate)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        if($tab !== $class) {
            return $this->redirect ([$tab.'search',
                'tab' => $tab,
                'class' => $tab,
                'begindate' => strtotime($_GET['begindate']),
                'enddate' => strtotime($_GET['enddate']),
// 					$class =>['management_area' =>  $_GET['management_area']],
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
            'class' => $class,
            'begindate' => $_GET['begindate'],
            'enddate' => $_GET['enddate'],
            'params' => $_GET,
        ]);
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
        $farmsData = $farmsSearch->search ( $farmsparams );
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

    public function actionBreedinfosearch($tab,$class,$begindate,$enddate)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        if($tab !== $class) {
            return $this->redirect ([$tab.'search',
                'tab' => $tab,
                'class' => $tab,
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
            'class' => $class,
            'begindate' => $_GET['begindate'],
            'enddate' => $_GET['enddate'],
            'params' => $_GET,
        ]);
    }

    //防疫情况图表数据
    public function actionPreventionsearch($tab,$class,$begindate,$enddate)
    {
        if($tab !== $class) {
            return $this->redirect ([$tab.'search',
                'tab' => $tab,
                'class' => $tab,
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
            'class' => $class,
            'begindate' => $_GET['begindate'],
            'enddate' => $_GET['enddate'],
            'params' => $_GET,
        ]);
    }

    public function actionCollectioninfo($year=null,$begindate=null,$enddate=null)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
//     	var_dump($enddate);exit;
        $b = $begindate;
        $e = $enddate;
        $searchModel = new collectionSearch();
        $params = Yii::$app->request->queryParams;
//     	$params['tempprintbillSearch']['state'] = 0;
        if (!empty($begindate)) {
            if(!strstr($begindate,'00:00:01'))
                $begindate = $begindate . ' 00:00:01';
//			var_dump($_GET);
            if(isset($_GET['collectionSearch']['state']))
                $params['collectionSearch']['state'] = $_GET['collectionSearch']['state'];
            else
                $params['collectionSearch']['state'] = 1;
            $params['begindate'] = strtotime($begindate);
        }
        if (!empty($enddate)) {
            if(!strstr($enddate,'23:59:59'))
                $enddate = $enddate . ' 23:59:59';
            $params['enddate'] = strtotime($enddate);
        }
        if(empty($year)) {
            $year = User::getYear();
        }
//     	var_dump($begindate);var_dump($enddate);

//     	var_dump($enddate);
//
//     	var_dump(date('Y-m-d',$params['enddate']));exit;
        $whereArray = Farms::getManagementArea()['id'];
        if (empty($params['collectionSearch']['management_area'])) {
            $params ['collectionSearch'] ['management_area'] = $whereArray;
        }
        if(empty($begindate) or empty($enddate)) {
//			if(empty($year))
//				$params['collectionSearch']['payyear'] = User::getYear();
//			else
            $params['collectionSearch']['payyear'] = $year;
        }
//		else
//			$params['collectionSearch']['payyear'] = date('Y',$params['begindate']);
//		var_dump($params);exit;
        $dataProvider = $searchModel->searchIndex ( $params );

        if (is_array($searchModel->management_area)) {
            $searchModel->management_area = null;
        }
        Logs::writeLogs('首页-承包费收缴');
//     	var_dump($params);exit;
        return $this->render('collectioninfo',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
            'begindate' => $begindate,
            'enddate' => $enddate,
            'year' => $year,
        ]);

    }

    //农机数据图表
    public function actionMachineapplysearch($tab,$class,$begindate,$enddate)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        if($tab !== $class) {
            return $this->redirect ([$tab.'search',
                'tab' => $tab,
                'class' => $tab,
                'begindate' => strtotime($_GET['begindate']),
                'enddate' => strtotime($_GET['enddate']),
// 					$class =>['management_area' =>  $_GET['management_area']],
            ]);
        }
        $searchModel = new MachineapplySearch();
        $_GET['MachineapplySearch']['state'] = 1;
        if(!is_numeric($_GET['begindate']))
            $_GET['begindate'] = strtotime($_GET['begindate']);
        if(!is_numeric($_GET['enddate']))
            $_GET['enddate'] = strtotime($_GET['enddate']);

        $dataProvider = $searchModel->searchSearch ( $_GET );
        Logs::writeLogs('综合查询-农机补贴');
        return $this->render('machineapplysearch',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tab' => $_GET['tab'],
            'class' => $class,
            'begindate' => $_GET['begindate'],
            'enddate' => $_GET['enddate'],
            'params' => $_GET,
        ]);
    }

    public function actionCollectionsearch($tab,$class,$begindate,$enddate)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        if($tab !== $class) {
            return $this->redirect ([$tab.'search',
                'tab' => $tab,
                'class' => $tab,
                'begindate' => strtotime($_GET['begindate']),
                'enddate' => strtotime($_GET['enddate']),
// 					$class =>['management_area' =>  $_GET['management_area']],
            ]);
        }
        $searchModel = new collectionSearch();
        $noSearchModel = new collectionSearch();
        if(!is_numeric($_GET['begindate']))
            $_GET['begindate'] = strtotime($_GET['begindate']);
        if(!is_numeric($_GET['enddate']))
            $_GET['enddate'] = strtotime($_GET['enddate']);

        $dataProvider = $searchModel->searchIndex ( $_GET );
        $nodata = $noSearchModel->noSearchIndex($_GET);
        Logs::writeLogs('综合查询-承包费收缴');
        return $this->render('collectionsearch',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tab' => $_GET['tab'],
            'class' => $class,
            'begindate' => $_GET['begindate'],
            'enddate' => $_GET['enddate'],
            'params' => $_GET,
            'nodata' => $nodata,
            'noSearchModel' => $noSearchModel,
        ]);
    }

    //灾害图表数据
    public function actionDisastersearch($tab,$class,$begindate,$enddate)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        if($tab !== $class) {
            return $this->redirect ([$tab.'search',
                'tab' => $tab,
                'class' => $tab,
                'begindate' => strtotime($_GET['begindate']),
                'enddate' => strtotime($_GET['enddate']),
// 					$class =>['management_area' =>  $_GET['management_area']],
            ]);
        }
        $searchModel = new disasterSearch();
        if(!is_numeric($_GET['begindate']))
            $_GET['begindate'] = strtotime($_GET['begindate']);
        if(!is_numeric($_GET['enddate']))
            $_GET['enddate'] = strtotime($_GET['enddate']);

        $dataProvider = $searchModel->searchIndex ( $_GET );
        Logs::writeLogs('综合查询-灾害');
        return $this->render('disastersearch',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tab' => $_GET['tab'],
            'class' => $class,
            'begindate' => $_GET['begindate'],
            'enddate' => $_GET['enddate'],
            'params' => $_GET,
        ]);
    }

    public function actionFirepreventioninfo()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $fire = Fireprevention::find()->where(['year'=>User::getYear()])->count();
        $farm = Farms::find()->where(['state'=>[1,2,3,4,5]]);
        if($fire < $farm->count()) {
            foreach ($farm->all() as $f) {
                $model = Fireprevention::find()->where(['year'=>User::getYear(),'farms_id'=>$f['id']])->one();
                if(empty($model)) {
                    $model = new Fireprevention();
                    $model->farms_id = $f['id'];
                    $model->management_area = $f['management_area'];
                    $model->firecontract = "0";
                    $model->safecontract = "0";
                    $model->environmental_agreement = "0";
                    $model->firetools = "0";
                    $model->mechanical_fire_cover = "0";
                    $model->chimney_fire_cover = "0";
                    $model->isolation_belt = "0";
                    $model->propagandist = "0";
                    $model->fire_administrator = "0";
                    $model->cooker = "0";
                    $model->propaganda_firecontract = "0";
                    $model->employee_firecontract = "0";
                    $model->equipmentpic = '';
                    $model->peoplepic = '';
                    $model->facilitiespic = '';
                    $model->create_at = time();
                    $model->update_at = $model->create_at;
                    $model->percent = '';
                    $model->fieldpermit = "0";
                    $model->leaflets = "0";
                    $model->rectification_record = "0";
                    $model->finished = 0;
                    $model->year = User::getYear();
                    $model->save();
//                    var_dump($model->getErrors());exit;
                }
            }
        }

        $searchModel = new firepreventionSearch();
        $params = Yii::$app->request->queryParams;
//		$searchModel = new farmsSearch();
//		$params = Yii::$app->request->queryParams;
        $whereArray = Farms::getManagementArea()['id'];
        //var_dump($whereArray);exit;
        if (empty($params['firepreventionSearch']['management_area'])) {
            $params ['firepreventionSearch'] ['management_area'] = $whereArray;
        }

        //if (is_array($searchModel->management_area)) {
        //	$searchModel->management_area = null;
        //}
//		$params['firepreventionSearch']['farmstate'] = [1,2,3,4,5];
        $params['firepreventionSearch']['year'] = User::getYear();
        $dataProvider = $searchModel->searchindex( $params );

        Logs::writeLog('首页板块-防火工作');
        return $this->render('firepreventioninfo', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);
    }

    public function actionFirepreventionsearch($tab,$class,$begindate,$enddate)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        if($tab !== $class) {
            return $this->redirect ([$tab.'search',
                'tab' => $tab,
                'class' => $tab,
                'begindate' => strtotime($_GET['begindate']),
                'enddate' => strtotime($_GET['enddate']),
// 					$class =>['management_area' =>  $_GET['management_area']],
            ]);
        }
        $searchModel = new firepreventionSearch();
        if(!is_numeric($_GET['begindate']))
            $_GET['begindate'] = strtotime($_GET['begindate']);
        if(!is_numeric($_GET['enddate']))
            $_GET['enddate'] = strtotime($_GET['enddate']);

        $dataProvider = $searchModel->searchIndex ( $_GET );
        Logs::writeLogs('综合查询-防火工作');
        return $this->render('firepreventionsearch',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tab' => $_GET['tab'],
            'class' => $class,
            'begindate' => $_GET['begindate'],
            'enddate' => $_GET['enddate'],
            'params' => $_GET,
        ]);
    }

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


    public function actionHuinonggrantsearch($tab,$class,$begindate,$enddate)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        if($tab !== $class) {
//            var_dump($begindate);exit;
            return $this->redirect ([$tab.'search',
                'tab' => $tab,
                'class' => $tab,
                'begindate' => strtotime($_GET['begindate']),
                'enddate' => strtotime($_GET['enddate']),
// 					$class =>['management_area' =>  $_GET['management_area']],
            ]);
        }
        $searchModel = new plantingstructurecheckSearch();
        if(!is_numeric($_GET['begindate']))
            $_GET['begindate'] = strtotime($_GET['begindate']);
        if(!is_numeric($_GET['enddate']))
            $_GET['enddate'] = strtotime($_GET['enddate']);
        $_GET['state'] = 1;
        $dataProvider = $searchModel->searchIndex ( $_GET );
        Logs::writeLogs('综合查询-惠农政策');

        //已经申请完成的农机器具
        $machineSearch = new MachineapplySearch();
        if(!is_numeric($_GET['begindate']))
            $_GET['begindate'] = strtotime($_GET['begindate']);
        if(!is_numeric($_GET['enddate']))
            $_GET['enddate'] = strtotime($_GET['enddate']);
        $dataMachine = $machineSearch->searchSearch( $_GET );

        return $this->render('huinonggrantsearch',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataMachine' => $dataMachine,
            'machineSearch' => $machineSearch,
            'tab' => $_GET['tab'],
            'class' => $class,
            'begindate' => $_GET['begindate'],
            'enddate' => $_GET['enddate'],
            'params' => $_GET,
        ]);
    }

    public function actionInsuranceinfo()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $searchModel = new insuranceSearch();
        $params = Yii::$app->request->queryParams;
        $params['insuranceSearch']['state'] = 1;
        $dataProvider = $searchModel->searchIndex ($params);

        $plantSearch = new plantingstructureSearch();
        $paramsplant = Yii::$app->request->queryParams;
        $paramsplant['plantingstructureSearch']['isinsurance'] = 1;
        $paramsplant['plantingstructureSearch']['year'] = User::getYear();
        $dataProviderplan = $plantSearch->searchIndex ($paramsplant);
        Logs::writeLogs('首页-保险业务');
        return $this->render('insuranceinfo',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchPlan' => $plantSearch,
            'dataProviderplan' => $dataProviderplan,
        ]);
    }

    public function actionInsurancesearch($tab,$class,$begindate,$enddate)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        if($tab !== $class) {
            return $this->redirect ([$tab.'search',
                'tab' => $tab,
                'class' => $tab,
                'begindate' => strtotime($_GET['begindate']),
                'enddate' => strtotime($_GET['enddate']),
// 					$class =>['management_area' =>  $_GET['management_area']],
            ]);
        }
        $searchModel = new insuranceSearch();
        if(!is_numeric($_GET['begindate']))
            $_GET['begindate'] = strtotime($_GET['begindate']);
        if(!is_numeric($_GET['enddate']))
            $_GET['enddate'] = strtotime($_GET['enddate']);
        $_GET['insuranceSearch']['state'] = 1;
//        $_GET['insuranceSearch']['year'] = User::getYear();
        $dataProvider = $searchModel->searchSearch ( $_GET );
        Logs::writeLogs('综合查询-保险业务');
        return $this->render('insurancesearch',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tab' => $_GET['tab'],
            'class' => $class,
            'begindate' => $_GET['begindate'],
            'enddate' => $_GET['enddate'],
            'params' => $_GET,
        ]);
    }

    public function actionLoaninfo()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $searchModel = new loanSearch();
        $params = Yii::$app->request->queryParams;
        $whereArray = Farms::getManagementArea()['id'];
        if (!isset($params ['loanSearch'] ['management_area'])) {
            $params ['loanSearch'] ['management_area'] = $whereArray;
        }

        if(!isset($params['loanSearch']['lock']))
            $params['loanSearch']['lock'] = 1;
//    	$params['loanSearch']['year'] = User::getYear();
        $params['loanSearch']['state'] = 1;

        $dataProvider = $searchModel->search ( $params );
        Logs::writeLogs('首页板块-贷款信息');
        return $this->render('loaninfo',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLoansearch($tab,$class,$begindate,$enddate)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        if($tab !== $class) {
            return $this->redirect ([$tab.'search',
                'tab' => $tab,
                'class' => $tab,
                'begindate' => strtotime($_GET['begindate']),
                'enddate' => strtotime($_GET['enddate']),
// 					$class =>['management_area' =>  $_GET['management_area']],
            ]);
        }
        $searchModel = new loanSearch();
        if(!is_numeric($_GET['begindate']))
            $_GET['begindate'] = strtotime($_GET['begindate']);
        if(!is_numeric($_GET['enddate']))
            $_GET['enddate'] = strtotime($_GET['enddate']);

        $dataProvider = $searchModel->searchIndex ( $_GET );
        Logs::writeLogs('结合查询-贷款信息');
        return $this->render('loansearch',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tab' => $_GET['tab'],
            'class' => $class,
            'begindate' => $_GET['begindate'],
            'enddate' => $_GET['enddate'],
            'params' => $_GET,
        ]);
    }

    public function actionProjectapplicationinfo()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $where = Farms::getManagementArea()['id'];
        $searchModel = new projectapplicationSearch();
        $params = Yii::$app->request->queryParams;
        $params ['projectapplicationSearch'] ['state'] = 1;
        $params ['projectapplicationSearch'] ['year'] = User::getYear();
        // 管理区域是否是数组
        if (empty($params['projectapplicationSearch']['management_area'])) {
            $params ['projectapplicationSearch'] ['management_area'] = $where;
        }
//		$params['begindate'] = Theyear::getYeartime()[0];
//		$params['enddate'] = Theyear::getYeartime()[1];
        $dataProvider = $searchModel->search ( $params );

        // 如果选择多个区域, 默认为空
        if (is_array($searchModel->management_area)) {
            $searchModel->management_area = null;
        }

        //固定资产信息列表
        $fixedSearch = new FixedSearch();
        $fparams = Yii::$app->request->queryParams;
        if (empty($fparams['FixedSearch']['management_area'])) {
            $fparams ['FixedSearch'] ['management_area'] = $where;
        }
        $dataFixed = $fixedSearch->search ( $fparams );

        //农机列表
        $machineSearch = new MachineoffarmSearch();
        $mparams = Yii::$app->request->queryParams;
        if (empty($mparams['MachineoffarmSearch']['management_area'])) {
            $mparams ['MachineoffarmSearch'] ['management_area'] = $where;
        }
        $dataMachine = $machineSearch->search ( $mparams );
        Logs::writeLogs('首页十大板块-项目申报');
        return $this->render('projectapplicationinfo', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
            'fixedSearch' => $fixedSearch,
            'dataFixed' => $dataFixed,
            'fparams' => $fparams,
            'dataMachine' => $dataMachine,
            'machineSearch' => $machineSearch,
            'mparams' => $mparams,
        ]);
    }

    public function actionProjectapplicationsearch($tab,$class,$begindate,$enddate)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        if($tab !== $class) {
            return $this->redirect ([$tab.'search',
                'tab' => $tab,
                'class' => $tab,
                'begindate' => strtotime($_GET['begindate']),
                'enddate' => strtotime($_GET['enddate']),
// 					$class =>['management_area' =>  $_GET['management_area']],
            ]);
        }
        $searchModel = new projectapplicationSearch();
        if(!is_numeric($_GET['begindate']))
            $_GET['begindate'] = strtotime($_GET['begindate']);
        if(!is_numeric($_GET['enddate']))
            $_GET['enddate'] = strtotime($_GET['enddate']);

        $dataProvider = $searchModel->searchIndex ( $_GET );
        Logs::writeLogs('综合查询-项目');
        return $this->render('projectapplicationsearch',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tab' => $_GET['tab'],
            'class' => $class,
            'begindate' => $_GET['begindate'],
            'enddate' => $_GET['enddate'],
            'params' => $_GET,
        ]);
    }

    public function actionYieldsinfo()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        //往年信息
        $searchModelLast = new plantingstructurecheckSearch();
        $paramsLast = Yii::$app->request->queryParams;
        $whereArray = Farms::getManagementArea()['id'];
        if (empty($paramsLast['plantingstructurecheckSearch']['management_area'])) {
            $paramsLast ['plantingstructurecheckSearch'] ['management_area'] = $whereArray;
        }
//        if (is_array($searchModelLast->management_area)) {
//            $searchModelLast->management_area = null;
//        }
        $paramsLast ['plantingstructurecheckSearch'] ['year'] = User::getLastYear();
        $dataProviderLast = $searchModelLast->searchIndex ( $paramsLast );


        $salesSearchLast = new salesSearch();
        $salesparamsLast = Yii::$app->request->queryParams;
//        $salesparams['farmsSearch']['state'] = [1,2,3,4,5];
        if (empty($salesparamsLast['salesSearch']['management_area'])) {
            $salesparamsLast ['salesSearch'] ['management_area'] = $whereArray;
        }
//        if (is_array($salesSearchLast->management_area)) {
//            $salesSearchLast->management_area = null;
//        }
        $salesparamsLast['salesSearch']['year'] = User::getLastYear();
        $salesDataLast = $salesSearchLast->searchIndex ( $salesparamsLast );


        //当年信息
        $searchModel = new plantingstructurecheckSearch();
        $params = Yii::$app->request->queryParams;
        $whereArray = Farms::getManagementArea()['id'];
        if (empty($params['plantingstructurecheckSearch']['management_area'])) {
            $params ['plantingstructurecheckSearch'] ['management_area'] = $whereArray;
        }
//        if (is_array($searchModel->management_area)) {
//            $searchModel->management_area = null;
//        }
        $params ['plantingstructurecheckSearch'] ['year'] = User::getYear();
        $dataProvider = $searchModel->searchIndex ( $params );


        $salesSearch = new salesSearch();
        $salesparams = Yii::$app->request->queryParams;
//        $salesparams['farmsSearch']['state'] = [1,2,3,4,5];
        if (empty($salesparams['salesSearch']['management_area'])) {
            $salesparams ['salesSearch'] ['management_area'] = $whereArray;
        }
//        if (is_array($salesSearch->management_area)) {
//            $salesSearch->management_area = null;
//        }
        $salesparams['salesSearch']['year'] = User::getYear();
        $salesData = $salesSearch->searchIndex ( $salesparams );

        Logs::writeLogs('首页十大板块-产品产量');
        return $this->render('yieldsinfo',[
            'searchModelLast' => $searchModelLast,
            'dataProviderLast' => $dataProviderLast,
            'paramsLast' => $paramsLast,
            'salesSearchLast' => $salesSearchLast,
            'salesDataLast' => $salesDataLast,
            'salesparamsLast' => $salesparamsLast,

            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
            'salesSearch' => $salesSearch,
            'salesData' => $salesData,
            'salesparams' => $salesparams,
        ]);
    }

    public function actionYieldssearch($tab,$class,$begindate,$enddate)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        if($tab !== $class) {
            return $this->redirect ([$tab.'search',
                'tab' => $tab,
                'class' => $tab,
                'begindate' => strtotime($_GET['begindate']),
                'enddate' => strtotime($_GET['enddate']),
// 					$class =>['management_area' =>  $_GET['management_area']],
            ]);
        }
        $searchModel = new plantingstructurecheckSearch();
        if(!is_numeric($_GET['begindate']))
            $_GET['begindate'] = strtotime($_GET['begindate']);
        if(!is_numeric($_GET['enddate']))
            $_GET['enddate'] = strtotime($_GET['enddate']);

        $dataProvider = $searchModel->searchIndex ( $_GET );
        Logs::writeLogs('综合查询-产品产量');
        return $this->render('yieldssearch',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tab' => $_GET['tab'],
            'class' => $class,
            'begindate' => $_GET['begindate'],
            'enddate' => $_GET['enddate'],
            'params' => $_GET,
        ]);
    }

    public function actionSalessearch($tab,$class,$begindate,$enddate)
    {
        if($tab !== $class) {
            return $this->redirect ([$tab.'search',
                'tab' => $tab,
                'class' => $tab,
                'begindate' => strtotime($_GET['begindate']),
                'enddate' => strtotime($_GET['enddate']),
// 					$class =>['management_area' =>  $_GET['management_area']],
            ]);
        }
        $searchModel = new salesSearch();
        if(!is_numeric($_GET['begindate']))
            $_GET['begindate'] = strtotime($_GET['begindate']);
        if(!is_numeric($_GET['enddate']))
            $_GET['enddate'] = strtotime($_GET['enddate']);
// 		var_dump($_GET);exit;
        $dataProvider = $searchModel->searchSearch ( $_GET );
        Logs::writeLogs('综合查询-销售信息');
        return $this->render('salessearch',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tab' => $_GET['tab'],
            'class' => $class,
            'begindate' => $_GET['begindate'],
            'enddate' => $_GET['enddate'],
            'params' => $_GET,
        ]);
    }
}