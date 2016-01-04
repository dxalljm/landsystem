<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Logs;
use app\models\Farms;
use app\models\Farmer;
use frontend\helpers;
use frontend\helpers\Pinyin;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\Plant;
use app\models\Plantingstructure;
use app\models\Collection;
use app\models\User;
use app\models\Department;
use app\models\Theyear;
use app\models\Breedinfo;
use app\models\Breedtype;
use app\models\Goodseed;
use app\models\Search;

/**
 * BankAccountController implements the CRUD actions for BankAccount model.
 */
class SearchController extends Controller
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

    
//     public function beforeAction($action)
//     {
//     	$action = Yii::$app->controller->action->id;
//     	if(\Yii::$app->user->can($action)){
//     		return true;
//     	}else{
//     		throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
//     	}
//     }

//     public function actionSearchindex()
//     {
//     	$post = Yii::$app->request->post();
//     	$Search = '';
//     	$dataProvider = [];
//     	$tab = '';
//     	if($post) {
// //     		var_dump($post);exit;
//     		$this->formatDate($post['begindate'],$post['enddate']);
//     		$managementarea = $post['managementarea'];
//     		$tab = $post['tab'];
//     		if($managementarea == 0) {
//     			$areaWhere = [1,2,3,4,5,6,7];
//     		} else 
//     			$areaWhere = $managementarea;

// 			$searchName = $tab.'Search';
//     		$searchClass = 'frontend\models\\'.$searchName;
//     		$Search = new  $searchClass();

//     		$params = Yii::$app->request->queryParams;

//     		if($tab == 'farms') {
//     			$params [$searchName] ['management_area'] = $management_area['id'];
//     		} else {
// //     			var_dump($areaWhere);exit;
//     			$arrayID = $this->getFarmsid($areaWhere);
//     			$params [$searchName] ['farms_id'] = $arrayID;
//     			$params [$searchName] ['create_at'] = $this->whereDate['begindate'];
//     			$params [$searchName] ['update_at'] = $this->whereDate['enddate'];
//     		}
//     		$dataProvider = $Search->searchIndex ( $params[$searchName] );
//     	}	
//     	return $this->render ( 'searchindex', [
//     			'searchModel' => $Search,
//     			'dataProvider' => $dataProvider,
//     			'controllername' => $tab,
//     	] );
// //     	$searchDate = date('Y年m月d日',$this->whereDate['begindate']).'—'.date('Y年m月d日',$this->whereDate['enddate']);
//     }

	

	public function actionSearchindex($tab = '',$management_area = '',$begindate = '',$enddate = '')
	{
		$post = Yii::$app->request->post();	
// 		var_dump($tab);exit;
		$getDate = Theyear::formatDate($begindate,$enddate);
		if($post) {
    		if($post['tab'] == 'parmpt')
    			return $this->render('searchindex');
			$whereDate = Theyear::formatDate($post['begindate'],$post['enddate']);
			$array[] = $post['tab'].'/'.$post['tab'].'search';
			$array['tab'] = $post['tab'];
			$array['begindate'] = $whereDate['begindate'];
			$array['enddate'] = $whereDate['enddate'];
			$array['management_area'] = $post['managementarea'];
			
			foreach (Search::getParameter($post['tab']) as $value) {
				$array[$value] = $post[$value];
			}
// 			var_dump($array);exit;
			return $this->redirect ($array);
		} else {
			return $this->render('searchindex',['tab'=>$tab,'management_area'=>$management_area,'begindate'=>$getDate['begindate'],'enddate'=>$getDate['enddate']]);
		}
	}
    
    private function getFarmsid($managment_area)
    {
    	$farms = Farms::find()->where(['management_area'=>$managment_area])->all();
    	foreach ($farms as $farm) {
    		$id[] = $farm['id'];
    	}
//     	var_dump($id);exit;
    	return $id;
    }

