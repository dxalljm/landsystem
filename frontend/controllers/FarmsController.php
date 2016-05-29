<?php

namespace frontend\controllers;

use Composer\IO\NullIO;
use Yii;
use app\models\Farms;
use app\models\User;
use app\models\Department;
use frontend\models\farmsSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Theyear;
use app\models\UploadForm;
use app\models\Farmer;
use app\models\ManagementArea;
use yii\web\UploadedFile;
use frontend\models\parcelSearch;
use app\models\Parcel;
use app\models\Logs;
use app\models\CooperativeOfFarm;
use frontend\helpers\Pinyin;
use app\models\Ttpo;
use app\models\Ttpozongdi;
use app\models\MenuToUser;
use app\models\Mainmenu;
use yii\helpers\Url;
use app\models\Lease;
use app\models\Loan;
use app\models\Dispute;
use app\models\Employee;
use app\models\Plantingstructure;
use app\models\Fireprevention;
use app\models\Yields;
use app\models\Sales;
use app\models\Prevention;
use app\models\Projectapplication;
use app\models\Disaster;
use app\models\Collection;
use app\models\Breedinfo;
use app\models\Breed;
use app\models\Breedtype;
use app\models\Reviewprocess;
use app\models\Lockedinfo;
use app\models\Auditprocess;
use app\models\Contractnumber;
use app\models\Search;
use app\models\Farmermembers;
use app\models\Machineoffarm;
use app\models\elasticsearchtest;
use app\models\Farmselastic;
use app\models\Otherfarms;
use app\models\Insurance;
use app\models\Estate;
use app\models\Leader;
use app\models\Zongdioffarm;
// use PHPExcel_IOFactory;

/**
 * FarmsController implements the CRUD actions for farms model.
 */
class FarmsController extends Controller {
	public function behaviors() {
		return [ 
				'verbs' => [ 
						'class' => VerbFilter::className (),
						'actions' => [ 
								'delete' => [ 
										'post' 
								] 
						] 
				] 
		];
	}

	

