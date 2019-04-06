<?php

namespace frontend\controllers;

use app\models\Tempprintbill;
use Yii;
use app\models\Collection;
use frontend\models\collectionSearch;
use yii\debug\models\search\Log;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use frontend\models\farmsSearch;
use app\models\Farmer;
use frontend\models\farmerSearch;
use app\models\Theyear;
use app\models\PlantPrice;
use app\models\Logs;
use app\models\User;
use app\models\Department;
use frontend\models\tempprintbillSearch;
use app\models\ManagementArea;
/**
 * CollectionController implements the CRUD actions for Collection model.
 */
class CollectionController extends Controller
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
    /**
     * Lists all Collection models.
     * @return mixed
     */
    public function actionCollectionindex()
    {
		//地产科提交重置
//    	Collection::dckpayReset();

//    	$year = Theyear::findOne(1)['years'];
        $searchModel = new collectionSearch();
        $params = Yii::$app->request->queryParams;
//        $params['collectionSearch']['dckpay'] = 1;
//		$params['collectionSearch']['state'] = 0;
//         var_dump($params);
//         exit;
        $dataProvider = $searchModel->searchCollection($params);
        
		Logs::writeLogs('进入承包费收缴页面');
        return $this->render('collectionindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
//        	'years' => $year,
        ]);
    }

	public function actionCollectionlocation($farms_id,$id)
	{
		$tempModel = Tempprintbill::findOne($id);
		$farm = Farms::findOne($farms_id);
		$collectionModel = Collection::find()->where(['payyear'=>$tempModel->year,'farms_id'=>$farms_id])->one();
		if($collectionModel) {
			$collectionModel2 = Collection::findOne($collectionModel['id']);
			$collectionModel2->update_at = time();
			$collectionModel2->ypayarea = 0.0;
			$collectionModel2->ypaymoney = 0.0;
			$collectionModel2->real_income_amount = $tempModel->amountofmoney;
			$collectionModel2->measure = $tempModel->measure;
			$collectionModel2->owe = 0.0;
			$collectionModel2->dckpay = 1;
			$collectionModel2->state = 1;
			$collectionModel2->save();
		} else {
			$collectionModel2 = new Collection();
			$collectionModel2->create_at = time();
			$collectionModel2->update_at = $collectionModel2->create_at;
			$collectionModel2->payyear = $tempModel->year;
			$collectionModel2->farms_id = $farms_id;
			$collectionModel2->amounts_receivable = $tempModel->amountofmoney;
			$collectionModel2->real_income_amount = $tempModel->amountofmoney;
			$collectionModel2->measure = $tempModel->measure;
			$collectionModel2->ypayarea = 0.0;
			$collectionModel2->ypaymoney = 0.0;
			$collectionModel2->owe = 0.0;
			$collectionModel2->dckpay = 1;
			$collectionModel2->state = 1;
			$collectionModel2->management_area = $farm->management_area;
			$collectionModel2->save();
		}
		$tempModel->farms_id = $farms_id;
		$tempModel->collection_id = $collectionModel2->id;
		$tempModel->save();
		return $this->redirect(['collectionnoinfo']);
	}

	public function actionCollectionfarmlist($id)
	{
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
		Logs::writeLogs ( '重新定位续费信息-农场列表' );
		return $this->render ( 'collectionfarmlist', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'params' => $params,
			'id' => $id,
		] );
	}

	public function actionCollectionreset($id)
	{
		$model = $this->findModel($id);
		$collection = Collection::find()->where(['farms_id'=>$model->farms_id,'payyear'=>$model->ypayyear])->count();
		if($collection == 1) {

			$model->dckpay = 0;
			$model->ypayarea = Farms::find()->where(['id'=>$model->farms_id])->one()['contractarea'];
			$model->ypaymoney = $model->amounts_receivable;
			$model->real_income_amount = null;
			if($model->payyear == Theyear::getYear())
				$model->ypayyear = null;
			$model->measure = null;
			if($model->payyear == date('Y'))
				$model->owe = 0.0;
			else
				$model->owe = $model->amounts_receivable;
			$model->save();
			Logs::writeLogs('更新缴费数据',$model);
		} else {
			$model->delete();
			Logs::writeLogs('删除缴费数据',$model);
		}

		return $this->redirect(['collectionsend','farms_id'=>$model->farms_id]);
	}
	public function actionCollectionreset2($farms_id,$payyear)
	{
		$collection = Collection::find()->where(['farms_id'=>$farms_id,'payyear'=>$payyear])->one();
		$model = $this->findModel($collection->id);
		
		if($collection == 1) {
			$model->dckpay = 0;
			$model->ypayarea = Farms::find()->where(['id'=>$model->farms_id])->one()['contractarea'];
			$model->ypaymoney = $model->amounts_receivable;
			$model->real_income_amount = null;
			$model->ypayyear = null;
			$model->measure = null;
			$model->owe = 0.0;
			$model->save();
			Logs::writeLogs('更新缴费数据',$model);
		}
	
		return $this->redirect(['collectionsend','farms_id'=>$model->farms_id]);
	}
    public function actionCollectionprint($farms_id = null,$year=null)
    {
    	if($year === null)
    		$year = Theyear::findOne(1)['years'];
    	$searchModel = new collectionSearch();
    	$params = Yii::$app->request->queryParams;
    	if(!empty($farms_id))
    		$params['collectionSearch']['farms_id'] = $farms_id;
    	$params['collectionSearch']['dckpay'] = 1;
    	//         var_dump($params);
    	//         exit;
    	$dataProvider = $searchModel->search($params);
    
    	Logs::writeLogs('进入承包费打印页面');
    	return $this->render('collectionprint', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'years' => $year,
    	]);
    }
    //陈欠追缴
	public function actionCollectionrecovered($id)
	{
		$model = Collection::findOne($id);
		$old = $model->attributes;
		$model->dckpay = 1;
		$model->real_income_amount = $model->ypaymoney;
		$model->measure = $model->ypayarea;
		$model->ypaymoney = 0.0;
		$model->ypayarea = 0.0;
		$model->owe = 0.0;
		$model->save();
		Logs::writeLogs('收缴陈欠承包费', $model);
		return $this->redirect(['collectionfinished', 'farms_id' => $model->farms_id]);

	}

    public function actionCollectionsend($farms_id,$year = NULL)
    {
    	if(empty($year))
    		$year = User::getYear();
//		var_dump($year);exit;
    	$farm = Farms::find()->where(['id'=>$farms_id])->one();
   		$collection = Collection::find()->where(['farms_id'=>$farms_id,'payyear'=>$year])->orderBy('id DESC')->one();
//		var_dump($collection);exit;
		$price = PlantPrice::find()->where(['years'=>$year])->one();
		if(!$price) {
			return $this->redirect(['error/error','msg'=>'对不起,财务还没有设置'.$year.'年度的缴费基数。请确认财务设置了缴费基数再试!']);
		}
//		else {
//			$prices = PlantPrice::find()->all();
//			foreach ($prices as $p) {
//				$c = Collection::find()->where(['farms_id'=>$farms_id,'payyear'=>$p['years']])->orderBy('id DESC')->one();
//				if(empty($c)) {
//					$collectionModel = new Collection();
//					$collectionModel->create_at = time();
//					$collectionModel->update_at = $collectionModel->create_at;
//					$collectionModel->payyear = $p['years'];
//					$collectionModel->farms_id = $farms_id;
//					$collectionModel->amounts_receivable = $collectionModel->getAR($year, $farm['id']);
//					$collectionModel->real_income_amount = 0.0;
//					$collectionModel->ypayarea = $farm['contractarea'];
//					$collectionModel->ypaymoney = bcmul($farm['contractarea'],$p['price'], 2);
//					if($p['years'] !== date('Y')) {
//						$collectionModel->owe = $collectionModel->ypaymoney;
//						$collectionModel->ypayyear = $p['years'];
//					} else {
//// 						$collectionModel->owe = $collectionModel->owe - $collectionModel->real_income_amount;
//						$collectionModel->measure = 0;
//					}
//					$collectionModel->dckpay = 0;
//					$collectionModel->state = 0;
//					$collectionModel->management_area = $farm['management_area'];
//
//					$collectionModel->save();
//					Logs::writeLogs('新增缴费数据',$collectionModel);
//				}
//			}

//		}
// 		var_dump($collection['id']);exit;
		$model = $this->findModel($collection['id']);

		$old_area = 0;
		$old = $model->attributes;
		$old_real_income_amount = $collection->real_income_amount;
		$old_ypaymoney = $collection->ypaymoney;
		$old_area = sprintf("%.2f", $collection->real_income_amount / PlantPrice::find()->where(['years' => $model->payyear])->one()['price']);

		$collectiondataProvider = Collection::find()->where(['farms_id' => $farms_id])->andWhere('ypayyear<=' . $year)->orderBy('ypayyear desc')->all();
		if ($model->load(Yii::$app->request->post())) {
//			var_dump($model);
//			var_dump($collection);exit;
			if($collection->real_income_amount > 0.0) {
				$collectionModel = new Collection();
				$collectionModel->create_at = time();
				$collectionModel->update_at = $collectionModel->create_at;
				$collectionModel->payyear = $year;
				$collectionModel->farms_id = $farm['id'];
				$collectionModel->amounts_receivable = $collectionModel->getAR($year, $farm['id']);
				$collectionModel->real_income_amount = $model->real_income_amount;
				$collectionModel->ypayarea = abs(bcsub($collection['ypayarea'] , $model->measure,2));
				$collectionModel->ypaymoney = abs(bcsub($collection['ypaymoney'] , $model->real_income_amount,2));
				$collectionModel->owe = $collectionModel->ypaymoney;
				$collectionModel->measure = $model->measure;
				$collectionModel->ypayyear = $year;
				$collectionModel->dckpay = 1;
				$collectionModel->state = 0;
				$collectionModel->management_area = $farm['management_area'];
				if ($collection->owe) {
					$collectionModel->owe = $collection->owe - $collectionModel->real_income_amount;
				}
				$collectionModel->save();
				Logs::writeLogs('新增缴费数据',$collectionModel);
//				$newmodel = $this->findModel($collectionModel->id);
//				$newmodel->amounts_receivable = $model->amounts_receivable;
//				$newmodel->real_income_amount = $model->real_income_amount;
//				$newmodel->ypayarea = abs(bcsub($model->ypayarea , $newmodel->ypayarea,2));
//				$newmodel->ypaymoney = abs(bcsub($model->ypaymoney , $newmodel->ypaymoney,2));
//				$newmodel->real_income_amount = $model->real_income_amount;
//				$newmodel->owe = $model->ypaymoney;
//				$newmodel->ypayyear = $year;
//				$newmodel->update_at = time();
//				$newmodel->dckpay = 1;
//				$newmodel->state = 0;
////				$model->management_area = Farms::find()->where(['id' => $farms_id])->one()['management_area'];
//				$newmodel->measure = abs((float)$model->measure);
//				$newmodel->save();
			} else {
				$model->amounts_receivable = Collection::getAR($year, $farms_id);
				$model->real_income_amount = (float)bcadd((float)$model->real_income_amount, (float)$old_real_income_amount, 2);
				$model->ypayarea = abs(bcsub($collection['ypayarea'],$model->measure,2));
				$model->ypaymoney = abs(bcsub($model->amounts_receivable, $model->real_income_amount,2));
//				$model->real_income_amount = $model->real_income_amount;
				$model->owe = $model->ypaymoney;
				//		$model->create_at = time();
				$model->update_at = time();
				$model->dckpay = 1;
				$model->state = 0;
				$model->management_area = Farms::find()->where(['id' => $farms_id])->one()['management_area'];
				$model->measure = abs((float)$model->measure);
				$model->save();
				Logs::writeLogs('更新缴费数据',$model);
			}

//						var_dump($model->getErrors());exit;
			$newAttr = $model->attributes;
			Logs::writeLogs('收缴一笔承包费', $model);
				return $this->redirect(['collectionfinished', 'farms_id' => $farms_id]);
		} else {
			return $this->render('collectionsend', [
				'model' => $model,
				'year' => $year,
				'farms_id' => $farms_id,
				'farm' => $farm,
				'collectiondataProvider' => $collectiondataProvider,
				'overarea' => $old_area,
			]);
		}
    }
    //地产科发送完成提示页面
    public function actionCollectionfinished($farms_id)
    {
		Logs::writeLog('地产科提交缴费页面',$farms_id);
    	return $this->render('collectionfinished',['farms_id'=>$farms_id]);
    }
    //承包费陈欠	
    public function actionCollectioncq($year=null)
    {
    	if($year === null)
    		$year = User::getYear();
//     	$model = new farmerSearch();
    	return $this->render('collectioncq', [
    			'model' => $model,
    	]);
    }
	//通过js获取当前农场的应收与实收金额
    public function actionGetamounts()
    {
    	$real = 0;
    	$amounts = 0;
    	$dep_id = User::findByUsername(yii::$app->user->identity->username)['department_id'];
    	$departmentData = Department::find()->where(['id'=>$dep_id])->one();
    	$whereArray = explode(',', $departmentData['membership']);
    	$farms = Farms::find()->where(['management_area'=>$whereArray])->all();
    	foreach ($farms as $value) {
    		if(is_array($value)) {
    			foreach ($value as $k => $v) {
    				$arrayID[] = $v['id'];
    			}
    		} else {
    			$arrayID[] = $value['id'];
    		}
    	}
    	 
    	$collections = Collection::find()->where(['farms_id'=>$arrayID])->all();
    	foreach ($collections as $value) {
    		$real += $value['real_income_amount'];
    		$amounts += $value['amounts_receivable'];
    	}
    	$resault = ['real'=>$real,'amounts'=>$amounts];
    	echo json_encode(['status' => 1, 'count' => $resault]);
		Yii::$app->end();
    }
    //确认承包费收缴并打印发票
    public function actionCollectionconfirm($id)
    {
    	Logs::writeLog('确认并打印',$id);
    	$model = $this->findModel($id);
    	
    	$model->dckpay = 1;
    	$model->save();
    	return $this->render('collectionview', [
    			'model' => $model,
    	]);
    }
    /**
     * Displays a single Collection model.
     * @param integer $id
     * @return mixed
     */
    public function actionCollectionview($farms_id)
    {
    	$farm = Farms::findOne($farms_id);
    	$collectiondataProvider = Collection::find()->where(['farms_id'=>$farms_id])->andWhere('ypayyear<='.date('Y'))->all();
    	Logs::writeLog('查看'.$farm->farmname.'收缴信息',$farms_id);
        return $this->render('collectionview', [
            'collectiondataProvider' => $collectiondataProvider,
        	'farm' => $farm,
        ]);
    }
    
    /**
     * Creates a new Collection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCollectioncreate($farms_id)
    {
    	//$this->layout='@app/views/layouts/nomain.php';
    	//$year = Theyear::findOne(1)['years'];
    	$farms = Farms::find()->where(['id'=>$farms_id])->one();
    	$collection = Collection::find()->where(['farms_id'=>$farms_id])->one();
    	//var_dump($collection);
    	$old_area = 0;
    	if($collection) {
    		$model = $this->findModel($collection->id);
    		$old = $model->attributes;
    		$old_real_income_amount = $collection->real_income_amount;
    		$old_ypaymoney = $collection->ypaymoney;
    		$old_area = sprintf("%.2f",$collection->real_income_amount/30/PlantPrice::find()->where(['years'=>$model->payyear])->one()['price']);
    		$model->dckpay = 1;
			$model->state = 0;
    		$model->update_at = time();
    		
    	}
    	else {
    		$model = new Collection();
    		$old_real_income_amount = 0;
    		$old = '';
    	}
    	
        
        $collectiondataProvider = Collection::find()->where(['farms_id'=>$farms_id])->andWhere('payyear<='.$year)->all();
        $owe = Collection::getOwe($farms_id,$year);
//         if($owe)
//         	$collectiondataProvider['owe'] = $owe+$collectiondataProvider['amounts_receivable']-$collectiondataProvider['real_income_amount'];
//         else
//         	$collectiondataProvider['owe'] = $collectiondataProvider['ypaymoney'];
        //$collectiondataProvider = Collection::findAll("");
        //print_r($collectiondataProvider);

        if ($model->load(Yii::$app->request->post())) {
        	
        	$model->amounts_receivable = Collection::getAR($year,farms_id);
        	$model->real_income_amount = $model->real_income_amount + $old_real_income_amount;
        	$model->ypayarea = Collection::getYpayarea($year, $model->real_income_amount,$farms_id);
        	$model->ypaymoney = Collection::getYpaymoney($year, $model->real_income_amount,$farms_id);
        	$model->measure = Farms::getContractarea($farms_id);
        	//$owe = $model->getOwe($farmerid, $farmsid,$year);
        	if($owe)
        		$model->owe = $owe+$model->amounts_receivable-$model->real_income_amount;
        	else
        		$model->owe = $model->ypaymoney;
        	if($collection) {
				$model->create_at = time();
				$model->update_at = $model->create_at;
				
        	} else 
        		$model->update_at = time();
        	$model->save();

        	//var_dump($model->getErrors());
        	$newAttr = $model->attributes;
        	Logs::writeLogs('收缴承包费',$model);
        	return $this->redirect(['collectioncreate', 
        			'model' => $model,
    					'year' => $year,
    					'cardid' => $farms['cardid'],
    					'farms_id' => $farms_id,
    					'farms' => $farms,
    					'collectiondataProvider' => $collectiondataProvider,
    					'owe' => $owe,
        				'overarea' => $old_area,
        	]);
        	//exit;
        } else {
            return $this->render('collectioncreate', [
                'model' => $model,
            	'year' => $year,
            	'cardid' => $farms['cardid'],
            	'farms_id' => $farms_id,
            	'farms' => $farms,
            	'collectiondataProvider' => $collectiondataProvider,
            	'owe' => $owe,
            	'overarea' => $old_area,
            ]);
        }
    }
	
    public function actionCollectionsearch2($farms_id,$cardid)
    {
    	//$this->layout='@app/views/layouts/nomain.php';
    	$year = Theyear::findOne(1)['years'];
    	$farms = Farms::find()->where(['id'=>$farms_id])->one();
    	$collection = Collection::find()->where(['farms_id'=>$farms_id,'cardid'=>$cardid])->one();
    	//echo '<br><br><br><br><br><br><br><br><br><br><br>';
    	//var_dump($collection);
    	if($collection) {
    		$model = $this->findModel($collection->id);
    		$old_real_income_amount = $collection->real_income_amount;
    		$old_ypaymoney = $collection->ypaymoney;
    	}
    	else {
    		$model = new Collection();
    		$old_real_income_amount = 0;
    	}
    	 
    
    	$collectiondataProvider = Collection::find()->where(['farms_id'=>$farms_id,'cardid'=>$cardid])->all();
    	$plantprace = PlantPrice::find()->all();
    	$owe = Collection::getOwe($farms_id,$year);
    	//         if($owe)
    		//         	$collectiondataProvider['owe'] = $owe+$collectiondataProvider['amounts_receivable']-$collectiondataProvider['real_income_amount'];
    		//         else
    			//         	$collectiondataProvider['owe'] = $collectiondataProvider['ypaymoney'];
    		//$collectiondataProvider = Collection::findAll("");
    		//print_r($collectiondataProvider);
    		if ($model->load(Yii::$app->request->post())) {
    			$model->amounts_receivable = Collection::getAR($year,farms_id);
    			$model->real_income_amount = $model->real_income_amount + $old_real_income_amount;
    			$model->ypayarea = Collection::getYpayarea($year, $model->real_income_amount,$farms_id);
    			$model->ypaymoney = Collection::getYpaymoney($year, $model->real_income_amount,$farms_id);
    			//$owe = $model->getOwe($farmerid, $farmsid,$year);
    			if($owe)
    				$model->owe = $owe+$model->amounts_receivable-$model->real_income_amount;
    			else
    				$model->owe = $model->ypaymoney;
    			$model->create_at = time();
    			$model->update_at = time();
    			$model->save();
    			$newAttr = $model->attributes;
    			Logs::writeLog('收缴一笔承包费',$model->id,'',$newAttr);
    			return $this->redirect(['collectionsearch', 
    					'model' => $model,
    					'year' => $year,
    					'cardid' => $cardid,
    					'farms_id' => $farms_id,
    					'farms' => $farms,
    					'collectiondataProvider' => $collectiondataProvider,
    					'owe' => $owe,
						'plantpreace' => $plantprace,   				
    			]);
    			
    		} else {
    			return $this->render('collectionsearch', [
    					'model' => $model,
    					'year' => $year,
    					'cardid' => $cardid,
    					'farms_id' => $farms_id,
    					'farms' => $farms,
    					'collectiondataProvider' => $collectiondataProvider,
    					'owe' => $owe,
    					'plantpreace' => $plantprace,
    			]);
    		}
    }
   //获取年度缴费基数
    public function actionGetplantprice($formyear)
    {
    	$plantprice = PlantPrice::find()->where(['years'=>$formyear])->one();
    	echo json_encode($plantprice['price']);
    }
    //获取当年应收金额
    public function actionGetar($year,$farms_id)
    {
    	echo json_encode(Collection::getAR($year,$farms_id));
    }
    /**
     * Updates an existing Collection model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
//     public function actionCollectionupdate($id)
//     {
//         $model = $this->findModel($id);
//         $oldAttr = $model->attributes;
//         if ($model->load(Yii::$app->request->post()) && $model->save()) {
//         	$newAttr = $model->attributes;
//         	Logs::writeLog('更新农场信息',$id,$oldAttr,$newAttr);
//             return $this->redirect(['collectionview', 'id' => $model->id]);
//         } else {
//             return $this->render('collectionupdate', [
//                 'model' => $model,
//             ]);
//         }
//     }

    /**
     * Deletes an existing Collection model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
//     public function actionCollectiondelete($id)
//     {
//         $this->findModel($id)->delete();

//         return $this->redirect(['collectionindex']);
//     }

    public function actionCollectionsearch($begindate,$enddate)
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
	    			'begindate' => $_GET['begindate'],
	    			'enddate' => $_GET['enddate'],
	    			'params' => $_GET,
    			'nodata' => $nodata,
    			'noSearchModel' => $noSearchModel,
    	]);    	
    }
    
    public function actionCollectioninfo($year=null,$begindate=null,$enddate=null)
	{
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

	public function actionCollectiontoxls($year)
	{
//		var_dump($year);exit;
		set_time_limit ( 0 );
		require_once '../../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
//		unset($_GET[1]['page']);
//		unset($_GET[1]['per-page']);
//     	var_dump($_GET);exit;
		$searchModel = new collectionSearch();
		$params = Yii::$app->request->queryParams;
		$params['collectionSearch']['payyear'] = $year;
// 		$params['collectionSearch']['state'] = [0,2];
		$dataProvider = $searchModel->searchindex($params);
		$dataProvider->pagination = ['pagesize'=>0];
		$data = $dataProvider->getModels();
//     	var_dump($data);exit;
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);

		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

		date_default_timezone_set('Europe/London');

		/** PHPExcel_IOFactory */

		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load("template/collection.xls");
		$objPHPExcel->getActiveSheet()->setCellValue('A1', $year.'年度承包费收缴情况统计表');
		$baseRow = 4;
		$row=0;
		foreach ($data as $value) {
			if($value['owe']>0)
				$result[] = $value;
		}