//     public function actionSearchindex()
//     {
//     	$post = Yii::$app->request->post();
//     	if($post)
//     		$this->formatDate($post['begindate'],$post['enddate']);
//     	else 
//     		$this->formatDate();
// // 	    $planting = $this->getPlantingstructure();
// 	    $collection = $this->getCollection();
//     	$breedinfo = $this->getBreedinfo();
//     	$loan = $this->getLoan();
// //     	$this->getFireprevention();
//     	$searchDate = date('Y年m月d日',$this->whereDate['begindate']).'—'.date('Y年m月d日',$this->whereDate['enddate']);
//     	return $this->render('searchindex',[
//     			'searchDate' => $searchDate,
// //     			'planting' => $planting,
//     			'collection' => $collection,
//     			'breedinfo' => $breedinfo,
//     			'loan' => $loan,
//     	]);
//     }
    
    public function actionSearchdemo()
    {
    	return $this->render('searchdemo');
    }
    //获取用户管理区信息
    public function getUserManagementArea()
    {
    	$departmentid = User::find ()->where ( [
    			'id' => \Yii::$app->getUser ()->id
    	] )->one ()['department_id'];
    	$departmentData = Department::find ()->where ( [
    			'id' => $departmentid
    	] )->one ();
    	$whereArray = explode ( ',', $departmentData ['membership'] );
    	return $whereArray;
    }
    
    //获取用户所在管理区的所有农场
    public function getAllFarmsID()
    {
    	$result = '';
    	$farms = Farms::find()->where(['management_area'=>$this->getUserManagementArea()])->all();
    	foreach ($farms as $value) {
    		$result[] = $value['id'];
    	}
    	return $result;
    }
    //获得指定表的所有当前用户所属管理区的农场ID
    public function getControllerData($controller) {
    	$classFile = 'app\\models\\'.$controller;
    	$result = [];
    	foreach($this->getAllFarmsID() as $value) {
    		$p = $classFile::find()->where(['farms_id'=>$value])->andFilterWhere(['between','update_at',$this->whereDate['begindate'],$this->whereDate['enddate']])->all();
    		if(!empty($p))
    			$result[$value][] = $p;
    	}
    	return $result;
    }
    
    
    
//     public function getPlantingstructure()
//     {
//     	$data = $this->getControllerData('Plantingstructure');
// // 		var_dump($planting);exit;
//     	foreach ($data as $key => $value) {
//     		foreach ($value as $val) {
//     			foreach($val as $v) {
// //     			var_dump($val[0]);exit;
// 	    			$plantname = Plant::find()->where(['id'=>$v['plant_id']])->one()['cropname'];
// 		    		$result[$plantname]['area'][] = $v['area'];
// 		    		$result[$plantname]['goodseed_id'][] = $v['goodseed_id'];
//     			}
//     		}
    		
//     	}
//     	return $result;
//     }
 
    public function getCollection()
    {
    	$data = $this->getControllerData('Collection');
    	$result = '';
    	foreach ($data as $key => $value) {
    		foreach ($value as $val) {
    			foreach($val as $v) {
		    		$result['amounts_receivable'][] = $v['amounts_receivable'];
		    		$result['real_income_amount'][] = $v['real_income_amount'];
		    		$result['q'][] = $v['amounts_receivable'] - $v['real_income_amount'];
    			}
    		}
    		
    	}
//     	var_dump($result);exit;
    	return $result;
    } 
    
    public function getBreedinfo()
    {
    	$data = $this->getControllerData('Breed');
//     	var_dump($data);exit;
    	$result = '';
    	foreach ($data as $key => $value) {
    		foreach ($value as $val) {
    			foreach($val as $v) {
    				$breedinfo = Breedinfo::find()->where(['breed_id'=>$v['id']])->all();
    				foreach ($breedinfo as $b) {
    					$breedtype = Breedtype::find()->where(['id'=>$b['breedtype_id']])->one();
    					$result[$key][$breedtype['typename']] = ['unit'=>$breedtype['unit'],'number'=>$b['number']];
    				}
    			}
    		}
    	
    	}
//     	var_dump($result);exit;
    	return $result;
    }
    
    public function getLoan()
    {
    	$data = $this->getControllerData('Loan');
    	$result = '';
   		foreach ($data as $key => $value) {
    		foreach ($value as $val) {
    			foreach($val as $v) {
	    			$result[$key] = [
	    				'mortgagebank' => $v['mortgagebank'],
	    				'mortgagemoney' => $v['mortgagemoney'],
	    				'mortgagearea' => $v['mortgagearea'],
	    			];
    			}
    		}
    	
    	}
//     	var_dump($result);exit;
    	return $result;
    }
    
    public function getFireprevention()
    {
    	$data = $this->getControllerData('Fireprevention');
    	$result = '';
    	var_dump($data);exit;
    }
}
