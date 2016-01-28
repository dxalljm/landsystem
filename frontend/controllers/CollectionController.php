<?php

namespace frontend\controllers;

use Yii;
use app\models\Collection;
use frontend\models\collectionSearch;
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
use yii\bootstrap\Collapse;
use app\models\ManagementArea;
use app\models\Lease;
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
    public function actionCollectionindex($farms_id = null,$year=null)
    {
    	if($year === null)
    		$year = Theyear::findOne(1)['years'];
        $searchModel = new collectionSearch();
        $params = Yii::$app->request->queryParams;
        if(!empty($farms_id))
        	$params['collectionSearch']['farms_id'] = $farms_id;
        $params['collectionSearch']['dckpay'] = 0;
//         var_dump($params);
//         exit;
        $dataProvider = $searchModel->search($params);
        
		Logs::writeLog('进入承包费收缴页面');
        return $this->render('collectionindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'years' => $year,
        ]);
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
    
    	Logs::writeLog('进入承包费打印页面');
    	return $this->render('collectionprint', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'years' => $year,
    	]);
    }
    
    public function actionCollectionsend($farms_id,$year = NULL)
    {
    	if(empty($year))
    		$year = Theyear::getYear();
    	$farm = Farms::find()->where(['id'=>$farms_id])->one();
   		$collection = Collection::find()->where(['farms_id'=>$farms_id,'ypayyear'=>$year])->one();
    	//var_dump($collection);
    	$old_area = 0;
    	if($collection) {
    		$model = $this->findModel($collection->id);
    		$old = $model->attributes;
    		$old_real_income_amount = $collection->real_income_amount;
    		$old_ypaymoney = $collection->ypaymoney;
    		$old_area = sprintf("%.2f",$collection->real_income_amount/PlantPrice::find()->where(['years'=>$model->ypayyear])->one()['price']);
    	}
    	else {
    		$model = new Collection();
    		$old_real_income_amount = 0;
    		$old = '';
    	}
    	
        
        $collectiondataProvider = Collection::find()->where(['farms_id'=>$farms_id])->andWhere('ypayyear<='.$year)->all();
        $owe = $model->getOwe($farms_id,$year);
        
//         if($owe)
//         	$collectiondataProvider['owe'] = $owe+$collectiondataProvider['amounts_receivable']-$collectiondataProvider['real_income_amount'];
//         else
//         	$collectiondataProvider['owe'] = $collectiondataProvider['ypaymoney'];
        //$collectiondataProvider = Collection::findAll("");
        //print_r($collectiondataProvider);

        if ($model->load(Yii::$app->request->post())) {
        	
        	$model->amounts_receivable = $model->getAR($year);
        	
        	$model->real_income_amount = (float)bcadd((float)$model->real_income_amount,(float)$old_real_income_amount,2);
        	$model->ypayarea = $model->getYpayarea($year, $model->real_income_amount);
        	$model->ypaymoney = $model->getYpaymoney($year, $model->real_income_amount);
        	//$owe = $model->getOwe($farmerid, $farmsid,$year);
        	if($owe)
        		$model->owe = $owe+$model->amounts_receivable-$model->real_income_amount;
        	else
        		$model->owe = $model->ypaymoney;
			$model->create_at = time();
			$model->update_at = time();
			$model->dckpay = 0;
			$model->management_area = Farms::find()->where(['id'=>$farms_id])->one()['management_area'];
// 			var_dump($model->real_income_amount);
// 			exit;
        	$model->save();
        	//var_dump($model->getErrors());
        	$newAttr = $model->attributes;
        	Logs::writeLog('收缴一笔承包费',$model->id,$old,$newAttr);
        	return $this->redirect(['collectionfinished','farms_id'=>$farms_id]);
        	//exit;
        } else {
            return $this->render('collectionsend', [
                'model' => $model,
            	'year' => $year,
            	'farms_id' => $farms_id,
            	'farm' => $farm,
            	'collectiondataProvider' => $collectiondataProvider,
            	'owe' => $owe,
            	'overarea' => $old_area,
            ]);
        }
    }
    //地产科发送完成提示页面
    public function actionCollectionfinished($farms_id)
    {
    	return $this->render('collectionfinished',['farms_id'=>$farms_id]);
    }
    //承包费陈欠	
    public function actionCollectioncq($year=null)
    {
    	if($year === null)
    		$year = Theyear::findOne(1)['years'];
    	$model = new farmerSearch();
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
    public function actionCollectionview($id)
    {
    	Logs::writeLog('查看一笔收缴信息',$id);
        return $this->render('collectionview', [
            'model' => $this->findModel($id),
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
    		$old_area = sprintf("%.2f",$collection->real_income_amount/30/PlantPrice::find()->where(['years'=>$model->ypayyear])->one()['price']);
    	}
    	else {
    		$model = new Collection();
    		$old_real_income_amount = 0;
    		$old = '';
    	}
    	
        
        $collectiondataProvider = Collection::find()->where(['farms_id'=>$farms_id])->andWhere('ypayyear<='.$year)->all();
        $owe = $model->getOwe($cardid, $farms_id,$year);
//         if($owe)
//         	$collectiondataProvider['owe'] = $owe+$collectiondataProvider['amounts_receivable']-$collectiondataProvider['real_income_amount'];
//         else
//         	$collectiondataProvider['owe'] = $collectiondataProvider['ypaymoney'];
        //$collectiondataProvider = Collection::findAll("");
        //print_r($collectiondataProvider);

        if ($model->load(Yii::$app->request->post())) {
        	
        	$model->amounts_receivable = $model->getAR($year);
        	$model->real_income_amount = $model->real_income_amount + $old_real_income_amount;
        	$model->ypayarea = $model->getYpayarea($year, $model->real_income_amount);
        	$model->ypaymoney = $model->getYpaymoney($year, $model->real_income_amount);
        	$model->measure = Farms::getMeasure($farms_id);
        	//$owe = $model->getOwe($farmerid, $farmsid,$year);
        	if($owe)
        		$model->owe = $owe+$model->amounts_receivable-$model->real_income_amount;
        	else
        		$model->owe = $model->ypaymoney;
			$model->create_at = time();
			$model->update_at = time();
        	$model->save();
        	//var_dump($model->getErrors());
        	$newAttr = $model->attributes;
        	Logs::writeLog('收缴一笔承包费',$model->id,$old,$newAttr);
        	return $this->redirect(['collectioncreate', 
        			'model' => $model,
    					'year' => $year,
    					'cardid' => $cardid,
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
            	'cardid' => $cardid,
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
    	$owe = $model->getOwe($cardid, $farms_id,$year);
    	//         if($owe)
    		//         	$collectiondataProvider['owe'] = $owe+$collectiondataProvider['amounts_receivable']-$collectiondataProvider['real_income_amount'];
    		//         else
    			//         	$collectiondataProvider['owe'] = $collectiondataProvider['ypaymoney'];
    		//$collectiondataProvider = Collection::findAll("");
    		//print_r($collectiondataProvider);
    		if ($model->load(Yii::$app->request->post())) {
    			$model->amounts_receivable = $model->getAR($year);
    			$model->real_income_amount = $model->real_income_amount + $old_real_income_amount;
    			$model->ypayarea = $model->getYpayarea($year, $model->real_income_amount);
    			$model->ypaymoney = $model->getYpaymoney($year, $model->real_income_amount);
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
    public function actionGetar($year)
    {
    	$result = new Collection();
    	echo json_encode($result->getAR($year));
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
    		return $this->redirect ([$_GET['tab'].'/'.$_GET['tab'].'search',
    				'tab' => $_GET['tab'],
    				'begindate' => strtotime($_GET['begindate']),
    				'enddate' => strtotime($_GET['enddate']),
    				$_GET['tab'].'Search' => ['management_area'=>$_GET['management_area']],
    		]);
    	} 
    	$searchModel = new collectionSearch();
		if(!is_numeric($_GET['begindate']))
			 $_GET['begindate'] = strtotime($_GET['begindate']);
		if(!is_numeric($_GET['enddate']))
			 $_GET['enddate'] = strtotime($_GET['enddate']);

    	$dataProvider = $searchModel->searchIndex ( $_GET );
    	return $this->render('collectionsearch',[
	    			'searchModel' => $searchModel,
	    			'dataProvider' => $dataProvider,
	    			'tab' => $_GET['tab'],
	    			'begindate' => $_GET['begindate'],
	    			'enddate' => $_GET['enddate'],
	    			'params' => $_GET,
    	]);    	
    }
    
    public function actionCollectioninfo()
    {
    	$searchModel = new collectionSearch();
    	$params = Yii::$app->request->queryParams;
    	$whereArray = Farms::getManagementArea()['id'];
    	if (empty($params['collectionSearch']['management_area'])) {
			$params ['collectionSearch'] ['management_area'] = $whereArray;
		}

		$params['collectionSearch']['dckpay'] = 1;
		$dataProvider = $searchModel->search ( $params );
    	if (is_array($searchModel->management_area)) {
			$searchModel->management_area = null;
		}
    	
    	
    	return $this->render('collectioninfo',[
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'params' => $params,
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
}