// 		var_dump($data);exit;
		foreach($result as $r => $dataRow) {
			$farm = Farms::findOne($dataRow['farms_id']);
			$row = $baseRow + $r;
			$state = '';
			if ($dataRow['owe'] > 0) {
// 				var_dump($dataRow);
				$objPHPExcel->getActiveSheet()->insertNewRowBefore($row, 1);

				$objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $r + 1)
					->setCellValue('B' . $row, ManagementArea::findOne($dataRow['management_area'])->areaname)
					->setCellValue('C' . $row, $farm->farmname)
					->setCellValue('D' . $row, $farm->contractnumber)
					->setCellValue('E' . $row, $farm->farmername)
					->setCellValue('F' . $row, $farm->contractarea)
					->setCellValue('G' . $row, $farm->accountnumber)
					->setCellValue('H' . $row, PlantPrice::find()->where(['years' => $year])->one()['price'])
					->setCellValue('I' . $row, $dataRow['amounts_receivable']);
//			var_dump($dataRow['update_at'] > strtotime('2016-12-24 23:59:59'));exit;
// 				if ($dataRow['update_at'] > strtotime('2016-12-24 23:59:59')) {
// 					$objPHPExcel->getActiveSheet()->setCellValue('J' . $row, 0)
// 						->setCellValue('K' . $row, $farm->contractarea)
// 						->setCellValue('L' . $row, $dataRow['amounts_receivable'])
// 						->setCellValue('M' . $row, '')
// 						->setCellValue('N' . $row, $dataRow['payyear'])
// 						->setCellValue('O' . $row, '未缴纳');
// 				} else {
					$objPHPExcel->getActiveSheet()->setCellValue('J' . $row, $dataRow['real_income_amount'])
						->setCellValue('K' . $row, $dataRow['ypayarea'])
						->setCellValue('L' . $row, $dataRow['owe'])
						->setCellValue('M' . $row, date('Y年m月d日', $dataRow['update_at']))
						->setCellValue('N' . $row, $dataRow['payyear']);
					if ($dataRow['state'] == 0) {
						$state = '未缴纳';
					}
					if ($dataRow['state'] == 1) {
						$state = '已缴纳';
					}
					if ($dataRow['state'] == 2) {
						$state = '部分缴纳';
					}
					$objPHPExcel->getActiveSheet()->setCellValue('O' . $row, $state);
// 				}
			}
		}
		$hjrow = $row+1;
		$objPHPExcel->getActiveSheet()->insertNewRowBefore($hjrow,1);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$hjrow, '合计')
			->setCellValue('F'.$hjrow, '=SUM(F3:F'.$row.')')
			->setCellValue('I'.$hjrow, '=SUM(I3:I'.$row.')')
			->setCellValue('J'.$hjrow, '=SUM(J3:J'.$row.')')
			->setCellValue('K'.$hjrow, '=SUM(K3:K'.$row.')')
			->setCellValue('L'.$hjrow, '=SUM(L3:L'.$row.')');

		$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
