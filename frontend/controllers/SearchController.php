<?php

namespace frontend\controllers;

use app\models\CollectionElasticSearch;
use app\models\Collectionsum;
use app\models\Insurance;
use app\models\Plantingstructureyearfarmsid;
use app\models\Plantingstructureyearfarmsidplan;
use console\models\Huinong;
use Yii;
use yii\helpers\ArrayHelper;
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
use app\models\MenuToUser;
use frontend\helpers\arraySearch;
use app\models\Reviewprocess;
use app\models\Yieldbase;
use frontend\helpers\MoneyFormat;
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

	

	public function actionSearchindex($tab = '',$class='',$begindate = '',$enddate = '',$params = '')
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
		Logs::writeLogs('综合查询');
		$menu = MenuToUser::getUserMenu();
		$getDate = Theyear::formatDate($begindate,$enddate);
		if(isset($_GET['tab'])) {
//			var_dump($_GET['tab']);
    		if($_GET['tab'] == 'parmpt')
    			return $this->render('searchindex');
			$whereDate = Theyear::formatDate($_GET['begindate'],$_GET['enddate']);
			$array[] = 'teninfo/'.$_GET['tab'].'search';
			$array['tab'] = $_GET['tab'];
			$array['class'] = $_GET['tab'];
			$array['begindate'] = $whereDate['begindate'];
			$array['enddate'] = $whereDate['enddate'];
//			if($_GET['tab'] == 'yields')
//				$array['plantingstructureSearch']['management_area'] = $_GET['management_area'];
//			else
//				$array[$_GET['tab'].'Search']['management_area'] = $_GET['management_area'];
			return $this->redirect ($array);
		} else {
//			switch (Yii::$app->user->identity->template) {
//				case 'default':
					return $this->render('searchindex',['tab'=>$tab,'class'=>$class,'begindate'=>$getDate['begindate'],'enddate'=>$getDate['enddate'],'params'=>$_GET]);
//					break;
//				case 'template2018':
//					return $this->render('searchindex2018',['tab'=>$tab,'class'=>$class,'begindate'=>$getDate['begindate'],'enddate'=>$getDate['enddate'],'params'=>$_GET]);
//					break;
//			}

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
//     	$get = Yii::$app->request->post();
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
// 	    			$plantname = Plant::find()->where(['id'=>$v['plant_id']])->one()['typename'];
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
	public function whereToStr($condition) {
		if (! is_array ( $condition )) {
			return $condition;
		}

		if (! isset ( $condition [0] )) {
			// hash format: 'column1' => 'value1', 'column2' => 'value2', ...
			foreach ( $condition as $name => $value ) {
				if ($this->isEmpty ( $value )) {
					unset ( $condition [$name] );
				}
			}
			return $condition;
		}
		$operator = array_shift ( $condition );
		switch (strtoupper ( $operator )) {
			case 'NOT' :
			case 'AND' :
			case 'OR' :
				foreach ( $condition as $i => $operand ) {
					$subcondition = $this->whereToStr ( $operand );
					if ($this->isEmpty ( $subcondition )) {
						unset ( $condition [$i] );
					} else {
						$condition [$i] = $subcondition;
					}
				}
				break;
			case 'LIKE' :
				return [
					$condition [0] => $condition [1]
				];
				break;
			default :
				$condition = null;
		}
		return $condition;
	}
	public function actionSearchdemo($modelClass,$where,$command)
	{
		$eC = CollectionElasticSearch::find()->addAggregate('_search',['size'=>0,'aggs'=>["intraday_return"=>["sum"=>["field"=>"ypaymoney"]]]]);
		var_dump($eC);
	}

	public function actionGetprocesscount($id)
	{
		$result = '';
		switch ($id) {
			case 'all':
				$result = Reviewprocess::getUserProcessAllCount();
				break;
			case 0:
				$result = Reviewprocess::getUserProcessCountHtml($id);
				break;
			case 2:
				$result = Reviewprocess::getUserProcessCountHtml($id);
				break;
			case 4:
				$result = Reviewprocess::getUserProcessCountHtml($id);
				break;
			case 6:
				$result = Reviewprocess::getUserProcessCountHtml($id);
				break;
			case 8:
				$result = Reviewprocess::getUserProcessCountHtml($id);
				break;
			case 9:
				$result = Reviewprocess::getUserProcessCountHtml($id);
				break;
		}
		$data = ['data'=>$result];
		echo json_encode($data,true);
	}
	
	public function actionSearch($modelClass,$where,$command,$andwhere = null,$orderby = null,$groupby=null)
	{
		set_time_limit(0);
		$result = '';
        $condition = null;
		$andcondition = null;
		$str = explode('-',$command);
		$classname = "app\\models\\" . $modelClass;
		$data = $classname::find();

		if($where !== null) {
//		    var_dump($where);
			$condition = json_decode($where, true);
//			var_dump($condition);
			if (!empty($condition)) {
				$data->andFilterWhere($condition);
			}
		}
		if(!empty($andwhere)) {
			$andcondition = json_decode($andwhere, true);
			if(is_array($andcondition)) {
				foreach ($andcondition as $key => $value) {
					if ($key == 'state') {
						if ($value == '') {
							unset($andcondition[$key]);
						}
					}
				}
			}
			if(is_array($andwhere)) {
//            var_dump($condition);exit;
				$data->andFilterWhere($andcondition);
			} else {
				$data->andWhere($andcondition);
			}
        }
//		var_dump($data->where);exit;
		if(!empty($orderby)) {
			$data->orderBy($orderby);
		}
		if(!empty($groupby)) {
			$data->groupBy($groupby);
		}
//		var_dump($str);exit;
//		var_dump(Collection::find()->andFilterWhere($condition)->andFilterWhere(['state'=>1])->all());
//		var_dump($data->where);exit;

// 		if($str[1] == 'ypaymoney' or $str[1] == 'owe') {
// 			$data->andFilterWhere(['state'=>1]);
// 		}

		switch ($str[0]) {
			case 'count':
				switch ($str[1]) {
					case 'farmer_id':
						$result = Search::getFarmerrows($modelClass,$condition,$andcondition);
						break;
					case 'farms_id':
						$result = Search::getFarmsrows($modelClass,$condition,$andcondition);
						break;
					default:
						$result = $data->andWhere($str[1].'>0')->groupBy($str[1])->count();
				}
				break;
			case 'countunique':
				
					$result = $data->groupBy('breedtype_id')->count();
				
				break;
			case 'mulsum':
				$arr = explode('*',$str[1]);
				$result = sprintf('%.2f',$data->sum($arr[0]) * $data->sum($arr[1]));
				break;
			case 'sum':
				switch ($str[1]) {
					case 'amounts_receivable':
						$result = sprintf ( "%.2f",$data->groupBy('farms_id')->sum($str[1]));
						break;
					case 'owe':
						$sum = $data->sum($str[1]);
						$collections = Collection::find()->andFilterWhere($condition)->andFilterWhere(['state'=>2])->groupBy('farms_id')->all();
						$dsum = 0.0;
						foreach ($collections as $collection) {
//						    var_dump($this->dMoney($condition,$collection['farms_id'],'owe'));
							$dsum += $this->dMoney($condition,$collection['farms_id'],'owe');
						}
//						var_dump($sum);
//						var_dump($dsum);
						$result = bcsub($sum,$dsum,2);
						break;
					case 'insured':
						$sum = 0;
						$data = Insurance::find()->where($condition)->all();
						$plant_id = $this->getInsured($condition);
						if(!empty($plant_id)) {
							foreach ($data as $value) {
								$array = explode(',', $value['insured']);
								foreach ($array as $val) {
									$arr = explode('-',$val);
									if ($arr[0] == $plant_id) {
										$sum += $arr[1];
									}
								}

							}
						} else {
							$sum = Insurance::find()->where($condition)->sum('insuredarea');
						}
						$result = sprintf('%.2f',$sum);
						break;
					case 'ypaymoney':
						$sum = $data->sum($str[1]);
						$collections = Collection::find()->andFilterWhere($condition)->andFilterWhere(['state'=>2])->groupBy('farms_id')->all();
						$dsum = 0.0;
						foreach ($collections as $collection) {
							$dsum += $this->dMoney($condition,$collection['farms_id'],'ypaymoney');
						}
						$result = bcsub($sum,$dsum,2);
						break;
					case 'contractarea':
						if($modelClass == 'Farms') {
							$result = sprintf("%.2f", $data->sum($str[1]));
						} elseif($modelClass == 'Collection') {
							$result = sprintf ( "%.2f",$data->andWhere('amounts_receivable>0')->groupBy('farms_id')->sum($str[1]));
						} else {
							$allid = ArrayHelper::map($data->all(),'id','farms_id');
//							var_dump($allid);
							sort($allid);
//							var_dump(array_unique($allid));
							$result = sprintf ( "%.2f",Farms::find()->where(['id'=>array_unique($allid)])->sum($str[1]));
						}

						break;
					default:
						if(isset($str[2])) {
							if($str[2]) {
								$result = sprintf("%.2f", $data->sum($str[1]));
							} else {
								$result = 0.00;
							}
						} else {
							$result = sprintf("%.2f", $data->sum($str[1]));
						}
				}
//				if($str[1] == 'amounts_receivable') {
//					$result = sprintf ( "%.2f",$data->groupBy('farms_id')->sum($str[1]));
//					//var_dump($data->createCommand()->getRawSql());
//				} else
//				$result = sprintf ( "%.2f",$data->sum($str[1]));
				break;
			case 'mulyieldSum':
				$sum= 0.0;
				foreach ($data->all() as $value) {
					$yield = Yieldbase::find()->where(['plant_id'=>$value['plant_id'],'year'=>$value['year']])->one()['yield'];
					if(empty($yield)) {
						$yield = 0;
					}
					$sum += bcmul($value['area'],$yield,2);
				}
				$result = MoneyFormat::num_format($sum);
				break;
			case 'mulhuinongSum':
				$sum = 0.0;
				foreach ($data->all() as $value) {
					$yield = \app\models\Huinong::find()->where(['typeid'=>$value['plant_id'],'year'=>$value['year']])->one()['subsidiesmoney'];
					if(empty($yield)) {
						$yield = 0;
					}
					$sum += bcmul($value['area'],$yield,2);
				}
				$result = MoneyFormat::num_format($sum);
				break;
		}

		if($result)
			echo json_encode($result);
		else
			echo json_encode(0);
	}

	public function actionPlantfinished($where)
	{
		$data = Plantingstructureyearfarmsid::find();
//		var_dump($data->andFilterWhere(['management_area'=>4])->count());
		if(!empty($where)) {
			$condition = json_decode($where, true);
				$data->andFilterWhere($condition);
		}
		$all = $data->count();
		echo json_encode(['finished'=>$data->andFilterWhere(['isfinished'=>1])->count(),'all'=>$all]);
	}
	public function actionPlantplanfinished($where)
	{
		$data = Plantingstructureyearfarmsidplan::find();
//		var_dump($data->andFilterWhere(['management_area'=>4])->count());
		if(!empty($where)) {
			$condition = json_decode($where, true);
			$data->andFilterWhere($condition);
		}
		$all = $data->count();
		echo json_encode(['finished'=>$data->andFilterWhere(['isfinished'=>1])->count(),'all'=>$all]);
	}
	public function actionSearch_bak($modelClass,$where,$command,$owhere = null,$andWhere = null)
	{

		$classname = "app\\models\\" . $modelClass;
		$data = $classname::find();
		if($where !== 'null') {
			$where2 = json_decode($where, true);
			$condition = $this->filterCondition($where2);
			if ($condition !== []) {
				$data->where($condition);
			}
		}
		if(!empty($owhere)) {
			$owhere2 = json_decode($owhere, true);
		}
		$andWhere2 = [];
		if(!empty($andWhere)) {
			$andWhere2 = json_decode($andWhere, true);
		}
		$arrayData = arraySearch::find($data->all())->search();
		$str = explode('-',$command);
// 		var_dump($str);
		switch ($str[0]) {
			case 'count':
				if(count($str) > 1) {
					if($andWhere2) {
						echo json_encode($arrayData->andWhere($andWhere2)->count($str[1]));
					} else {
						echo json_encode($arrayData->count($str[1]));
					}
				}
				else {
					if($andWhere2) {
						echo json_encode($arrayData->andWhere($andWhere2)->count());
					} else {
						echo json_encode($arrayData->count());
					}
				}
				break;
			case 'sum':
				if($andWhere2) {

					echo json_encode($arrayData->andWhere($andWhere2)->sum($str[1]));
				} else {
					echo json_encode($arrayData->sum($str[1]));
				}
				break;
			case 'mulyieldSum':
				echo json_encode(MoneyFormat::num_format($arrayData->mulyieldSum($str[1],$str[2])));
				break;
			case 'mulSum':
				echo json_encode(MoneyFormat::num_format($arrayData->mulSum([$str[1],$str[2]])));
				break;
		}
	}

	protected function filterCondition($condition)
	{
		if (!is_array($condition)) {
			return $condition;
		}

		if (!isset($condition[0])) {
			// hash format: 'column1' => 'value1', 'column2' => 'value2', ...
			foreach ($condition as $name => $value) {
				if ($this->isEmpty($value)) {
					unset($condition[$name]);
				}
			}
			return $condition;
		}

		// operator format: operator, operand 1, operand 2, ...

		$operator = array_shift($condition);

		switch (strtoupper($operator)) {
			case 'NOT':
			case 'AND':
			case 'OR':
				foreach ($condition as $i => $operand) {
					$subCondition = $this->filterCondition($operand);
					if ($this->isEmpty($subCondition)) {
						unset($condition[$i]);
					} else {
						$condition[$i] = $subCondition;
					}
				}

				if (empty($condition)) {
					return [];
				}
				break;
			case 'BETWEEN':
			case 'NOT BETWEEN':
				if (array_key_exists(1, $condition) && array_key_exists(2, $condition)) {
					if ($this->isEmpty($condition[1]) || $this->isEmpty($condition[2])) {
						return [];
					}
				}
				break;
			default:
				if (array_key_exists(1, $condition) && $this->isEmpty($condition[1])) {
					return [];
				}
		}

		array_unshift($condition, $operator);

		return $condition;
	}

	protected function isEmpty($value)
	{
		return $value === '' || $value === [] || $value === null || is_string($value) && trim($value) === '';
	}

	protected function dMoney($condition,$farms_id,$str) {
//        var_dump($condition);
		$collecitons = Collection::find()->andFilterWhere(['farms_id'=>$farms_id,'payyear'=>User::getYear()])->all();
		$is = Collection::find()->andFilterWhere($condition)->andFilterWhere(['farms_id'=>$farms_id])->orderBy('id desc')->one();
//		var_dump($farms_id);
//		var_dump($is);
		$sum = 0.00;
		if($is['state'] == 1) {
			foreach ($collecitons as $colleciton) {
				if($colleciton['state'] == 2) {
					$sum += $colleciton[$str];
				}
			}
		}
//		var_dump($sum);
		if($is['state'] == 2) {
//            var_dump(count($collecitons));
            if (count($collecitons) > 1) {
//                var_dump(count($collecitons));
                foreach ($collecitons as $colleciton) {
                    if ($colleciton['state'] == 2) {
                        $sum += $colleciton[$str];
                    }
                }
            }
        }
//		var_dump($sum);

		return $sum;
	}

	public function getInsured($condition)
	{
		foreach ($condition as $value) {
			if(is_array($value)) {
				if(count($value) >= 3) {
					if(is_string($value[1])) {
						if ($value[1] == 'insured') {
							$len = strlen($value[2]);
							return substr($value[2],0,$len-1);
						} else {
							return '';
						}
					} else {
						return $this->getInsured($value);
					}
				}
			}
		}
	}
}
