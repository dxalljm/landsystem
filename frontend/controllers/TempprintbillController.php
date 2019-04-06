<?php

namespace frontend\controllers;

use frontend\models\logSearch;
use Yii;
use app\models\Tempprintbill;
use frontend\models\tempprintbillSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\helpers\MoneyFormat;
use app\models\Logs;
use app\models\Collection;
use app\models\Farms;
use app\models\PlantPrice;
use app\models\ManagementArea;
//use yii\web\User;
use app\models\User;
use app\models\Collectionsum;

/**
 * TempprintbillController implements the CRUD actions for Tempprintbill model.
 */
class TempprintbillController extends Controller
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
     * Lists all Tempprintbill models.
     * @return mixed
     */
    public function actionTempprintbillindex($begindate=null,$enddate=null)
    {
        $searchModel = new tempprintbillSearch();
        $params = Yii::$app->request->queryParams;
        $params['tempprintbillSearch']['state'] = 0;
		if(empty($begindate))
			$begindate = date('Y').'-01-01 00:00:01';
		else {
			$begindate = $begindate.' 00:00:01';
//     		$params['collectionSearch']['state'] = 1;
		}
		if(empty($enddate))
			$enddate = date('Y-m-d').'23:59:59' ;
		else
			$enddate = $enddate.' 23:59:59';
//     	var_dump($begindate);exit;
		$params['begindate'] = strtotime($begindate);
		$params['enddate'] = strtotime($enddate);
        $dataProvider = $searchModel->search($params);
        $create_at = Tempprintbill::find()->orderBy('id DESC','LIMIT=1')->one()['create_at'];
//		$billSum = Tempprintbill::find()->where(['state'=>0])->sum('amountofmoneys');
		//var_dump($billSum);
		Logs::writeLogs('财务收费明细');
        return $this->render('tempprintbillindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
//        	'billSum' => $billSum,
        	'create_at' => $create_at,
            'begindate' => $params['begindate'],
            'enddate' => $params['enddate'],
        ]);
    }

	public function actionCollectiontotemp()
	{
		$temps = Tempprintbill::find()->all();
		foreach($temps as $temp) {
			$model = Tempprintbill::findOne($temp['id']);
			switch($model->farms_id) {
				case -1:
					$model->farmstate = -1;
					$model->save();
					break;
				case 0:
					$model->farmstate = 2;
					$model->save();
					break;
				default:
					$model->farmstate = Farms::find()->where(['id'=>$model->farms_id])->one()['state'];
					$model->save();
			}
		}
		echo 'finished';
	}

	//报废发票撤销
	public function actionTempprintbillcacle($id)
	{
		$model = $this->findModel($id);
		$collection = Collection::findOne($model->collection_id);
		$collection->real_income_amount = bcsub($collection->real_income_amount,$model->amountofmoney,2);
		$collection->measure = bcsub($collection->measure,$model->measure,2);
		$collection->ypayarea = $model->measure;
		$collection->ypaymoney = $model->amountofmoney;
		if($collection->owe) {
			$collection->owe = bcsub($collection->ypaymoney,$model->amountofmoney,2);
		} else {
			$collection->owe = 0.00;
		}
		$collection->dckpay = 0;
		$collection->state = 0;
		$collection->save();
		Logs::writeLogs('撤销报废发票相关业务',$collection);
		return $this->redirect(['tempprintbillscrap']);
	}

	public function actionTempprintbilltoxls($begindate,$enddate)
	{
		set_time_limit ( 0 );
		require_once '../../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
//		unset($_GET[1]['page']);
//		unset($_GET[1]['per-page']);
//     	var_dump($_GET);exit;
		$searchModel = new tempprintbillSearch();
		$params = Yii::$app->request->queryParams;
		$params['tempprintbillSearch']['state'] = 0;
		$params['begindate'] = $begindate;
		$params['enddate'] = $enddate;

		$dataProvider = $searchModel->search($params);
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1','承包费收缴情况统计表');
		$baseRow = 4;
		$row=0;
		foreach($data as $r => $dataRow) {
			$farm = Farms::findOne($dataRow['farms_id']);
//			var_dump($farm->farmname);exit;
			$row = $baseRow + $r;
			$state = '';
//			if ($dataRow['real_income_amount'] !== 0) {
				$objPHPExcel->getActiveSheet()->insertNewRowBefore($row, 1);

				$objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $r + 1)
					->setCellValue('B' . $row, ManagementArea::findOne($dataRow['management_area'])->areaname)
					->setCellValue('C' . $row, $farm['farmname'])
					->setCellValue('D' . $row, $farm['contractnumber'])
					->setCellValue('E' . $row, $farm['farmername'])
					->setCellValue('F' . $row, $farm['contractarea'])
					->setCellValue('G' . $row, $farm['accountnumber'])
					->setCellValue('H' . $row, '30')
					->setCellValue('I' . $row, $farm['contractarea']*30)
//			var_dump($dataRow['update_at'] > strtotime('2016-12-24 23:59:59'));exit;
//				if ($dataRow['update_at'] > strtotime('2016-12-26 23:59:59')) {
					->setCellValue('J' . $row, $dataRow['amountofmoneys'])
						->setCellValue('K' . $row, $dataRow['measure'])
						->setCellValue('L' . $row, $dataRow['amountofmoneys'])
						->setCellValue('M' . $row, date('Y年m月d日',$dataRow['update_at']))
						->setCellValue('N' . $row, '2016')
						->setCellValue('O' . $row, '未缴纳');
//				} else {
//					$objPHPExcel->getActiveSheet()->setCellValue('J' . $row, $dataRow['real_income_amount'])
//						->setCellValue('K' . $row, $dataRow['ypayarea'])
//						->setCellValue('L' . $row, $dataRow['owe'])
//						->setCellValue('M' . $row, date('Y年m月d日', $dataRow['update_at']))
//						->setCellValue('N' . $row, $dataRow['payyear']);
//					if ($dataRow['state'] == 0) {
//						$state = '未缴纳';
//					}
//					if ($dataRow['state'] == 1) {
//						$state = '已缴纳';
//					}
//					if ($dataRow['state'] == 2) {
//						$state = '部分缴纳';
//					}
//					$objPHPExcel->getActiveSheet()->setCellValue('O' . $row, $state);
//				}
//			}
// var_dump($r);
// var_dump($dataRow);
		}