//     	echo $this->render('insuranceprogress',['width'=>'100%']);
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = iconv("utf-8","gb2312//IGNORE",'collection_xls/'.$year.'_collection.xls');
		$objWriter->save($filename);
		Logs::writeLogs('导出承包费缴费情况(xls)');
		return $this->render('collectiontoxls',['filename'=>$filename,'year'=>$year]);

	}

	public function actionCollectionnoinfo()
	{
		$searchModel = new tempprintbillSearch();
		$collectionCount = Collection::find()->andWhere('farms_id<>0')->count();
		$params = Yii::$app->request->queryParams;
		$whereArray = Farms::getManagementArea()['id'];
// 		if (is_array($whereArray) and count($whereArray) > 1) {
// 			$searchModel->management_area = null;
// 		}
		if (empty($params['tempprintbillSearch']['management_area'])) {
			$params ['tempprintbillSearch'] ['management_area'] = $whereArray;
		}
		$params ['tempprintbillSearch'] ['state'] = 0;
		
		
		$dataProvider = $searchModel->searchIndex ( $params );
		return $this->render('collectionnoinfo',[
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'params' => $params,
			'collectionCount' => $collectionCount,
		]);

	}
    /**
     * Finds the Collection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Collection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Collection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	public function actionUpdate()
	{
		$temp = Tempprintbill::find()->all();
		foreach ($temp as $t) {
			$collection = Collection::findOne($t['collection_id']);
			if($collection) {
				if($t['update_at']) {
					$collection->update_at = $t['update_at'];
					$collection->save();
				}
			}
		}
		echo 'finished';
	}
}