	public function actionGetallaction() {
		$result = self::actionName ();
		return $result;
	}
	public function actionFarmsreplace() {
		$farms = Farms::find ()->all ();
		foreach ( $farms as $farm ) {
			$model = $this->findModel ( $farm ['id'] );
			$model->locked = 0;
			$model->save ();
		}
		return 'finished';
	}
	// public function beforeAction($action) {
	// $action = Yii::$app->controller->action->id;
	// if (\Yii::$app->user->can ( $action )) {
	// return true;
	// } else {
	// throw new \yii\web\UnauthorizedHttpException ( '对不起，您现在还没获此操作的权限' );
	// }
	// }
	/**
	 * Lists all farms models.
	 *
	 * @return mixed
	 */
	public function actionFarmsindex() {
// 		$departmentid = User::find ()->where ( [ 
// 				'id' => \Yii::$app->getUser ()->id 
// 		] )->one ()['department_id'];
// 		$departmentData = Department::find ()->where ( [ 
// 				'id' => $departmentid 
// 		] )->one ();
// 		$whereArray = explode ( ',', $departmentData ['membership'] );
		
		$searchModel = new farmsSearch ();
		
		$params = Yii::$app->request->queryParams;
// 		$params ['farmsSearch'] ['state'] = 1;
		// 管理区域是否是数组
// 		if (empty($params['farmsSearch']['mamagement_area'])) {
// 			$params ['farmsSearch'] ['management_area'] = $whereArray;
// 		}
		// var_dump($params);exit;
		$dataProvider = $searchModel->search ( $params );
		Logs::writeLog ( '农场管理' );
		return $this->render ( 'farmsindex', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider, 
				
		] );
	}
	//合同打印
	public function actionFarmscontractprint($farms_id)
	{
		$model = $this->findModel($farms_id);
// 		$model->getErrors();
		return $this->render('farmscontractprint',[
				'model' => $model,
		]);
	}
	
	public function getFarmsid() {
		$departmentid = User::find ()->where ( [ 
				'id' => \Yii::$app->getUser ()->id 
		] )->one ()['department_id'];
		$departmentData = Department::find ()->where ( [ 
				'id' => $departmentid 
		] )->one ();
		$whereArray = explode ( ',', $departmentData ['membership'] );
		$farms = Farms::find ()->where ( [ 
				'management_area' => $whereArray 
		] )->all ();
		foreach ( $farms as $value ) {
			$farmsID [] = $value ['id'];
		}
		return $farmsID;
	}
	// public function actionFarmszongdi()
	// {
	// $farms = Farms::find()->all();
	// foreach ($farms as $val) {
	// $model = $this->findModel($val['id']);
	// $model->zongdi = substr($val['zongdi'],0,strlen($val['zongdi'])-1);
	// $model->save();
	// }
	// }
	// 得到所属农场的记录数
	
	// 得到所属农场的所有面积
	// public function getfarmarea() {
	// $cacheKey = 'farmsarea-hcharts2';
	// $result = Yii::$app->cache->get ( $cacheKey );
	// if (! empty ( $result )) {
	// return $result;
	// }
	// $sum = 0;
	// $dep_id = User::findByUsername ( yii::$app->user->identity->username )['department_id'];
	// $departmentData = Department::find ()->where ( [
	// 'id' => $dep_id
	// ] )->one ();
	// $whereArray = explode ( ',', $departmentData ['membership'] );
	// $all = Farms::find ()->sum ( 'measure' );
	// foreach ( $whereArray as $value ) {
	// $resultName = ManagementArea::find ()->where ( [
	// 'id' => $value
	// ] )->one ()['areaname'];
	// $resultValue = ( float ) Farms::find ()->where ( [
	// 'management_area' => $value
	// ] )->sum ( 'measure' );
	// $result [] = [
	// $resultName,
	// $resultValue
	// ];
	// $sum += $resultValue;
	// }
	// $allvalue = $all - $sum;
	// $result [] = [
	// '其他管理区',
	// ( float ) $allvalue
	// ];
	// $jsonData = Json::encode ( [
	// 'status' => 1,
	// 'result' => $result,
	// 'total' => $all
	// ] );
	// Yii::$app->cache->set ( $cacheKey, $jsonData, 1 );
	
	// return $jsonData;
	// }
	// xls导入
	public function actionFarmsxls() {
		set_time_limit ( 0 );
		$model = new UploadForm ();
		$rows = 0;
		$area = [];
		$data = [];
// 		require_once dirname(__FILE__) . '../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
		if (Yii::$app->request->isPost) {
			
			$model->file = UploadedFile::getInstance ( $model, 'file' );
// 			var_dump($model);exit;
			$extension = $model->file->getExtension();
			if ($model->file == null)
				throw new \yii\web\UnauthorizedHttpException ( '对不起，请先选择xls文件' );
			if ($model->file && $model->validate ()) {
				
				$xlsName = time () . rand ( 100, 999 );
				$model->file->name = $xlsName;
				$model->file->saveAs ( 'uploads/' . $model->file->name . '.' . $extension );
				$path = 'uploads/' . $model->file->name . '.' . $extension;

				$loadxls = \PHPExcel_IOFactory::load($path);
				$rows = $loadxls->getActiveSheet ()->getHighestRow ();
// 				$farms = Farms::find()->all();
// 				$zongdi = [ ];
// 				$a = [];
// 				echo '<br><br><br><br><br><br><br><br>';
				
				for($i = 2; $i <= $rows; $i ++) {

					$farm = Farms::find()->where(['contractnumber'=>$loadxls->getActiveSheet()->getCell('C'.$i)->getValue()])->one();
					if($farm) {
						echo "<br><br><br><br><br><br><br><br>";
						echo '===================================================================='.$loadxls->getActiveSheet()->getCell('C'.$i)->getValue()."<br>";
// 					var_dump(strtotime($loadxls->getActiveSheet ()->getCell ( 'E' . $i )->getValue ()));
// 					导入农场基础信息
// 					var_dump($loadxls->getActiveSheet()->getCell('H'.$i)->getValue())."<br>";
// // 					echo ManagementArea::find()->where(['areaname'=>$loadxls->getActiveSheet()->getCell('B'.$i)->getValue()])->one()['id'];"<br>";
					$farmsmodel = $this->findModel($farm['id']);
// 					var_dump($farmsmodel);
					// $farmsmodel = $loadxls->getActiveSheet()->getCell('A'.$i)->getValue();
					// $farmsmodel->id = ( int ) $loadxls->getActiveSheet ()->getCell ( 'A' . $i )->getValue ();
// 					if($loadxls->getActiveSheet ()->getCell ( 'E' . $i )->getValue ())
						$farmsmodel->surveydate = (string)strtotime($loadxls->getActiveSheet ()->getCell ( 'E' . $i )->getValue ());
// 					if($loadxls->getActiveSheet ()->getCell ( 'H' . $i )->getValue ())
						$farmsmodel->remarks = $loadxls->getActiveSheet ()->getCell ( 'H' . $i )->getValue ();

					if($farmsmodel->save ())
						echo 'yes';
					else 
						var_dump($farmsmodel->getErrors());
// 					if($farmsmodel->getErrors()) {
// 						var_dump($farmsmodel->getErrors());
// 						exit;
// 					}
// 					}
// 					if($farmsmodel->getErrors())
						
// 					var_dump($farmsmodel);
// 					exit;
					
					// 导入农场宗地信息
// 					$OldContractNumber = $loadxls->getActiveSheet ()->getCell ( 'K' . $i )->getValue ();
// 					$htareaArray = explode ( '-', $OldContractNumber );
					// var_dump($loadxls->getActiveSheet()->getCell('A'.$i)->getValue());
					// var_dump($OldContractNumber);
					// exit;
// 					if (is_array ( $htareaArray ))
// 						$htarea = $htareaArray [2];
// 					else
// 						return $OldContractNumber;
// 					$j = $i + 1;
					
// 					if ($i <= $rows) {
// 						$NewContractNumber = $loadxls->getActiveSheet ()->getCell ( 'K' . $j )->getValue ();
						
// 						if ($OldContractNumber == $NewContractNumber) {
// 							// $htareaArray = explode('-', $OldContractNumber);
// 							// $htarea = $htareaArray[2];
// 							$netarea = Parcel::find ()->where ( [ 
// 									'unifiedserialnumber' => $loadxls->getActiveSheet ()->getCell ( 'G' . $i )->getValue () 
// 							] )->one ()['netarea'];
// 							$zongdi [] = $loadxls->getActiveSheet ()->getCell ( 'G' . $i )->getValue () . '(' . $netarea . ')';
// 							$area += $netarea;
// 						} else {
// 							//
// 							$netarea = Parcel::find ()->where ( [ 
// 									'unifiedserialnumber' => $loadxls->getActiveSheet ()->getCell ( 'G' . $i )->getValue () 
// 							] )->one ()['netarea'];
// 							$zongdi [] = $loadxls->getActiveSheet ()->getCell ( 'G' . $i )->getValue () . '(' . $netarea . ')';
// 							$area += $netarea;
// 							$farm = Farms::find ()->where ( [ 
// 									'contractnumber' => $loadxls->getActiveSheet ()->getCell ( 'K' . $i )->getValue () 
// 							] )->one ();
// 							if ($farm)
// 								$farmModel = $this->findModel ( $farm->id );
// 							else
// 								return '无此合同农场' . $OldContractNumber;
// 								// var_dump($zongdi);
// 							$farmModel->zongdi = implode ( '、', $zongdi );
							
// 							// var_dump($htarea);
// 							// var_dump($OldContractNumber);
// 							if (bccomp ( ( float ) $htarea, $area ) == 1) {
// 								$notclear = ( float ) sprintf ( "%.2f", $htarea - $area );
// 								// $lastarea = $area;
// 							} else {
// 								$notclear = 0.0;
// 							}
							
// 							$farmModel->measure = $area;
// 							$farmModel->notclear = $notclear;
// 							$farmModel->save ();
// 							// var_dump($farmModel->getErrors());
// 							$area = 0.0;
// 							$zongdi = [ ];
// 							// if($notclear !== 0.0)
// 							// var_dump($farmModel->attributes);
// 						}
					}
				}
			}
		}
// 		exit;

// 		Logs::writeLog ( '农场XLS批量导入' );
		return $this->render ( 'farmsxls', [ 
				'model' => $model,
				'rows' => $rows, 
// 				'area' => $area
		] );
	}
	//八大块，宜林农林
	public function actionFarmsland()
	{
		$whereArray = Farms::getManagementArea()['id'];
		$searchModel = new farmsSearch ();
		
		$params = Yii::$app->request->queryParams;
		$params ['farmsSearch'] ['state'] = 1;
		
		// 管理区域是否是数组
		if (empty($params['farmsSearch']['management_area'])) {
			$params ['farmsSearch'] ['management_area'] = $whereArray;
		}

		$dataProvider = $searchModel->search ( $params );

		// 如果选择多个区域, 默认为空
		if (is_array($searchModel->management_area)) {
			$searchModel->management_area = null;
		}

		Logs::writeLog ( '农场管理' );
		return $this->render ( 'farmsland', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'params' => $params,
		] );
	}
	
	public function actionFarmsinaccountnumber()
	{
		
	}
	
	public function actionFarmstoxls()
	{
		$ColumnNames = ['management_area','farmname','farmername','address','contractnumber','accountnumber','measure','zongdi','notclear','notstate','cardid','telephone'];
// 		[
// 			'Farms' => ['management_area','farmname','farmername','contractnumber','cardid','telephone','measure','zongdi','notclear','notstate','longitude','latitude'],
// 			'Lease' => ['lessee','lessee_cardid','lessee_telephone','lease_area',],
// 			'Plantingstructure' => ['plant_id','area'],
// 		];
		$farms = Farms::find()->all();
		return $this->render ( 'farmstoxls', [
// 				'farms' => $farms,
				'ColumnNames' => $ColumnNames,
		] );
	}
	
	// private function formatLongLat($str, $l) {
	// $miao = substr ( $str, - 4 );
	// $fen = substr ( $str, - 6, 2 );
	// $du = '';
	// if (strlen ( $str ) == 9)
	// $du = substr ( $str, 0, 3 );
	// if (strlen ( $str ) == 7)
	// $du = substr ( $str, 0, 2 );
	// $result = $l . $du . '°' . $fen . "'" . $miao . '"';
	// return $result;
	// }
	//统一修改经纬度
	public function actionFarmsmodfiylonglat() {
		$farms = Farms::find ()->all ();
		foreach ( $farms as $farm ) {
			$model = $this->findModel ( $farm ['id'] );
			$model->longitude = $this->formatLongLat ( $farm ['longitude'], 'E' );
			$model->latitude = $this->formatLongLat ( $farm ['latitude'], 'N' );
			$model->save ();
		}
		return '完成';
	}
	private function formatLongLat($str, $l) {
		if (strlen ( $str ) == 11) {
			$miao = substr ( $str, - 3, 2 );
			$fen = substr ( $str, - 5, 2 );
			$du = substr ( $str, - 8, 2 );
			// var_dump($miao);
			$result = $l . $du . '°' . $fen . "'" . $miao . '.0' . '"';
		} else
			$result = $str;
		
		return $result;
	}
	//
	// public function actionFarmsma($mid,$nid)
	// {
	// $farms = Farms::find()->where(['management_area'=>$mid])->all();
	// foreach ($farms as $value) {
	// $model = $this->findModel($value['id']);
	// $model->management_area = $nid;
	// $model->save();
	// }
	// //var_dump($farms);
	// echo 'yes';
	// }
	
	// public function actionXlsbj()
	// {
	// set_time_limit(0);
	// $cw_loadxls = \PHPExcel_IOFactory::load('uploads/cw.xlsx');
	// $ht_loadxls = \PHPExcel_IOFactory::load('uploads/ht.xlsx');
	// $cw_rows = $cw_loadxls->getActiveSheet()->getHighestRow();
	// $ht_rows = $ht_loadxls->getActiveSheet()->getHighestRow();
	
	// for($i=1;$i<=$cw_rows;$i++) {
	// for($j=1;$j<=$ht_rows;$j++) {
	// if($cw_loadxls->getActiveSheet()->getCell('D'.$i)->getValue() == $ht_loadxls->getActiveSheet()->getCell('B'.$j)->getValue()) {
	// $result[$cw_loadxls->getActiveSheet()->getCell('D'.$i)->getValue()]['cw'][] = $cw_loadxls->getActiveSheet()->getCell('E'.$i)->getValue();
	// $result[$cw_loadxls->getActiveSheet()->getCell('D'.$i)->getValue()]['ht'][] = $ht_loadxls->getActiveSheet()->getCell('D'.$j)->getValue();
	// }
	// }
	// }
	// foreach($result as $key=>$value) {
	// foreach ($value['cw'] as $cw) {
	// $last[$key] = ['cw' =>$cw];
	// }
	// $area = 0;
	// $i = 1;
	// foreach ($value['ht'] as $ht) {
	// $area += $ht;
	// $last[$key]['ht'] = $area;
	// $i++;
	// }
	// $last[$key]['num'] = $i;
	// }
	// $objPHPExcel = new PHPExcel();
	// $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
	// for ($i = 1; $i <= count($last); $i++) {
	// $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, key($last[$i]));
	// $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $last['cw']);
	// $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $last['ht']);
	// $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $last['num']);
	// }
	
	// // 在默认sheet后，创建一个worksheet
	// echo date('H:i:s') . " Create new Worksheet object\n";
	// $objPHPExcel->createSheet();
	// $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
	// $objWriter-save('php://output');
	// // var_dump($last);
	// }
	
	// public function actionFarmszdxls()
	// {
	// set_time_limit(0);
	// $model = new UploadForm();
	// $rows = 0;
	// if (Yii::$app->request->isPost) {
	
	// $model->file = UploadedFile::getInstance($model, 'file');
	// if($model->file == null)
	// throw new \yii\web\UnauthorizedHttpException('对不起，请先选择xls文件');
	// if ($model->file && $model->validate()) {
	
	// $xlsName = time().rand(100,999);
	// $model->file->name = $xlsName;
	// $model->file->saveAs('uploads/' . $model->file->name . '.' . $model->file->extension);
	// $path = 'uploads/' . $model->file->name . '.' . $model->file->extension;
	// $loadxls = \PHPExcel_IOFactory::load($path);
	// $rows = $loadxls->getActiveSheet()->getHighestRow();
	// for($i=1;$i<=$rows;$i++) {
	// $id = Farms::find()->where(['farmname'=>$loadxls->getActiveSheet()->getCell('B'.$i)->getValue(),'farmername'=>$loadxls->getActiveSheet()->getCell('C'.$i)->getValue()])->one()['id'];
	// //if(!$id)
	// //echo '-------------'.$i.'---------<br>';
	// $data[$id][] = $loadxls->getActiveSheet()->getCell('D'.$i)->getValue();
	// }
	
	// }
	// //$k = 0;
	// var_dump($data);
	// foreach ($data as $key => $value) {
	// $model = $this->findModel($key);
	// $model->zongdi = implode('、', $value);
	// foreach($value as $val)
	// $model->measure += Parcel::find()->where(['unifiedserialnumber'=>$val])->one()['grossarea'];
	// $model->save();
	// }
	
	// // echo count($data).'<br>';
	
	// }
	
	// Logs::writeLog('宗地XLS批量导入');
	// return $this->render('farmszdxls',[
	// 'model' => $model,
	// 'rows' => $rows,
	// ]);
	// }
	// 业务办理列表页面
	public function actionFarmsbusiness() {
		
		$whereArray = Farms::getManagementArea()['id'];
		
		$searchModel = new farmsSearch ();
		$params = Yii::$app->request->queryParams;
// 		var_dump($params);exit;
		// 管理区域是否是数组
		if (! empty ( $whereArray ) && count ( $whereArray ) > 0) {
			$params ['farmsSearch'] ['management_area'] = $whereArray;
		}
		$params ['farmsSearch'] ['state'] = 1;
		if(isset($_GET['locked']))
			$params['farmsSearch']['locked'] = $_GET['locked'];
		if(isset($_GET['zongdi']))
			$params['farmsSearch']['zongdi'] = $_GET['zongdi'];
		if(isset($_GET['notclear']))
			$params['farmsSearch']['notclear'] = $_GET['notclear'];
		if(isset($_GET['notstate']))
			$params['farmsSearch']['notstate'] = $_GET['notstate'];
		if(isset($_GET['dispute'])) {
			$disputes = Dispute::find()->where(['state'=>0])->all();
			$farms_ids = [];
			foreach ($disputes as $dispute) {
				$farms_ids[] = $dispute['farms_id'];
			}
			$params['farmsSearch']['id'] = $farms_ids;
		}
		if(isset($_GET['machine'])) {
			$machines = Machineoffarm::find()->all();
			$farms_ids = [];
			foreach ($machines as $machine) {
				$farms_ids[] = $machine['farms_id'];
			}
			$params['farmsSearch']['id'] = $farms_ids;
		}
		if(isset($_GET['project'])) {
			$projects = Projectapplication::find()->where(['state'=>1])->all();
			$farms_ids = [];
			foreach ($projects as $project) {
				$farms_ids[] = $project['farms_id'];
			}
			$params['farmsSearch']['id'] = $farms_ids;
		}
		if(isset($_GET['collection'])) {
			$collections = Collection::find()->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->all();
			$farms_ids = [];
			foreach ($collections as $collection) {
				$farms_ids[] = $collection['farms_id'];
			}
			$params['farmsSearch']['id'] = $farms_ids;
		}
		// $params ['farmsSearch'] ['update_at'] = date('Y');
		$dataProvider = $searchModel->search ( $params );
		Logs::writeLog ( '业务办理' );
		return $this->render ( 'farmsbusiness', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	public function actionFarmslist() 
	{
		set_time_limit ( 0 );
// 		$farms = Farms::find ()->all ();
// 		$attributes = Farmselastic::getAtt();
// 		foreach ( $farms as $farm ) {
// 			$elastic = new Farmselastic();
// 			foreach ( $attributes as $value ) {
// 				if ($value !== 'index' and $value !== 'type')
// 					$elastic->$value = $farm[$value];
// 			}
// 			$elastic->insert();
// 		}
		// 		var_dump(Farmselastic::index());
// 		echo 'insert done';
// 		set_time_limit ( 0 );
// 		$farms = Farms::find()->all();
// 		foreach ($farms as $farm) {
// 			$elastic = new Elasticsearch();
// 			$elastic->setModel('Farms');
// 			foreach ($elastic->attributes as $value) {
// 				if($value !== 'index' and $value !== 'type')
// 					$elastic->$value = $farm[$value];
// 			}
// 			$elastic->insert();
// 		}
// 		echo 'done';
// 		var_dump(Farmselastic::getDb());
		$sum = 0.0;
		$farms = Farms::find ()->where(['state'=>1])->all ();
		foreach ( $farms as $farm ) {
			if($farm['zongdi']) {
				$zongdiArray = explode('、', $farm['zongdi']);			
				foreach ($zongdiArray as $value) {
					$model = new Zongdioffarm();
					$model->farms_id = $farm['id'];
					$model->zongdinumber = Lease::getZongdi($value);
					$model->measure = Lease::getArea($value);
					$model->save();
				}
			}
		}
		echo 'finished';
// 		return $this->render ( 'farmslist', [ 
// 				'data' => $data 
// 		] );
	}
	// 得到所有农场ID
	public function actionGetfarmid($id) {
		$arrayFarmsid = Farms::getFarmArray ();
		for($i = 0; $i < count ( $arrayFarmsid ); $i ++) {
			
			if (($i + 1) == $id) {
				$result = $arrayFarmsid [$i];
			}
		}
		echo json_encode ( [ 
				'farmsid' => $result 
		] );
	}
	// 业务办理菜单页面
	public function actionFarmsmenu($farms_id) {
		// 如果当前日期超过贷款日期则解锁
		Farms::unLocked ( $farms_id );
		$farm = $this->findModel ( $farms_id );
		$farmsmenu = $this->showFarmmenu ( $farms_id );
		Logs::writeLog ( '进入业务办理菜单页面', $farms_id );
		return $this->render ( 'farmsmenu', [ 
				'farm' => $farm,
				'year' => Theyear::findOne ( 1 ),
				'farmsmenu' => $farmsmenu 
		] );
	}
	
	/**
	 * Displays a single Farms model.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionFarmsview($id) {
		$model = $this->findModel ( $id );
		$farmer = Farmer::find()->where(['farms_id'=>$id])->one();
		$cooperativeoffarm = CooperativeOfFarm::find ()->where ( [ 
				'farms_id' => $id 
		] )->all ();
		$zongdiarr = explode ( ' ', $model->zongdi );
		foreach ( $zongdiarr as $zongdi ) {
			$dataProvider [] = Parcel::find ()->where ( [ 
					'unifiedserialnumber' => $zongdi 
			] )->one ();
		}
		Logs::writeLog ( '查看农场信息', $id );
		return $this->render ( 'farmsview', [ 
				'model' => $model,
				'dataProvider' => $dataProvider,
				'cooperativeoffarm' => $cooperativeoffarm,
				'farmer' => $farmer,
		] );
	}
	//八大块，宜农林地查看详情
	public function actionFarmslandview($id) {
		$model = $this->findModel ( $id );
		$farmer = Farmer::find()->where(['farms_id'=>$id])->one();
		$cooperativeoffarm = CooperativeOfFarm::find ()->where ( [
				'farms_id' => $id
		] )->all ();
		$zongdiarr = explode ( ' ', $model->zongdi );
		foreach ( $zongdiarr as $zongdi ) {
			$dataProvider [] = Parcel::find ()->where ( [
					'unifiedserialnumber' => $zongdi
			] )->one ();
		}
		Logs::writeLog ( '查看农场信息', $id );
		return $this->render ( 'farmslandview', [
				'model' => $model,
				'dataProvider' => $dataProvider,
				'cooperativeoffarm' => $cooperativeoffarm,
				'farmer' => $farmer,
		] );
	}
	// 转让主页面
	public function actionFarmsttpomenu($farms_id) {
//		$ttpoModel = Ttpo::find ()->orWhere ( [
//				'oldfarms_id' => $farms_id
//		] )->orWhere ( [
//				'newfarms_id' => $farms_id
//		] )->all ();
		$ttpozongdiModel = Ttpozongdi::find ()->orWhere ( [ 
				'oldfarms_id' => $farms_id 
		] )->orWhere ( [ 
				'newfarms_id' => $farms_id 
		] )->all ();

		Logs::writeLog ( '农场转让信息', $farms_id );
		return $this->render ( 'farmsttpomenu', [ 
//				'ttpoModel' => $ttpozongdiModel,
				'ttpozongdiModel' => $ttpozongdiModel,
				'farms_id' => $farms_id 
		] );
	}
// 整体转让选择页面
	public function actionFarmsttpowhole($farms_id) {
		$ttpoModel = Ttpo::find ()->orWhere ( [
			'oldfarms_id' => $farms_id
		] )->orWhere ( [
			'newfarms_id' => $farms_id
		] )->all ();
		$ttpozongdiModel = Ttpozongdi::find ()->orWhere ( [
			'oldfarms_id' => $farms_id
		] )->orWhere ( [
			'newfarms_id' => $farms_id
		] )->all ();
		Logs::writeLog ( '农场转让信息', $farms_id );
		return $this->render ( 'farmsttpowhole', [
			'ttpoModel' => $ttpoModel,
			'ttpozongdiModel' => $ttpozongdiModel,
			'farms_id' => $farms_id
		] );
	}
	// 部分转让选择页面
	public function actionFarmsttpopart($farms_id) {
		$ttpoModel = Ttpo::find ()->orWhere ( [
			'oldfarms_id' => $farms_id
		] )->orWhere ( [
			'newfarms_id' => $farms_id
		] )->all ();
		$ttpozongdiModel = Ttpozongdi::find ()->orWhere ( [
			'oldfarms_id' => $farms_id
		] )->orWhere ( [
			'newfarms_id' => $farms_id
		] )->all ();
		Logs::writeLog ( '农场转让信息', $farms_id );
		return $this->render ( 'farmsttpopart', [
			'ttpoModel' => $ttpoModel,
			'ttpozongdiModel' => $ttpozongdiModel,
			'farms_id' => $farms_id
		] );
	}
	// 查看过户信息
	public function actionFarmsttpoview($id) {
		$ttpoModel = Ttpo::findOne ( $id );
		$oldFarm = Farms::find ()->where ( [ 
				'id' => $ttpoModel->oldfarms_id 
		] )->one ();
		$newFarm = Farms::find ()->where ( [ 
				'id' => $ttpoModel->newfarms_id 
		] )->one ();
		return $this->render ( 'farmsttpoview', [ 
				'ttpoModel' => $ttpoModel,
				'oldFarm' => $oldFarm,
				'newFarm' => $newFarm 
		] );
	}
	// 查看转让信息
	public function actionFarmsttpozongdiview($id) {
		$ttpoModel = Ttpozongdi::findOne ( $id );
// 		var_dump($ttpoModel);exit;
		$state = Estate::find()->where(['reviewprocess_id'=>$ttpoModel->reviewprocess_id])->count();
		if($ttpoModel->oldnewfarms_id)
			$oldfarms_id = $ttpoModel->oldnewfarms_id;
		else 
			$oldfarms_id = $ttpoModel->oldfarms_id;
		if($ttpoModel->newnewfarms_id)
			$newfarms_id = $ttpoModel->newnewfarms_id;
		else
			$newfarms_id = $ttpoModel->newfarms_id;
		
// 		var_dump($oldfarms_id);
// 		var_dump($newfarms_id);
// 		exit;
		$oldFarm = Farms::find ()->where ( [ 
				'id' => $oldfarms_id 
		] )->one ();
		$newFarm = Farms::find ()->where ( [ 
				'id' => $newfarms_id 
		] )->one ();
// 		var_dump($newFarm);exit;
		return $this->render ( 'farmsttpozongdiview', [ 
				'ttpoModel' => $ttpoModel,
				'oldFarm' => $oldFarm,
				'newFarm' => $newFarm,
				'farms_id' => $ttpoModel->oldfarms_id,
				'state' => $state,
		] );
	}
	// 农场过户，state状态：0为已经失效，1为当前正用
	public function actionFarmstransfer($farms_id) {
		$oldmodel = $this->findModel ( $farms_id );
		
		$old = $oldmodel->attributes;
		// $model->state = 0;
		
		$reviewprocess = new Reviewprocess ();
		
		$newmodel = new Farms ();
		$new = $newmodel->attributes;
		if ($newmodel->load ( Yii::$app->request->post () )) {
			// 保存审核信息，成功返回reviewprocessID，失败返回flase
// 			var_dump($nowModel);exit;
			// var_dump($reviewprocessModel->getErrors());exit;
			$lockedinfoModel = new Lockedinfo ();
			$lockedinfoModel->farms_id = $farms_id;
			$lockedinfoModel->lockedcontent = '整体过户审核中，已被冻结。';
			$lockedinfoModel->save ();
						
			$newmodel->address = $oldmodel->address;
			$newmodel->management_area = $oldmodel->management_area;
			$newmodel->spyear = $oldmodel->spyear;
			$newmodel->measure = $newmodel->measure;
			$newmodel->contractarea =  $oldmodel->contractarea;
			$newmodel->zongdi = $newmodel->zongdi;
			$newmodel->cooperative_id = $oldmodel->cooperative_id;
			$newmodel->surveydate = $oldmodel->surveydate;
			$newmodel->groundsign = $oldmodel->groundsign;
			$newmodel->investigator = $oldmodel->investigator;
			$newmodel->notclear = $newmodel->notclear;
			$newmodel->notstate = $newmodel->notstate;
			$newmodel->create_at = time ();
			$newmodel->update_at = $newmodel->create_at;
			$newmodel->pinyin = Pinyin::encode ( $newmodel->farmname );
			$newmodel->farmerpinyin = Pinyin::encode ( $newmodel->farmername );
			$newmodel->state = 0;
			$newmodel->locked = 1;
			$newmodel->save ();
			
			$farmerModel = new Farmer();
			$farmerModel->farms_id = $newmodel->id;
			$farmerModel->save();
			
			$oldModel = $this->findModel ( $farms_id );
			$oldModel->locked = 1;
			$oldModel->save ();
// 			var_dump($oldModel->getErrors());exit;
// 			$reviewprocessID = Reviewprocess::processRun ( $model->id, $nowModel->id );
			$ttpoModel = new Ttpozongdi ();
			$ttpoModel->oldfarms_id = $oldmodel->id;
			$ttpoModel->newfarms_id = $newmodel->id;
			$ttpoModel->oldnewfarms_id = '';
			$ttpoModel->newnewfarms_id = '';
			$ttpoModel->create_at = (string)time ();
			
			$ttpoModel->oldzongdi = $old['zongdi'];
			$ttpoModel->oldmeasure = $old['measure'];
			$ttpoModel->oldnotclear = $old['notclear'];
			$ttpoModel->oldnotstate = $old['notstate'];
			$ttpoModel->oldcontractnumber = $old['contractnumber'];
			//原转让改变的信息
			$ttpoModel->oldchangezongdi = $oldmodel->zongdi;
			$ttpoModel->oldchangemeasure = $oldmodel->measure;
			$ttpoModel->oldchangenotclear = $oldmodel->notclear;
			$ttpoModel->oldchangenotstate = $oldmodel->notstate;
			$ttpoModel->oldchangecontractnumber = $oldmodel->contractnumber;
			
			$ttpoModel->auditprocess_id = 1;
			//新转让信息
			$ttpoModel->newzongdi = $new['zongdi'];
			$ttpoModel->newmeasure = $new['measure'];
			$ttpoModel->newnotclear = $new['notclear'];
			$ttpoModel->newnotstate = $new['notstate'];
			$ttpoModel->newcontractnumber = $new['contractnumber'];
			//新转让改变信息
			$ttpoModel->newchangezongdi = $newmodel->zongdi;
			$ttpoModel->newchangemeasure = $newmodel->measure;
			$ttpoModel->newchangenotclear = $newmodel->notclear;
			$ttpoModel->newchangenotstate = $newmodel->notstate;
			$ttpoModel->newchangecontractnumber = $newmodel->contractnumber;
			
			$ttpoModel->ttpozongdi = Yii::$app->request->post ( 'ttpozongdi' );
			$ttpoModel->ttpoarea = Yii::$app->request->post ( 'ttpoarea' );
			$ttpoModel->actionname = \Yii::$app->controller->action->id;
			$ttpoModel->state = 0;
			$ttpoModel->save ();
			
			Logs::writeLog ( '农场转让信息', $newmodel->id, $old, $new );
			$oldFarm = Farms::find()->where(['id'=>$ttpoModel->oldfarms_id])->one();
			$newFarm = Farms::find()->where(['id'=>$ttpoModel->newfarms_id])->one();
			return $this->redirect ([
					'farmsttpozongdiview',
					'id'=> $ttpoModel->id,
					'farms_id'=>$ttpoModel->oldfarms_id,
					'ttpoModel' => $ttpoModel,
					'oldFarm' => $oldFarm,
					'newFarm' => $newFarm,
			]);
// 			return $this->redirect ( [ 
// 					Reviewprocess::getReturnAction (),
// 					'newfarmsid' => $nowModel->id,
// 					'oldfarmsid' => $model->id,
// 					'reviewprocessid' => $reviewprocessID 
// 			] );
		} else {
			return $this->render ( 'farmstransfer', [ 
					'model' => $oldmodel,
					'nowModel' => $newmodel,
					'farms_id' => $farms_id 
			] );
		}
	}
	//整体合并
	public function actionFarmstransfermergecontract($farms_id,$newfarms_id) {
		$oldmodel = $this->findModel ( $farms_id );
		$old = $oldmodel->attributes;

		$newmodel = $this->findModel($newfarms_id);
		$new = $newmodel->attributes;

		$reviewprocess = new Reviewprocess ();
	
		$oldnewmodel = new Farms();
		$newnewmodel = new Farms();
		
		if ($newmodel->load ( Yii::$app->request->post () )) {
			// 保存审核信息，成功返回reviewprocessID，失败返回flase
			// 			var_dump($nowModel);exit;
			// var_dump($reviewprocessModel->getErrors());exit;
			$lockedinfoModel = new Lockedinfo ();
			$lockedinfoModel->farms_id = $farms_id;
			$lockedinfoModel->lockedcontent = '整体过户审核中，已被冻结。';
			$lockedinfoModel->save();
			$lockedinfoModel = new Lockedinfo ();
			$lockedinfoModel->farms_id = $newfarms_id;
			$lockedinfoModel->lockedcontent = '整体过户审核中，已被冻结。';
			$lockedinfoModel->save();

			$newnewmodel->farmname = $newmodel->farmname;
			$newnewmodel->farmername = $newmodel->farmername;
			$newnewmodel->cardid = $newmodel->cardid;
			$newnewmodel->telephone = $newmodel->telephone;
			$newnewmodel->address = $newmodel->address;
			$newnewmodel->management_area = $newmodel->management_area;
			$newnewmodel->spyear = $newmodel->spyear;
			$newnewmodel->measure = $newmodel->measure;
			$newnewmodel->zongdi = $newmodel->zongdi;
			$newnewmodel->cooperative_id = $newmodel->cooperative_id;
			$newnewmodel->surveydate = $newmodel->surveydate;
//			$newnewmodel->groundsign = $newmodel->groudsign;
			$newnewmodel->farmersign = $newmodel->farmersign;
			$newnewmodel->create_at = time();
			$newnewmodel->pinyin = $newmodel->pinyin;
			$newnewmodel->farmerpinyin = $newmodel->farmerpinyin;
			$newnewmodel->begindate = $newmodel->begindate;
			$newnewmodel->enddate = $newmodel->enddate;
			$newnewmodel->oldfarms_id = $oldmodel->id;
			$newnewmodel->latitude = $newmodel->latitude;
			$newnewmodel->longitude = $newmodel->longitude;
			$newnewmodel->accountnumber = $newmodel->accountnumber;
			$newnewmodel->remarks = $newmodel->remarks;
			$newnewmodel->update_at = time();

			$newnewmodel->notclear = $newmodel->notclear;
			$newnewmodel->notstate = $newmodel->notstate;
			$newnewmodel->contractarea = $newmodel->contractarea;
			$newnewmodel->contractnumber = $newmodel->contractnumber;
			$newnewmodel->state = 0;
			$newnewmodel->locked = 0;
			$newnewmodel->save();

			$oldfarmer = Farmer::find()->where(['farms_id' => $oldmodel->id])->one();

			$oldfarmerModel = Farmer::findOne($oldfarmer['id']);
//			$oldfarmerModel->id = '';
			$farmerModel = new Farmer();
			if ($oldfarmerModel) {
				unset($oldfarmerModel->id);
				$farmerModel->attributes = $oldfarmerModel->attributes;
//				var_dump($farmerModel);exit;
				$farmerModel->id = Null;
			}
			else
				$farmerModel->farms_id = $oldmodel->id;
			$farmerModel->save();
			
			$ttpoModel = new Ttpozongdi ();
			$ttpoModel->oldfarms_id = $oldmodel->id;
			$ttpoModel->newfarms_id = $newmodel->id;
			$ttpoModel->oldnewfarms_id = '';
			$ttpoModel->newnewfarms_id = $newnewmodel->id;
			$ttpoModel->create_at = (string)time ();
				
			$ttpoModel->oldzongdi = $old['zongdi'];
			$ttpoModel->oldmeasure = $old['measure'];
			$ttpoModel->oldnotclear = $old['notclear'];
			$ttpoModel->oldnotstate = $old['notstate'];
			$ttpoModel->oldcontractnumber = $old['contractnumber'];
			//原转让改变的信息
			$ttpoModel->oldchangezongdi = $oldmodel->zongdi;
			$ttpoModel->oldchangemeasure = $oldmodel->measure;
			$ttpoModel->oldchangenotclear = $oldmodel->notclear;
			$ttpoModel->oldchangenotstate = $oldmodel->notstate;
			$ttpoModel->oldchangecontractnumber = $oldmodel->contractnumber;
				
			$ttpoModel->auditprocess_id = 1;
			//新转让信息
			$ttpoModel->newzongdi = $new['zongdi'];
			$ttpoModel->newmeasure = $new['measure'];
			$ttpoModel->newnotclear = $new['notclear'];
			$ttpoModel->newnotstate = $new['notstate'];
			$ttpoModel->newcontractnumber = $new['contractnumber'];
			//新转让改变信息
			$ttpoModel->newchangezongdi = $newmodel->zongdi;
			$ttpoModel->newchangemeasure = $newmodel->measure;
			$ttpoModel->newchangenotclear = $newmodel->notclear;
			$ttpoModel->newchangenotstate = $newmodel->notstate;
			$ttpoModel->newchangecontractnumber = $newmodel->contractnumber;
				
			$ttpoModel->ttpozongdi = Yii::$app->request->post ( 'ttpozongdi' );
			$ttpoModel->ttpoarea = Yii::$app->request->post ( 'ttpoarea' );
			$ttpoModel->actionname = \Yii::$app->controller->action->id;
			$ttpoModel->state = 0;
			$ttpoModel->save ();
			$Oldmodel = $this->findModel($ttpoModel->oldfarms_id);
			$Oldmodel->locked = 1;
			$Oldmodel->save();
			$Newmocel = $this->findModel($ttpoModel->newfarms_id);
			$Newmocel->locked = 1;
			$Newmocel->save();
			$newAttr = $newmodel->attributes;
			Logs::writeLog ( '农场转让信息', $newmodel->id, $old, $newAttr );
			$oldFarm = Farms::find()->where(['id'=>$ttpoModel->oldfarms_id])->one();
			$newFarm = Farms::find()->where(['id'=>$ttpoModel->newfarms_id])->one();
			return $this->redirect ([
					'farmsttpozongdiview',
					'id'=> $ttpoModel->id,
					'farms_id'=>$ttpoModel->oldfarms_id,
					'ttpoModel' => $ttpoModel,
					'oldFarm' => $oldFarm,
					'newFarm' => $newFarm,
			]);
			// 			return $this->redirect ( [
			// 					Reviewprocess::getReturnAction (),
			// 					'newfarmsid' => $nowModel->id,
			// 					'oldfarmsid' => $model->id,
			// 					'reviewprocessid' => $reviewprocessID
			// 			] );
		} else {
			return $this->render ( 'farmstransfermergecontract', [
					'oldFarm' => $oldmodel,
					'newFarm' => $newmodel,
					'farms_id' => $farms_id
			] );
		}
	}
	
	public function actionFarmsttpoupdatefarmstozongdi($id) 
	{
		
		$ttpoModel = Ttpozongdi::findOne($id);
		$oldmodel = Farms::findOne($ttpoModel->oldnewfarms_id);
		$newmodel = Farms::findOne($ttpoModel->newnewfarms_id);
		$post = Yii::$app->request->post ();
		if ($post) {
			$newmodel->load ( Yii::$app->request->post () );
			$newmodel->save();
			$ttpoModel->create_at = (string)time ();
// 			var_dump($post['Farms']);exit;
			if($ttpoModel->actionname !== 'farmstransfer') {
				//原转让改变的信息
				$ttpoModel->oldchangezongdi = $post['oldzongdichange'];
				$ttpoModel->oldchangemeasure = $post['oldmeasure'];
				$ttpoModel->oldchangenotclear = $post['oldnotclear'];
				$ttpoModel->oldchangenotstate = $post['oldnotstate'];
				$ttpoModel->oldchangecontractnumber = $post['oldcontractnumber'];
			}
			//新转让改变信息
			$ttpoModel->newchangezongdi = $post['Farms']['zongdi'];
			$ttpoModel->newchangemeasure = $post['Farms']['measure'];
			$ttpoModel->newchangenotclear = $post['Farms']['notclear'];
			$ttpoModel->newchangenotstate = $post['Farms']['notstate'];
			$ttpoModel->newchangecontractnumber = $post['Farms']['contractnumber'];
			
			$ttpoModel->ttpozongdi = $post['ttpozongdi'];
			$ttpoModel->ttpoarea = $post['ttpoarea'];
			
			$ttpoModel->state = 0;
			$ttpoModel->save ();
			
			
			Zongdioffarm::zongdiUpdate($ttpoModel->oldfarms_id, $ttpoModel->oldnewfarms_id,$ttpoModel->oldchangezongdi);
			Zongdioffarm::zongdiUpdate($ttpoModel->newfarms_id, $ttpoModel->newnewfarms_id,$ttpoModel->newchangezongdi);
			return $this->redirect ([
					'farmsttpozongdiview',
					'id'=> $ttpoModel->id,
					'farms_id'=>$ttpoModel->oldfarms_id,
					'ttpoModel' => $ttpoModel,
					'oldFarm' => $oldmodel,
					'newFarm' => $newmodel,
			]);
		} else {
			return $this->render ( 'farmsttpoupdatefarmstozongdi', [
					'model' => $oldmodel,
					'nowModel' => $newmodel,
					'ttpoModel' => $ttpoModel,
					'farms_id' => $ttpoModel->oldfarms_id,
			] );
		}
	}
	public function actionFarmsttpoupdatefarmstransfer($id)
	{
	
		$ttpoModel = Ttpozongdi::findOne($id);
		$oldmodel = Farms::findOne($ttpoModel->oldfarms_id);
		$newmodel = Farms::findOne($ttpoModel->newfarms_id);
		$post = Yii::$app->request->post ();
		if ($post) {
			$newmodel->load ( Yii::$app->request->post () );
			$newmodel->save();
			$ttpoModel->create_at = (string)time ();
			// 			var_dump($post['Farms']);exit;
// 			if($ttpoModel->actionname !== 'farmstransfer') {
// 				//原转让改变的信息
// 				$ttpoModel->oldchangezongdi = $post['oldzongdichange'];
// 				$ttpoModel->oldchangemeasure = $post['oldmeasure'];
// 				$ttpoModel->oldchangenotclear = $post['oldnotclear'];
// 				$ttpoModel->oldchangenotstate = $post['oldnotstate'];
// 				$ttpoModel->oldchangecontractnumber = $post['oldcontractnumber'];
// 			}
			//新转让改变信息
			$ttpoModel->newchangezongdi = $post['Farms']['zongdi'];
			$ttpoModel->newchangemeasure = $post['Farms']['measure'];
			$ttpoModel->newchangenotclear = $post['Farms']['notclear'];
			$ttpoModel->newchangenotstate = $post['Farms']['notstate'];
			$ttpoModel->newchangecontractnumber = $post['Farms']['contractnumber'];
				
			$ttpoModel->ttpozongdi = $post['ttpozongdi'];
			$ttpoModel->ttpoarea = $post['ttpoarea'];
				
			$ttpoModel->state = 0;
			$ttpoModel->save ();				
				
			Zongdioffarm::zongdiUpdate($ttpoModel->oldfarms_id,$ttpoModel->newfarms_id, $ttpoModel->newzongdi);
			return $this->redirect ([
					'farmsttpozongdiview',
					'id'=> $ttpoModel->id,
					'farms_id'=>$ttpoModel->oldfarms_id,
					'ttpoModel' => $ttpoModel,
					'oldFarm' => $oldmodel,
					'newFarm' => $newmodel,
			]);
		} else {
			return $this->render ( 'farmsttpoupdatefarmstransfer', [
					'model' => $oldmodel,
					'nowModel' => $newmodel,
					'ttpoModel' => $ttpoModel,
					'farms_id' => $ttpoModel->oldfarms_id,
			] );
		}
	}
//整体转让合并修改页面
	public function actionFarmsttpoupdatefarmstransfermergecontract($id)
	{

		$ttpoModel = Ttpozongdi::findOne($id);
		$oldmodel = Farms::findOne($ttpoModel->oldfarms_id);
		$newmodel = Farms::findOne($ttpoModel->newfarms_id);
		$post = Yii::$app->request->post ();
		if ($post) {
			$newmodel->load ( Yii::$app->request->post () );
			$newmodel->save();
			$ttpoModel->create_at = (string)time ();
			// 			var_dump($post['Farms']);exit;
// 			if($ttpoModel->actionname !== 'farmstransfer') {
// 				//原转让改变的信息
// 				$ttpoModel->oldchangezongdi = $post['oldzongdichange'];
// 				$ttpoModel->oldchangemeasure = $post['oldmeasure'];
// 				$ttpoModel->oldchangenotclear = $post['oldnotclear'];
// 				$ttpoModel->oldchangenotstate = $post['oldnotstate'];
// 				$ttpoModel->oldchangecontractnumber = $post['oldcontractnumber'];
// 			}
			//新转让改变信息
			$ttpoModel->newchangezongdi = $post['Farms']['zongdi'];
			$ttpoModel->newchangemeasure = $post['Farms']['measure'];
			$ttpoModel->newchangenotclear = $post['Farms']['notclear'];
			$ttpoModel->newchangenotstate = $post['Farms']['notstate'];
			$ttpoModel->newchangecontractnumber = $post['Farms']['contractnumber'];

			$ttpoModel->ttpozongdi = $post['ttpozongdi'];
			$ttpoModel->ttpoarea = $post['ttpoarea'];

			$ttpoModel->state = 0;
			$ttpoModel->save ();

			Zongdioffarm::zongdiUpdate($ttpoModel->oldfarms_id,$ttpoModel->newfarms_id, $ttpoModel->newzongdi);
			return $this->redirect ([
				'farmsttpozongdiview',
				'id'=> $ttpoModel->id,
				'farms_id'=>$ttpoModel->oldfarms_id,
				'ttpoModel' => $ttpoModel,
				'oldFarm' => $oldmodel,
				'newFarm' => $newmodel,
			]);
		} else {
			return $this->render ( 'farmsttpoupdatefarmstransfermergecontract', [
				'oldFarm' => $oldmodel,
				'newFarm' => $newmodel,
				'ttpoModel' => $ttpoModel,
				'farms_id' => $ttpoModel->oldfarms_id,
			] );
		}
	}

	public function actionFarmsttpoupdatefarmssplit($id)
	{
	
		$ttpoModel = Ttpozongdi::findOne($id);
		$oldmodel = Farms::findOne($ttpoModel->oldfarms_id);
		$newmodel = Farms::findOne($ttpoModel->newfarms_id);
		$post = Yii::$app->request->post ();
		if ($post) {
			$newmodel->load ( Yii::$app->request->post () );
			$newmodel->save();
			$ttpoModel->create_at = (string)time ();
			// 			var_dump($post['Farms']);exit;
			// 			if($ttpoModel->actionname !== 'farmstransfer') {
			// 				//原转让改变的信息
			// 				$ttpoModel->oldchangezongdi = $post['oldzongdichange'];
			// 				$ttpoModel->oldchangemeasure = $post['oldmeasure'];
			// 				$ttpoModel->oldchangenotclear = $post['oldnotclear'];
			// 				$ttpoModel->oldchangenotstate = $post['oldnotstate'];
			// 				$ttpoModel->oldchangecontractnumber = $post['oldcontractnumber'];
			// 			}
			//新转让改变信息
			$ttpoModel->newchangezongdi = $post['Farms']['zongdi'];
			$ttpoModel->newchangemeasure = $post['Farms']['measure'];
			$ttpoModel->newchangenotclear = $post['Farms']['notclear'];
			$ttpoModel->newchangenotstate = $post['Farms']['notstate'];
			$ttpoModel->newchangecontractnumber = $post['Farms']['contractnumber'];
	
			$ttpoModel->ttpozongdi = $post['ttpozongdi'];
			$ttpoModel->ttpoarea = $post['ttpoarea'];
	
			$ttpoModel->state = 0;
			$ttpoModel->save ();
	
			Zongdioffarm::zongdiUpdate($ttpoModel->oldfarms_id,$ttpoModel->newfarms_id, $ttpoModel->newzongdi);
			return $this->redirect ([
					'farmsttpozongdiview',
					'id'=> $ttpoModel->id,
					'farms_id'=>$ttpoModel->oldfarms_id,
					'ttpoModel' => $ttpoModel,
					'oldFarm' => $oldmodel,
					'newFarm' => $newmodel,
			]);
		} else {
			return $this->render ( 'farmsttpoupdatefarmssplit', [
					'model' => $oldmodel,
					'nowModel' => $newmodel,
					'ttpoModel' => $ttpoModel,
					'farms_id' => $ttpoModel->oldfarms_id,
			] );
		}
	}
	// 农场转让——新增
	public function actionFarmssplit($farms_id) {
		$oldmodel = $this->findModel ( $farms_id );
		$old = $oldmodel->attributes;
		$oldzongdi = $oldmodel->zongdi;
		$oldcontractnumber = $oldmodel->contractnumber;
		$oldmeasure = $oldmodel->measure;
		$newmodel = new Farms();
		// $ttpoModel = Ttpo::find()->orWhere(['oldfarms_id'=>$farms_id])->orWhere(['newfarms_id'=>$farms_id])->all();
		// $ttpozongdiModel = Ttpozongdi::find()->orWhere(['oldfarms_id'=>$farms_id])->orWhere(['newfarms_id'=>$farms_id])->all();
		// 原农场转让宗地后，重新签订合同后，生成新的农场信息
		if ($oldmodel->load ( Yii::$app->request->post () )) {
			
// 			var_dump($oldmodel);exit;
			$lockedinfoModel = new Lockedinfo ();
			$lockedinfoModel->farms_id = $farms_id;
			$lockedinfoModel->lockedcontent = '部分过户审核中，已被冻结。';
			$lockedinfoModel->save();
			// $oldmodel->state = 1;
			$oldmodel = $this->findModel ( $farms_id );
			$oldmodel->update_at = time ();			
			$oldmodel->locked = 1;
			$oldmodel->save ();
			$newoldmodel = new Farms();
			$newoldmodel->farmname = $oldmodel->farmname;
			$newoldmodel->farmername = $oldmodel->farmername;
			$newoldmodel->cardid = $oldmodel->cardid;
			$newoldmodel->telephone = $oldmodel->telephone;
			$newoldmodel->address = $oldmodel->address;
			$newoldmodel->management_area = $oldmodel->management_area;
			$newoldmodel->spyear = $oldmodel->spyear;
			$newoldmodel->measure = $oldmodel->measure;
			$newoldmodel->zongdi = $oldmodel->zongdi;
			$newoldmodel->cooperative_id = $oldmodel->cooperative_id;
			$newoldmodel->surveydate = $oldmodel->surveydate;
			$newoldmodel->groundsign = $oldmodel->groundsign;
			$newoldmodel->farmersign = $oldmodel->farmersign;
			$newoldmodel->create_at = $oldmodel->create_at;
			$newoldmodel->pinyin = $oldmodel->pinyin;
			$newoldmodel->farmerpinyin = $oldmodel->farmerpinyin;
			$newoldmodel->begindate = $oldmodel->begindate;
			$newoldmodel->enddate = $oldmodel->enddate;
			$newoldmodel->oldfarms_id = $oldmodel->id;
			$newoldmodel->latitude = $oldmodel->latitude;
			$newoldmodel->longitude = $oldmodel->longitude;
			$newoldmodel->accountnumber = $oldmodel->accountnumber;
			$newoldmodel->remarks = $oldmodel->remarks;
			$newoldmodel->update_at = time();
			$newoldmodel->notclear = $oldmodel->notclear;
			$newoldmodel->notstate = $oldmodel->notstate;
			$newoldmodel->contractarea = $oldmodel->contractarea;
			$newoldmodel->contractnumber = $oldmodel->contractnumber;
			$newoldmodel->state = 1;
			$newoldmodel->locked = 0;
			$newoldmodel->save();
			
			if ($newmodel->load ( Yii::$app->request->post () )) {
				// var_dump($newmodel->zongdi);exit;
				$newmodel->farmname = $newmodel->farmname;
				$newmodel->farmername = $newmodel->farmername;
				$newmodel->cardid = $newmodel->cardid;
				$newmodel->telephone = $newmodel->telephone;
				$newmodel->address = $oldmodel->address;
				$newmodel->management_area = $oldmodel->management_area;				
				$newmodel->measure = $newmodel->measure;
				$newmodel->zongdi = $newmodel->zongdi;
				$newmodel->begindate = $oldmodel->begindate;
				$newmodel->enddate = $oldmodel->enddate;
				$newmodel->latitude = $oldmodel->latitude;
				$newmodel->longitude = $oldmodel->longitude;
				$newmodel->create_at = time ();
				$newmodel->update_at = $newmodel->create_at;
				$newmodel->pinyin = Pinyin::encode($newmodel->pinyin);
				$newmodel->farmerpinyin = Pinyin::encode($newmodel->farmerpinyin);
				$newmodel->contractnumber = $newmodel->contractnumber;
				$newmodel->contractarea = $newmodel->contractarea;
				$newmodel->state = 0;
				$newmodel->locked = 0;
				$newmodel->notclear = $newmodel->notclear;
				$newmodel->remarks = $newmodel->remarks;		
				$newmodel->save ();
				$new = $newmodel->attributes;
// 				Parcel::parcelState(['farms_id'=>$newmodel->id,'zongdi'=>$newmodel->zongdi,'state'=>true]);
			}
			
			$ttpoModel = new Ttpozongdi ();
			$ttpoModel->oldfarms_id = $oldmodel->id;
			$ttpoModel->newfarms_id = $newmodel->id;
			$ttpoModel->oldnewfarms_id = $newoldmodel->id;
			$ttpoModel->newnewfarms_id = '';
			$ttpoModel->create_at = (string)time ();
			//新转让的信息
			$ttpoModel->oldzongdi = $old['zongdi'];
			$ttpoModel->oldmeasure = $old['measure'];
			$ttpoModel->oldnotclear = $old['notclear'];
			$ttpoModel->oldnotstate = $old['notstate'];
			$ttpoModel->oldcontractnumber = $old['contractnumber'];
			//原转让改变的信息
			$ttpoModel->oldchangezongdi =  Yii::$app->request->post ('oldzongdichange');
			$ttpoModel->oldchangemeasure = Yii::$app->request->post ('oldmeasure');
			$ttpoModel->oldchangenotclear = Yii::$app->request->post ('oldnotclear');
			$ttpoModel->oldchangenotstate = Yii::$app->request->post ('oldnotstate');
			$ttpoModel->oldchangecontractnumber = Yii::$app->request->post ('oldcontractnumber');
				
			$ttpoModel->auditprocess_id = 1;
			//新转让信息
			$ttpoModel->newzongdi = $new['zongdi'];
			$ttpoModel->newmeasure = $new['measure'];
			$ttpoModel->newnotclear = $new['notclear'];
			$ttpoModel->newnotstate = $new['notstate'];
			$ttpoModel->newcontractnumber = $new['contractnumber'];
			//新转让改变信息
// 			$ttpoModel->newchangezongdi = $newmodel->zongdi;
// 			$ttpoModel->newchangemeasure = $newmodel->measure;
// 			$ttpoModel->newchangenotclear = $newmodel->notclear;
// 			$ttpoModel->newchangenotstate = $newmodel->notstate;
// 			$ttpoModel->newchangecontractnumber = $newmodel->contractnumber;
				
			$ttpoModel->ttpozongdi = Yii::$app->request->post ( 'ttpozongdi' );
			$ttpoModel->ttpoarea = Yii::$app->request->post ( 'ttpoarea' );
			$ttpoModel->actionname = \Yii::$app->controller->action->id;
			$ttpoModel->state = 0;
			$ttpoModel->save ();
			
			Contractnumber::contractnumberAdd ();
			Zongdioffarm::zongdiUpdate($oldmodel->id, $newoldmodel->id, $ttpoModel->oldchangezongdi);
			Zongdioffarm::zongdiUpdate('',$newmodel->id,$ttpoModel->newzongdi);
// 			var_dump($ttpoModel->oldchangenotclear);exit;
			return $this->redirect ([
					'farmsttpozongdiview',
					'id'=> $ttpoModel->id,
					'farms_id'=>$ttpoModel->oldfarms_id,
					'ttpoModel' => $ttpoModel,
					'oldFarm' => $oldmodel,
					'newFarm' => $newmodel,
			]);
		} else {
			
			return $this->render ( 'farmssplit', [ 
					'oldFarm' => $oldmodel,
					'newFarm' => $newmodel 
			] );
		}
	}
	// 农场转让
	public function actionFarmsttpotransfer($farms_id) {
		$search = Yii::$app->request->post ( 'search' );
		$management_area = Farms::getManagementArea ();
		$farmsSearch = null;
		$dataProvider = null;
		if ($search) {
			$farmsSearch = new farmsSearch ();
			$params = Yii::$app->request->queryParams;
			$params ['farmsSearch'] ['farmname'] = $search;
			$params ['farmsSearch'] ['farmername'] = $search;
			$params ['farmsSearch'] ['management_area'] = $management_area ['id'];
			// 			$params ['farmsSearch'] ['state'] = 1;
			// 			$params ['farmsSearch'] ['locked'] = 0;
			$dataProvider = $farmsSearch->search ( $params );
		}
		return $this->render ( 'farmsttpotransfer', [
				'searchModel' => $farmsSearch,
				'dataProvider' => $dataProvider,
				'oldfarms_id' => $farms_id
		] );
	}
	
	// 农场转让,部分合并
	public function actionFarmsttpozongdi($farms_id) 
	{
		$management_area = Farms::getManagementArea();
		$searchModel = new farmsSearch ();

		$params = Yii::$app->request->queryParams;
		$params['farmsSearch']['management_area'] = $management_area['id'];
		$params['farmsSearch']['state'] = 1;
		$params['farmsSearch']['locked'] = 0;
		$dataProvider = $searchModel->search ( $params );

		return $this->render ( 'farmsttpozongdi', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'oldfarms_id' => $farms_id 
		] );
	}
	// 农场转让，整体合并
	public function actionFarmstransfermerge($farms_id)
	{
		$management_area = Farms::getManagementArea();
		$searchModel = new farmsSearch ();

		$params = Yii::$app->request->queryParams;
		$params['farmsSearch']['management_area'] = $management_area['id'];
		$params['farmsSearch']['state'] = 1;
		$params['farmsSearch']['locked'] = 0;
		$dataProvider = $searchModel->search ( $params );

		return $this->render ( 'farmstransfermerge', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'oldfarms_id' => $farms_id
		] );
	}
	public function actionOldzongdichange($yzongdi,$zongdi,$measure,$state)
	{
		$oldarr = explode ( '、',$yzongdi );
// 		var_dump($oldarr);exit;
		$newZongdi = $oldarr;
		if($state == 'change') {
			foreach ($oldarr as $k => $o) {
				if(Lease::getZongdi($o) == $zongdi) {
					if(Lease::getArea($o) == $measure) {
						unset($newZongdi[$k]);
					} else {
						$newmeasure = Lease::getArea($o) - $measure;
						$newZongdi[$k] = Lease::getZongdi($o)."(".$newmeasure.")";
					}
				}
			}
		}
		$is = true;
		if($state == 'back') {
			foreach ($oldarr as $k => $o) {
				if(Lease::getZongdi($o) == $zongdi) {
					$is = false;
// 					if(Lease::getArea($o) !== $measure) {
						
// 					}
					$newmeasure = Lease::getArea($o) + $measure;
					$newZongdi[$k] = Lease::getZongdi($o)."(".$newmeasure.")";
// 					$newZongdi[$k] = $zongdi."(".$measure.")";
				}				
			}
			if($is) {
				$newZongdi[] = $zongdi."(".$measure.")";
			}
		}
// 		var_dump($newZongdi);exit;
		$result = implode('、', $newZongdi);
		
// 		$farm->save();
		echo json_encode(['zongdi'=>$result]);
	}
	
	// 删除已经分配转让出去的空数组
	private function deleteZongdiDH($zongdiStr) {
		$arrayZongdi = explode ( '、', $zongdiStr );
		// var_dump($arrayZongdi);
		foreach ( $arrayZongdi as $key => $value ) {
			if ($value == '') {
				unset ( $arrayZongdi [$key] );
			}
		}
		sort ( $arrayZongdi );
		return implode ( '、', $arrayZongdi );
	}
	// 转让给现有法人
	public function actionFarmstozongdi($farms_id, $newfarms_id) {
		$oldmodel = $this->findModel ( $farms_id );
		$old = $oldmodel->attributes;
		$newmodel = $this->findModel($newfarms_id);
		$new = $newmodel->attributes;
		
		$oldzongdi = $oldmodel->zongdi;
		$oldcontractnumber = $oldmodel->contractnumber;
		$oldmeasure = $oldmodel->measure;
		$oldmodel->update_at = time ();
		$oldmodel->locked = 1;
		$oldmodel->save ();
		$newmodel->locked = 1;
		$newmodel->update_at = time();
		$newmodel->save ();
// 		var_dump($new);exit;
		$zongditemp = $newmodel->zongdi;
		$lockedinfoModel = new Lockedinfo ();
		$lockedinfoModel->farms_id = $farms_id;
		$lockedinfoModel->lockedcontent = '部分过户审核中，已被冻结。';
		$lockedinfoModel->save();
		$lockedinfoModel = new Lockedinfo ();
		$lockedinfoModel->farms_id = $newmodel->id;
		$lockedinfoModel->lockedcontent = '部分过户审核中，已被冻结。';
		$lockedinfoModel->save();
		// $ttpoModel = Ttpo::find()->orWhere(['oldfarms_id'=>$farms_id])->orWhere(['newfarms_id'=>$farms_id])->all();
		// $ttpozongdiModel = Ttpozongdi::find()->orWhere(['oldfarms_id'=>$farms_id])->orWhere(['newfarms_id'=>$farms_id])->all();
		// 原农场转让宗地后，重新签订合同后，生成新的农场信息
		
		if ($newmodel->load ( Yii::$app->request->post () )) {			
			$newoldmodel = new Farms();			
			$newoldmodel->farmname = $oldmodel->farmname;
			$newoldmodel->farmername = $oldmodel->farmername;
			$newoldmodel->cardid = $oldmodel->cardid;
			$newoldmodel->telephone = $oldmodel->telephone;
			$newoldmodel->address = $oldmodel->address;
			$newoldmodel->management_area = $oldmodel->management_area;
			$newoldmodel->spyear = $oldmodel->spyear;			
			$newoldmodel->cooperative_id = $oldmodel->cooperative_id;
			$newoldmodel->surveydate = $oldmodel->surveydate;
			$newoldmodel->groundsign = $oldmodel->groundsign;
			$newoldmodel->farmersign = $oldmodel->farmersign;
			$newoldmodel->create_at = $oldmodel->create_at;
			$newoldmodel->pinyin = $oldmodel->pinyin;
			$newoldmodel->farmerpinyin = $oldmodel->farmerpinyin;
			$newoldmodel->begindate = $oldmodel->begindate;
			$newoldmodel->enddate = $oldmodel->enddate;
			$newoldmodel->oldfarms_id = $oldmodel->id;
			$newoldmodel->latitude = $oldmodel->latitude;
			$newoldmodel->longitude = $oldmodel->longitude;
			$newoldmodel->accountnumber = $oldmodel->accountnumber;
			$newoldmodel->remarks = $oldmodel->remarks;
			$newoldmodel->update_at = time();
			$newoldmodel->measure = Yii::$app->request->post ('oldmeasure');
			$newoldmodel->zongdi = Yii::$app->request->post ('zongdi');
			$newoldmodel->notclear = Yii::$app->request->post ('notclear');
			$newoldmodel->notstate = Yii::$app->request->post ('notstate');
			$newoldmodel->contractarea = Farms::getContractnumberArea(Yii::$app->request->post ('oldcontractnumber'));
			$newoldmodel->contractnumber = Yii::$app->request->post ('oldcontractnumber');
			$newoldmodel->state = 0;
			$newoldmodel->locked = 0;
			$newoldmodel->save();
// 			var_dump($newoldmodel);
			$oldfarmer = Farmer::find()->where(['farms_id'=>$oldmodel->id])->one();
			$newoldfarmerModel = new Farmer();
			$newoldfarmerModel->farms_id = $newoldmodel->id;
			$newoldfarmerModel->save();
			$newnewmodel = new Farms();
			
			$newnewmodel->farmname = $newmodel->farmname;
			$newnewmodel->farmername = $newmodel->farmername;
			$newnewmodel->cardid = $newmodel->cardid;
			$newnewmodel->telephone = $newmodel->telephone;
			$newnewmodel->address = $newmodel->address;
			$newnewmodel->management_area = $newmodel->management_area;
			$newnewmodel->spyear = $newmodel->spyear;
			$newnewmodel->measure = $newmodel->measure;
			$newnewmodel->zongdi = $newmodel->zongdi;
			$newnewmodel->cooperative_id = $newmodel->cooperative_id;
			$newnewmodel->surveydate = $newmodel->surveydate;
			$newnewmodel->groundsign = $newmodel->groundsign;
			$newnewmodel->farmersign = $newmodel->farmersign;
			$newnewmodel->create_at = $newmodel->create_at;
			$newnewmodel->pinyin = $newmodel->pinyin;
			$newnewmodel->farmerpinyin = $newmodel->farmerpinyin;
			$newnewmodel->begindate = $newmodel->begindate;
			$newnewmodel->enddate = $newmodel->enddate;
			$newnewmodel->oldfarms_id = $newmodel->id;
			$newnewmodel->latitude = $newmodel->latitude;
			$newnewmodel->longitude = $newmodel->longitude;
			$newnewmodel->accountnumber = $newmodel->accountnumber;
			$newnewmodel->remarks = $newmodel->remarks;
			$newnewmodel->update_at = time();
			$newnewmodel->notclear = $newmodel->notclear;
			$newnewmodel->notstate = $newmodel->notstate;
			$newnewmodel->contractarea = $newmodel->contractarea;
			$newnewmodel->contractnumber = $newmodel->contractnumber;
			$newnewmodel->state = 0;
			$newnewmodel->locked = 0;
			$newnewmodel->save();
			
			$newfarmer = Farmer::find()->where(['farms_id'=>$newmodel->id])->one();
			$newnewfarmerModel = new Farmer();
			$newnewfarmerModel->farms_id = $newnewmodel->id;
			$newnewfarmerModel->save();
			
// 			var_dump($newnewmodel);exit;
			$ttpoModel = new Ttpozongdi ();
			$ttpoModel->oldfarms_id = $oldmodel->id;
			$ttpoModel->newfarms_id = $newmodel->id;
			$ttpoModel->oldnewfarms_id = $newoldmodel->id;
			$ttpoModel->newnewfarms_id = $newnewmodel->id;
			$ttpoModel->create_at = (string)time ();
				
			//原转让信息
			$ttpoModel->oldzongdi = $old['zongdi'];
			$ttpoModel->oldmeasure = $old['measure'];
			$ttpoModel->oldnotclear = $old['notclear'];
			$ttpoModel->oldnotstate = $old['notstate'];
			$ttpoModel->oldcontractnumber = $old['contractnumber'];
			//原转让改变的信息
			$ttpoModel->oldchangezongdi =  Yii::$app->request->post ('oldzongdichange');
			$ttpoModel->oldchangemeasure = Yii::$app->request->post ('oldmeasure');
			$ttpoModel->oldchangenotclear = Yii::$app->request->post ('oldnotclear');
			$ttpoModel->oldchangenotstate = Yii::$app->request->post ('oldnotstate');
			$ttpoModel->oldchangecontractnumber = Yii::$app->request->post ('oldcontractnumber');
				
			$ttpoModel->auditprocess_id = 1;
			//新转让信息
			$ttpoModel->newzongdi = $new['zongdi'];
			$ttpoModel->newmeasure = $new['measure'];
			$ttpoModel->newnotclear = $new['notclear'];
			$ttpoModel->newnotstate = $new['notstate'];
			$ttpoModel->newcontractnumber = $new['contractnumber'];
			//新转让改变信息
			$ttpoModel->newchangezongdi = $newmodel->zongdi;
			$ttpoModel->newchangemeasure = $newmodel->measure;
			$ttpoModel->newchangenotclear = $newmodel->notclear;
			$ttpoModel->newchangenotstate = $newmodel->notstate;
			$ttpoModel->newchangecontractnumber = $newmodel->contractnumber;
				
			$ttpoModel->ttpozongdi = Yii::$app->request->post ( 'ttpozongdi' );
			$ttpoModel->ttpoarea = Yii::$app->request->post ( 'ttpoarea' );
			$ttpoModel->actionname = \Yii::$app->controller->action->id;
			$ttpoModel->state = 0;
			$ttpoModel->save ();
			
// 			var_dump($_POST);
// 			var_dump($newmodel);exit;
			Contractnumber::contractnumberAdd ();
			Zongdioffarm::zongdiUpdate($oldmodel->id,$newoldmodel->id, $ttpoModel->oldchangezongdi);
			Zongdioffarm::zongdiUpdate($newmodel->id,$newnewmodel->id, $ttpoModel->newchangezongdi);
			// var_dump($ttpozongdi->getErrors());exit;
			return $this->redirect ([
					'farmsttpozongdiview',
					'id'=> $ttpoModel->id,
					'farms_id'=>$ttpoModel->oldfarms_id,
					'ttpoModel' => $ttpoModel,
					'oldFarm' => $oldmodel,
					'newFarm' => $newmodel,
			]);
		} else {
			
			return $this->render ( 'farmstozongdi', [ 
					'oldFarm' => $oldmodel,
					'newFarm' => $newmodel 
			] );
		}
	}
	
	// public function actionAllstate()
	// {
	// $allfarms = Farms::find()->all();
	// foreach($allfarms as $value) {
	// $model = $this->findModel($value['id']);
	// $model->state = 1;
	// $model->save();
	// }
	
	// }
	/**
	 * Creates a new farms model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionFarmscreate() {
		$model = new farms ();
		
		if ($model->load ( Yii::$app->request->post () )) {
			$model->create_at = time ();
			$model->update_at = time ();
			$model->pinyin = Pinyin::encode ( $model->farmname );
			$model->farmerpinyin = Pinyin::encode ( $model->farmername );
			$model->contractarea = Farms::getContractnumberArea($model->contractnumber);
			if($model->save ()) {
				$farmerModel = new Farmer();
				$farmerModel->farms_id = $model->id;
				$farmerModel->save();
			}
			$newAttr = $model->attributes;
			Logs::writeLog ( '创建农场', $model->id, '', $newAttr );
			
			return $this->redirect ( [ 
					'farmsview',
					'id' => $model->id 
			] );
		} else {
			// Logs::writeLog('农场创建表单');
			return $this->render ( 'farmscreate', [ 
					'model' => $model 
			] );
		}
	}
	public function actionFarmsfileprint($farms_id) {
		$farm = Farms::find ()->where ( [ 
				'id' => $farms_id 
		] )->one ();
		$farmer = Farmer::find ()->where ( [ 
				'farms_id' => $farms_id 
		] )->one ();
		$members = [];
		if($farmer)
			$members = Farmermembers::find ()->where ( [ 
					'farmer_id' => $farmer->id 
			] )->all ();
		return $this->render ( 'farmsfileprint', [ 
				'farm' => $farm,
				'farmer' => $farmer,
				'members' => $members 
		] );
	}
	
	public function actionFarmsfile($farms_id) {
		$farm = Farms::find ()->where ( [
				'id' => $farms_id
		] )->one ();
		$farmer = Farmer::find ()->where ( [
				'farms_id' => $farms_id
		] )->one ();
		$members = [];
		if($farmer)
			$members = Farmermembers::find ()->where ( [
					'farmer_id' => $farmer->id
			] )->all ();
		$machine = Machineoffarm::find()->where(['farms_id'=>$farms_id])->all();
		return $this->render ( 'farmsfile', [
				'farm' => $farm,
				'farmer' => $farmer,
				'members' => $members,
				'machine' => $machine,
		] );
	}
	// public function actionFarmsttpo($farms_id)
	// {
	// $model = $this->findModel($farms_id);
	// $nowModel = new Farms();
	// if ($nowModel->load(Yii::$app->request->post())) {
	// $nowModel->create_at = time();
	// $nowModel->update_at = time();
	// $nowModel->pinyin = Pinyin::encode($nowModel->farmname);
	// $nowModel->farmerpinyin = Pinyin::encode($nowModel->farmername);
	// $nowModel->save();
	// $newAttr = $nowModel->attributes;
	// Logs::writeLog('农场转让信息',$farms_id,$oldAttr,$newAttr);
	
	// return $this->redirect(['farmsview', 'id' => $nowModel->id]);
	// } else {
	// return $this->render('farmsttpo', [
	// 'model' => $model,
	// 'nowModel' => $nowModel,
	// ]);
	// }
	// }
	
	public function actionFarmsaccountnumber()
	{
		$searchModel = new farmsSearch ();
		$whereArray = Farms::getManagementArea();
		$params = Yii::$app->request->queryParams;
		$params ['farmsSearch'] ['state'] = 1;
		// 管理区域是否是数组
		if (empty($params['farmsSearch']['mamagement_area'])) {
			$params ['farmsSearch'] ['management_area'] = $whereArray['id'];
		}
		// var_dump($params);exit;
		$dataProvider = $searchModel->search ( $params );
		Logs::writeLog ( '农场管理' );
		return $this->render ( 'farmsaccountnumber', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
		
		] );
	}
	
	public function actionFarmsupdateaccountnumber($id)
	{
		$model = $this->findModel($id);
		if($model->load(Yii::$app->request->post()) && $model->save()) {
// 			var_dump($model->getErrors());exit;
			return $this->redirect(['farmsaccountnumber']);
		}
		return $this->render('farmsupdateaccountnumber',[
				'model' => $model,
		]);
	}
	
	/**
	 * Updates an existing Farms model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionFarmsupdate($id) {
		$model = $this->findModel ( $id );
		$oldAttr = $model->attributes;
		if ($model->load ( Yii::$app->request->post () )) {
			$model->update_at = time ();
			$model->pinyin = Pinyin::encode ( $model->farmname );
			$model->farmerpinyin = Pinyin::encode ( $model->farmername );
			$model->surveydate = (string)strtotime($model->surveydate);
			$model->save ();
			$newAttr = $model->attributes;
			Logs::writeLog ( '更新农场信息', $id, $oldAttr, $newAttr );
			
			return $this->redirect ( [ 
					'farmsview',
					'id' => $model->id 
			] );
		} else {
			// Logs::writeLog('农场更新表单');
			return $this->render ( 'farmsupdate', [ 
					'model' => $model 
			] );
		}
	}
	
	// public function actionTempzongdichuli()
	// {
	// set_time_limit(0);
	// $farms = Farms::find()->all();
	// foreach($farms as $key=>$value) {
	// $model = $this->findModel($value['id']);
	// $model->measure = $model->measure + $model->notclear;
	// $model->save();
	// }
	// echo 'yes';
	// }
	public function showFarmmenu($farms_id) 
	{
		$html = '';
		$businessmenu = MenuToUser::find ()->where ( [ 
				'role_id' => User::getItemname () 
		] )->one ()['businessmenu'];
// 		var_dump($businessmenu);exit;
		$arrayBusinessMenu = explode ( ',', $businessmenu );
		if($businessmenu) {
			$html = '<div class="row" >';
			
			for($i = 0; $i < count ( $arrayBusinessMenu ); $i ++) {
	
				$menuUrl = Mainmenu::find ()->where ( [ 
						'id' => $arrayBusinessMenu [$i] 
				] )->one ();
				$html .= $this->showMenuPic ( $menuUrl, $farms_id );
			}
			$html .= '</div>';
		}
		return $html;
	}
	

	
	public function showMenuPic($menuUrl, $farms_id) {
		$str = explode ( '/', $menuUrl ['menuurl'] );
		$divInfo = $this->getMenuInfo ( $str [0], $menuUrl, $farms_id );
		$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;">';
		$html .= '<a href=' . $divInfo ['url'] . '>';
		$html .= '<div class="info-box bg-blue">';
		$html .= '<span class="info-box-icon"><i class="' . $divInfo ['icon'] . '"></i></span>';
		$html .= '<div class="info-box-content">';
		$html .= '<span class="info-box-number">' . $divInfo ['title'] . ' </span>';
		$html .= '<span class="info-box-text">' . $divInfo ['info'] . '</span>';
		$html .= '<!-- The progress section is optional -->';
		$html .= '<div class="progress">';
		$html .= '<div class="progress-bar" style="width: 100%"></div>';
		$html .= '</div>';
		$html .= '<span class="progress-description">';
		$html .= $divInfo ['description'];
		$html .= '</span>';
		$html .= '</div><!-- /.info-box-content -->';
		$html .= '</div><!-- /.info-box --></a>';
		$html .= '</div>';
		return $html;
	}
	public function getMenuInfo($controller, $menuUrl, $farms_id) {
		switch ($controller) {
			case 'farmer' :
				$value ['icon'] = 'fa fa-user';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = Farms::find ()->where ( [ 
						'id' => $farms_id 
				] )->one ()['farmername'];
				$value ['description'] = '法人详细信息';
				break;
			case 'farms' :
				$value ['icon'] = 'fa fa-users';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$ttop = Ttpo::find ()->orWhere ( [ 
						'newfarms_id' => $farms_id 
				] )->orWhere ( [ 
						'oldfarms_id' => $farms_id 
				] )->count ();
				$ttopzongdi = Ttpozongdi::find ()->where ( [ 
						'newfarms_id' => $farms_id 
				] )->count ();
				$value ['info'] = '无过户转让信息';
				if ($ttop)
					$value ['info'] = '过户' . $ttop . '次';
				if ($ttopzongdi)
					$value ['info'] .= ' 转让' . $ttopzongdi . '次';
				if (Farms::getLocked ( $farms_id ))
					$value ['info'] = '已冻结';
				$value ['description'] = '过户、转让办理与历史记录';
				break;
			case 'lease' :
				$value ['icon'] = 'fa fa-street-view';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '现共' . Lease::find ()->where ( [ 
						'farms_id' => $_GET ['farms_id'] 
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '人租赁';
				$value ['description'] = '承租人信息及年限';
				break;
			case 'loan' :
				$value ['icon'] = 'fa fa-university';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '现有' . Loan::find ()->where ( [ 
						'farms_id' => $_GET ['farms_id'] 
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '条贷款信息';
				$value ['description'] = '贷款信息';
				break;
			case 'dispute' :
				$value ['icon'] = 'fa fa-commenting';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '现有' . Dispute::find ()->where ( [ 
						'farms_id' => $_GET ['farms_id'] 
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '个纠纷';
				$value ['description'] = '纠纷具体事项';
				break;
			case 'cooperativeoffarm' :
				$value ['icon'] = 'fa fa-briefcase';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '参加了' . Cooperativeoffarm::find ()->where ( [ 
						'farms_id' => $_GET ['farms_id'] 
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '个合作社';
				$value ['description'] = '注册资金等信息';
				break;
			case 'employee' :
				$value ['icon'] = 'fa fa-user-plus';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$employeerows = Employee::find ()->where ( [ 
						'farms_id' => $_GET ['farms_id'] 
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count ();
				$value ['info'] = '雇佣了' . $employeerows . '人';
				$value ['description'] = '雇佣人员的详细信息';
				break;
			case 'plantingstructure' :
				$value ['icon'] = 'fa fa-sun-o';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
// 				var_dump(Theyear::getYeartime()[1]);exit;
				$value ['info'] = '种植了' . Plantingstructure::find ()->where ( [ 
						'farms_id' => $_GET ['farms_id'],
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '种作物';
				$value ['description'] = '种植作物信息';
				break;
			case 'fireprevention' :
				$value ['icon'] = 'fa fa-fire-extinguisher';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '未完成防火工作';
				if (Fireprevention::find ()->where ( [ 
						'farms_id' => $_GET ['farms_id'] 
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count ())
					$value ['info'] = '完成防火工作';
				
				$value ['description'] = '防火宣传、合同签订信息';
				break;
			case 'yields' :
				$value ['icon'] = 'fa fa-balance-scale';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '农作物产量';
				$value ['description'] = '农产品产量信息';
				break;
			case 'sales' :
				$value ['icon'] = 'fa fa-cart-arrow-down';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '有' . Sales::find ()->where ( [ 
						'farms_id' => $_GET ['farms_id'] 
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '条销量信息';
				$value ['description'] = '农产品销售情况';
				break;
			case 'breed' :
				$value ['icon'] = 'fa fa-github-alt';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$breedinfo = breedinfo::find ()->where ( [ 
						'breed_id' => Breed::find ()->where ( [ 
								'farms_id' => $_GET ['farms_id'] 
						] )->one ()['id'] 
				] )->all ();
				$breeds = '';
				foreach ( $breedinfo as $val ) {
					$breeds .= $val ['number'] . Breedtype::find ()->where ( [ 
							'id' => $val ['breedtype_id'] 
					] )->one ()['unit'] . Breedtype::find ()->where ( [ 
							'id' => $val ['breedtype_id'] 
					] )->one ()['typename'];
					$breeds .= ' ';
				}
				if ($breeds)
					$value ['info'] = $breeds;
				else
					$value ['info'] = '无养殖信息';
				$value ['description'] = '养殖信息';
				break;
			case 'prevention' :
				$value ['icon'] = 'fa fa-plus';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '有' . Prevention::find ()->where ( [ 
						'farms_id' => $_GET ['farms_id'] 
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '条防疫信息';
				$value ['description'] = ' 防疫率,疫苗接种等信息';
				break;
			case 'projectapplication' :
				$value ['icon'] = 'fa fa-sticky-note-o';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '申报了' . Projectapplication::find ()->where ( [ 
						'farms_id' => $_GET ['farms_id'] 
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '个项目';
				$value ['description'] = '项目申报、完成情况';
				break;
			case 'disaster' :
				$value ['icon'] = 'fa fa-soundcloud';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '有' . Disaster::find ()->where ( [ 
						'farms_id' => $_GET ['farms_id'] 
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '个灾害';
				$value ['description'] = '受灾、保险情况';
				break;
			case 'collection' :
				$value ['icon'] = 'fa fa-cny';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$isCollection = Collection::find ()->where ( [ 
						'farms_id' => $_GET ['farms_id'],
						'ypayyear' => date ( 'Y' ) 
				] )->count ();
				if ($isCollection)
					$value ['info'] = '已收缴本年度承包费';
				else
					$value ['info'] = '本年度承包费未收缴或有欠费';
				$value ['description'] = '地产科确认承包费并发送至财务科';
				break;
			case 'machineoffarm' :
				$value ['icon'] = 'fa fa-truck';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$machine = Machineoffarm::find ()->where ( [ 
						'farms_id' => $_GET ['farms_id']
				] )->count ();
				$value ['info'] = '有'.$machine.'台农机器具';
				$value ['description'] = '农机器具';
				break;
			case 'insurance' :
				$value ['icon'] = 'fa fa-file-text-o';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$insurance = Insurance::find ()->where ( [
						'farms_id' => $_GET ['farms_id']
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count ();
				if($insurance)
					$value ['info'] = '已参加保险';
				else 
					$value ['info'] = '未参加保险';
				$value ['description'] = '种植业保险';
				break;
			default :
				$value = false;
		}
		return $value;
	}
	
	
	public function actionFarmsdelete($id) {
		$model = $this->findModel ( $id );
		$oldAttr = $model->getAttributes ();
		$model->delete ();
		Logs::writeLog ( '删除农场信息', $id, $oldAttr );
		return $this->redirect ( [ 
				'farmsindex' 
		] );
	}
	public function actionGetfarminfo($str) {
		$search = Farms::find ()->where ( [ 
				'farmname' => $str 
		] )->one ();
		$data ['farmername'] = $search->farmername;
		$data ['cardid'] = $search->cardid;
		$data ['telephone'] = $search->telephone;
		echo json_encode ( [ 
				'status' => 1,
				'data' => $data 
		] );
	}
	public function actionGetfarmerinfo($str) {
		$search = Farms::find ()->where ( [ 
				'farmername' => $str 
		] )->one ();
		$data ['farmname'] = $search->farmname;
		$data ['cardid'] = $search->cardid;
		$data ['telephone'] = $search->telephone;
		echo json_encode ( [ 
				'status' => 1,
				'data' => $data 
		] );
	}
	public function actionGetcardidinfo($str) {
		$search = Farms::find ()->where ( [
				'cardid' => $str
		] )->one ();
		$data ['farmname'] = $search->farmname;
		$data ['farmername'] = $search->farmername;
		$data ['telephone'] = $search->telephone;
		echo json_encode ( [
				'status' => 1,
				'data' => $data
		] );
	}
	public function actionGettelephoneinfo($str) {
		$search = Farms::find ()->where ( [
				'telephone' => $str
		] )->one ();
		$data ['farmname'] = $search->farmname;
		$data ['cardid'] = $search->cardid;
		$data ['farmername'] = $search->farmername;
		echo json_encode ( [
				'status' => 1,
				'data' => $data
		] );
	}
	public function actionFarmssearch($tab,$begindate, $enddate) 
	{
// 		var_dump($_GET);exit;
    	if(isset($_GET['tab']) and $_GET['tab'] !== \Yii::$app->controller->id) {
    		if($_GET['tab'] == 'yields')
    			$class = 'plantingstructureSearch';
    		else
    			$class = $_GET['tab'].'Search';
    		return $this->redirect ([$_GET['tab'].'/'.$_GET['tab'].'search',
    				'tab' => $_GET['tab'],
    				'begindate' => strtotime($_GET['begindate']),
    				'enddate' => strtotime($_GET['enddate']),
					$class =>['management_area' =>  $_GET['management_area']],

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
	    			'begindate' => $_GET['begindate'],
	    			'enddate' => $_GET['enddate'],
	    			'params' => $_GET,
    	]);    	
	}
	
	/**
	 * Finds the farms model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id        	
	 * @return farms the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Farms::findOne ( $id )) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException ( 'The requested page does not exist.' );
		}
	}
}