// 		exit;
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
		$filename = iconv("utf-8","gb2312//IGNORE",'collection_xls/2016_tempprintbill.xls');
		$objWriter->save($filename);
		Logs::writeLogs('生成财务收费明细报表('.$filename.')');
		return $this->render('tempprintbilltoxls',['filename'=>$filename]);
	}

	public function actionTempprintbillindexadmin($begindate=null,$enddate=null)
	{
		$searchModel = new tempprintbillSearch();
		$params = Yii::$app->request->queryParams;
		$params['tempprintbillSearch']['state'] = 0;
		if(empty($begindate))
			$begindate = date('Y').'-01-01 00:00:01';
		else {
			$begindate = $begindate.' 00:00:01';
//     		$params['collectionSearch']['state'] = 1;
		}
		if(empty($enddate))
			$enddate = date('Y-m-d').'23:59:59' ;
		else
			$enddate = $enddate.' 23:59:59';
//     	var_dump($begindate);exit;
		$params['begindate'] = strtotime($begindate);
		$params['enddate'] = strtotime($enddate);
		$dataProvider = $searchModel->search($params);
		$create_at = Tempprintbill::find()->orderBy('id DESC','LIMIT=1')->one()['create_at'];
		$billSum = Tempprintbill::find()->where(['state'=>0])->sum('amountofmoneys');
		//var_dump($billSum);
		return $this->render('tempprintbillindexadmin', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'billSum' => $billSum,
			'create_at' => $create_at,
			'begindate' => $params['begindate'],
			'enddate' => $params['enddate'],
		]);
	}

	//修正收缴错误年份
	public function actionTempprintbillfixyear($id)
	{
		$model = Tempprintbill::findOne($id);
		$farm = Farms::find()->where(['id'=>$model->farms_id])->one();
		$collectionModel = Collection::findOne($model->collection_id);
		if ($model->load(Yii::$app->request->post())) {
			$collectionNow = Collection::findOne($model->collection_id);
			$collectionlate = Collection::find()->where(['farms_id'=>$model->farms_id,'payyear'=>$model->year])->one();
			$model->collection_id = $collectionlate['id'];
			$lateModel = Collection::findOne($collectionlate['id']);
			$lateModel->real_income_amount = $collectionNow->real_income_amount;
			$collectionNow->real_income_amount = 0.0;
			$lateModel->ypayarea = $collectionNow->ypayarea;
			$lateModel->ypaymoney = $collectionNow->ypaymoney;
			$lateModel->measure = $collectionNow->measure;
			$lateModel->dckpay = 1;
			$lateModel->state = 1;
			$lateModel->nonumber = $collectionNow->nonumber;
			$lateModel->update_at = $collectionNow->update_at;
			$lateModel->owe = $collectionNow->owe;

			$collectionNow->ypayyear = '';
			$collectionNow->ypayarea = $collectionlate['ypayarea'];
			$collectionNow->ypaymoney = $collectionlate['ypaymoney'];
			$collectionNow->measure = $collectionlate['measure'];
			$collectionNow->dckpay = 0;
			$collectionNow->state = 0;
			$collectionNow->nonumber = '';
			$collectionNow->update_at = $collectionNow->create_at;
			$collectionNow->owe = 0.0;
			$collectionNow->save();
			$lateModel->save();
			$model->save();
//			var_dump($lateModel);exit;
		}
		return $this->render('tempprintbillfixyear',[
			'model' => $model,
			'farm' => $farm,
			'collectionModel' => $collectionModel,
		]);
	}

    public function actionTempprintbillscrap()
    {
    	$searchModel = new tempprintbillSearch();
    	$params = Yii::$app->request->queryParams;
    	$params['tempprintbillSearch']['state'] = 1;
    
    	$dataProvider = $searchModel->search($params);
    	$create_at = Tempprintbill::find()->orderBy('id DESC','LIMIT=1')->one()['create_at'];
    	//$billSum = Tempprintbill::find()->where(['state'=>1])->sum('amountofmoneys');
    	//var_dump($billSum);
		Logs::writeLogs('报废票据列表');
    	return $this->render('tempprintbillscrap', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			//'billSum' => $billSum,
    			'create_at' => $create_at,
    	]);
    }
    
    /**
     * Displays a single Tempprintbill model.
     * @param integer $id
     * @return mixed
     */
    public function actionTempprintbillview($id)
    {
    	$model = $this->findModel($id);
//		$collectionModel = Collection::findOne($model->collection_id);
    	$farm = Farms::find()->where(['id'=>$model->farms_id])->one();
    	//     	var_dump($farm);exit;
		Logs::writeLogs('查看票据',$model);
    	return $this->render('tempprintbillview', [
    			'model' => $model,
    			'farm' => $farm,
    	]);
    }
    public function actionTempprintbillview2($id)
    {
    	$model = $this->findModel($id);
    	$farm = Farms::find()->where(['id'=>$model->farms_id])->one();
    	//     	var_dump($farm);exit;
		Logs::writeLogs('查看票据',$model);
    	return $this->render('tempprintbillview2', [
    			'model' => $model,
    			'farm' => $farm,
    	]);
    }
    public function actionTempprintbillnoview($id)
    {
        $model = $this->findModel($id);
        $farm = Farms::find()->where(['id'=>$model->farms_id])->one();
        //     	var_dump($farm);exit;
		Logs::writeLogs('查看票据',$model);
        return $this->render('tempprintbillnoview', [
            'model' => $model,
            'farm' => $farm,
        ]);
    }

    public function actionTempprintbillsview($id)
    {
    	$model = $this->findModel($id);
//		$collectionModel = Collection::findOne($model->collection_id);
    	$farm = Farms::find()->where(['id'=>$model->farms_id])->one();
//     	var_dump($farm);exit;
		Logs::writeLogs('查看票据',$model);
    	return $this->render('tempprintbillsview', [
    			'model' => $model,
//				'collectionModel' => $collectionModel,
    			'farm' => $farm,
    	]);
    }

    public function actionTempprintbillsearch()
    {
    	
    }
    
    /**
     * Creates a new Tempprintbill model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionTempprintbillcreate($id)
    {
        $model = new Tempprintbill();
		$nonumber = Tempprintbill::find()->orderBy('id DESC','LIMIT=1')->one()['nonumber'];
		$collectionModel = Collection::findOne($id);
//        var_dump($collectionModel);exit;
		$collectionModel->dckpay = 1;
        $collectionModel->state = 1;

		$farm =  Farms::find()->where(['id'=>$collectionModel->farms_id])->one();
		$model->farmername = $farm->farmername;
		$model->measure = $collectionModel->measure;
		$model->standard = PlantPrice::find()->where(['years'=>$collectionModel->ypayyear])->one()['price'];
		$model->amountofmoney = $collectionModel->real_income_amount;
		$model->bigamountofmoney = MoneyFormat::cny($collectionModel->real_income_amount);
		$model->amountofmoneys = $collectionModel->real_income_amount;
		$model->farms_id = $collectionModel->farms_id;
        $model->management_area = $collectionModel->management_area;
        $model->accountnumber = $farm->accountnumber;
        $model->collection_id = $id;
		$model->farmstate = $farm['state'];
		//exit;
        if ($model->load(Yii::$app->request->post())) {
        	$model->create_at = time();
        	$model->update_at = $model->create_at;
			if(strtotime($_POST['Tempprintbill']['create_at']) == strtotime(date('Y-m-d'))) {
				$model->kptime = $model->create_at;
			} else {
				$model->kptime = strtotime($_POST['Tempprintbill']['create_at']);
			}
			$model->year = $collectionModel->payyear;
			$model->iscq = $collectionModel->iscq;
//			var_dump($model);exit;
        	$model->save();
			Logs::writeLogs('创建收费明细',$model);
            $collectionModel->nonumber = $model->nonumber;
            $collectionModel->update_at = $model->create_at;
            if($collectionModel->owe > 0.0) {
                $collectionModel->state = 2;
            }
            $collectionModel->save();
//            if($collectionModel->save()) {
//				Logs::writeLogs('更新收费信息',$collectionModel);
//            	$collectionsum = Collectionsum::find()->where(['management_area'=>$collectionModel->management_area,'year'=>$collectionModel->payyear])->one();
//				$sumModel = Collectionsum::findOne($collectionsum['id']);
//
//            	$amounts_receivable  = ( float )bcsub($sumModel->allsum , $collectionModel->real_income_amount ,2);
//            	$real_income_amount  = ( float )bcadd($sumModel->realsum, $collectionModel->real_income_amount ,2);
//            	$sumModel->allsum = $amounts_receivable;
//            	$sumModel->realsum = $real_income_amount;
//            	$sumModel->save();
//				Logs::writeLogs('更新收费合计信息',$sumModel);
//            	$all = Collectionsum::find()->where(['management_area'=>0,'year'=>$collectionModel->payyear])->one();
//				$allModel = Collectionsum::findOne($all['id']);
//            	$allModel->allsum = Collectionsum::find()->where(['management_area'=>[1,2,3,4,5,6,7],'year'=>$collectionModel->payyear])->sum('allsum');
//            	$allModel->realsum = Collectionsum::find()->where(['management_area'=>[1,2,3,4,5,6,7],'year'=>$collectionModel->payyear])->sum('realsum');
//            	$allModel->save();
//				Logs::writeLogs('更新收费合计信息',$allModel);
//            }
//            $farmModel = Farms::findOne($collectionModel->farms_id);
//            $farmModel->accountnumber = yii::$app->request->post('accountnumber');
//            $farmModel->save();
//        	var_dump($model->getErrors());exit;
            return $this->redirect(['tempprintbillview', 'id' => $model->id]);
        } else {
            return $this->render('tempprintbillcreate', [
                'model' => $model,
            	'nonumber' => ++$nonumber,
            	'collectionModel' => $collectionModel,
            	'farm' => $farm,
            ]);
        }
    }

    public function actionTempprintbillnocontract()
    {
        $model = new Tempprintbill();

        $nonumber = Tempprintbill::find()->orderBy('id DESC','LIMIT=1')->one()['nonumber'];
        if ($model->load(Yii::$app->request->post())) {
            $model->farms_id = 0;
            $model->create_at = strtotime($model->create_at.' '.date("H:m:s"));
            $model->update_at = $model->create_at;
			$model->kptime = $model->create_at;
			$model->year = User::getYear();
            
//            $collectionModel = new Collection();
//            $collectionModel->dckpay = 1;
//            $collectionModel->state = 1;
//            $collectionModel->farms_id = 0;
//            $collectionModel->real_income_amount = MoneyFormat::toNumber($model->amountofmoney);
//            $collectionModel->amounts_receivable = $model->standard * $model->measure;
//            $collectionModel->payyear = User::getYear();
//            $collectionModel->ypayyear = date('Y');
//            $collectionModel->measure = $model->measure;
//            $collectionModel->management_area = $model->management_area;
//            $collectionModel->create_at = $model->create_at;
//            $collectionModel->update_at = $model->update_at;
//            $collectionModel->nonumber = $model->nonumber;
////			$collectionModel->kptime = (int)$model->create_at;
////			$collectionModel->year = date('Y');
//            $collectionModel->save();
//            Logs::writeLogs('创建收费数据信息',$collectionModel);
//            $model->collection_id = $collectionModel->id;
            $model->save();
            
//            var_dump($collectionModel->getErrors());exit;
            $new = $model->attributes;
            Logs::writeLogs('新增票据打印',$model);
            return $this->redirect(['tempprintbill/tempprintbillview2','id'=>$model->id]);
        } else {
            return $this->render('tempprintbillnocontract', [
                'model' => $model,
                'nonumber' => ++$nonumber,
            ]);
        }
    }
    
    public function actionTempprintbillcq()
    {
    	$id = [0=>'',1=>'',2=>''];
    	$model = new Tempprintbill();
    	$nonumber = Tempprintbill::find()->orderBy('id DESC','LIMIT=1')->one()['nonumber'];
    	if ($model->load(Yii::$app->request->post())) {
//			var_dump($_POST);exit;
//     		var_dump($model);exit;
    		$Temp = Yii::$app->request->post('temp');
    		$tempCount = count($Temp['measure']);
//     		var_dump($Temp);exit;
    		for ($i=0;$i<$tempCount;$i++) {
    			if($Temp['measure'][$i]) {
	    			$model2 = new Tempprintbill();
	    			$model2->farmername = $model->farmername;
	    			$model2->farms_id = -1;
					$model2->iscq = 1;
	    			$model2->create_at = time();
	    			$model2->update_at = $model2->create_at;
					if(strtotime($_POST['Tempprintbill']['create_at']) == strtotime(date('Y-m-d'))) {
						$model2->kptime = $model2->create_at;
					} else {
						$model2->kptime = strtotime($_POST['Tempprintbill']['create_at']);
					}
	    			$model2->year = $Temp['year'][$i];
	    			$model2->measure = $Temp['measure'][$i];
	    			$model2->standard = $Temp['standard'][$i];
	    			$model2->amountofmoney = $Temp['amountofmoney'][$i];
	    			$model2->amountofmoneys = $model->amountofmoneys;
	    			$model2->bigamountofmoney = $model->bigamountofmoney;
	    			$model2->nonumber = $model->nonumber;
	    			$model2->management_area = $model->management_area;
	    			$model2->accountnumber = $model->accountnumber;
	    			$model2->remarks = $model->remarks;
	    			
// 	    			$collectionModel = new Collection();
// 	    			$collectionModel->dckpay = 1;
// 	    			$collectionModel->state = 1;
// 	    			$collectionModel->farms_id = 0;
// 	    			$collectionModel->real_income_amount = MoneyFormat::toNumber($model->amountofmoney);
// 	    			$collectionModel->amounts_receivable = $model2->standard * $model2->measure;
// 	    			$collectionModel->payyear = User::getYear();
// 	    			$collectionModel->ypayyear = date('Y');
// 	    			$collectionModel->measure = $model2->measure;
// 	    			$collectionModel->management_area = $model2->management_area;
// 	    			$collectionModel->create_at = $model2->create_at;
// 	    			$collectionModel->update_at = $model2->update_at;
// 	    			$collectionModel->nonumber = $model2->nonumber;
	    			
// 	    			$collectionModel->save();
	    			
// 	    			$model2->collection_id = $collectionModel->id;
//					var_dump($model2);exit;
	    			$model2->save();
					Logs::writeLogs('新增陈欠数据',$model2);
	    			$id[$i] = $model2->id;
    			}
    			
    		}
//     		var_dump($id);exit;
    		//            var_dump($collectionModel->getErrors());exit;
    		Logs::writeLogs('新增票据打印',$model);
    		return $this->redirect(['tempprintbill/tempprintbillcqview','id0'=>$id[0],'id1'=>$id[1],'id2'=>$id[2]]);
    	} else {
    		return $this->render('tempprintbillcq', [
    				'model' => $model,
    				'nonumber' => ++$nonumber,
    		]);
    	}
    }
    
    public function actionTempprintbillcqview($id0,$id1,$id2)
    {
    	$model0 = $this->findModel($id0);
    	if($id1)
    		$model1 = $this->findModel($id1);
    	else 
    		$model1 = '';
    	if($id2)
    		$model2 = $this->findModel($id2);
    	else
    		$model2 = '';
    	$farm = Farms::find()->where(['id'=>$model0->farms_id])->one();
    	//     	var_dump($farm);exit;
		Logs::writeLogs('查看陈欠数据',$model0);
		Logs::writeLogs('查看陈欠数据',$model1);
		Logs::writeLogs('查看陈欠数据',$model2);
    	return $this->render('tempprintbillcqview', [
    			'model0' => $model0,
    			'model1' => $model1,
    			'model2' => $model2,
    			'farm' => $farm,
    	]);
    }
    
    public function actionFormat($number)
    {
    	$cny = MoneyFormat::cny($number);
    	$numformat = $number;
    	echo json_encode(['cny'=>$cny,'num'=>$numformat]);
    }
    
    /**
     * Updates an existing Tempprintbill model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTempprintbillupdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->state = 1;
        	$model->save();
        	Logs::writeLogs('报废',$model);
            return $this->redirect(['tempprintbillindex']);
        } else {
            return $this->render('tempprintbillupdate', [
                'model' => $model,
            	'nonumber' => $model->nonumber,
            ]);
        }
    }

    public function actionTempprintbillnewprint($id)
    {
        $oldmodel = $this->findModel($id);
        $old = $oldmodel->attributes;
        $model = new Tempprintbill();
        $model->attributes = $old;
        $model->nonumber = '';
        $model->state = 0;
        $model->save();
        $farm = Farms::findOne($model->farms_id);
        if ($model->load(Yii::$app->request->post())) {
            $model->create_at = time();
            $model->update_at = $model->create_at;
            $model->save();
            Logs::writeLogs('更改发票号',$model);
            return $this->redirect(['tempprintbillview','id'=>$model->id]);
        } else {
            return $this->render('tempprintbillnewprint', [
                'model' => $model,
                'farm' => $farm,
            ]);
        }
    }
    /**
     * Deletes an existing Tempprintbill model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTempprintbilldelete($id)
    {
        $model = $this->findModel($id);
        $model->state = 1;
        $model->save();
		
        return $this->redirect(['tempprintbillindex']);
    }

    /**
     * Finds the Tempprintbill model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tempprintbill the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tempprintbill::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	public function actionAlladdyear()
	{
		$tempprint = Tempprintbill::find()->all();
		foreach ($tempprint as $temp) {
			$model = Tempprintbill::findOne($temp['id']);
			if($model->year == '')
				$model->year = '2016';
			$model->save();
		}
		echo 'finished';
	}
}
