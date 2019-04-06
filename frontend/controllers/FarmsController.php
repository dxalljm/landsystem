<?php

namespace frontend\controllers;

use app\models\Fixed;
use app\models\Lockstate;
use app\models\Log;
use app\models\Numberlock;
use app\models\Plantingstructurecheck;
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
use app\models\Farmermembers;
use app\models\Machineoffarm;
use app\models\Insurance;
use app\models\Estate;
use app\models\Farmerinfo;
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

	public function beforeAction($action)
	{
		if(Yii::$app->user->isGuest) {
			$this->redirect(['site/logout']);
		} else {
			return true;
		}
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
	
	public function actionFarmssql()
	{
		foreach (Farms::find()->all() as $farm) {
			$model = $this->findModel($farm['id']);
			if($model->notstate) {
//				var_dump($model->notstate);
				$model->notstateinfo = 2;
				$model->save();
				var_dump($model->getErrors());
			}
		}
	}
	
	public function actionIscontractnumber($lsh)
	{
		$state = 0;
		$msg = '';
		$f = [];
		$farms = Farms::find()->andFilterWhere(['state'=>1])->andFilterWhere(['like','contractnumber',$lsh])->all();
		foreach ($farms as $farm) {
			$contractnumberArray = explode('-', $farm['contractnumber']);
			if($lsh == $contractnumberArray[0]) {
				$state = 1;
				$f = $farm;
			}
		}
// 		var_dump($f);exit;
		if($state == 1) {
			$msg = "对不起,该流水号已经被该农场占用：\r\n".$f['farmname'].'('.$f['farmername'].')'.',合同号：'.$f['contractnumber'];
		}
		echo json_encode(['state'=>$state,'msg'=>$msg]);
	}
	
	public function actionFarmsindex() {
// 		$departmentid = User::find ()->where ( [ 
// 				'id' => \Yii::$app->getUser ()->id 
// 		] )->one ()['department_id'];
// 		$departmentData = Department::find ()->where ( [
// 				'id' => $departmentid 
// 		] )->one ();
// 		$whereArray = explode ( ',', $departmentData ['membership'] );
		
		$searchModel = new farmsSearch ();
//		$whereArray = Farms::getManagementArea()['id'];
//		var_dump($whereArray);exit;
		$params = Yii::$app->request->queryParams;
// 		$params ['farmsSearch'] ['tempdata'] = 0;
		// 管理区域是否是数组
// 		if (empty($params['farmsSearch']['mamagement_area'])) {
// 			$params ['farmsSearch'] ['management_area'] = $whereArray;
// 		}
//		 var_dump($params);exit;
		$dataProvider = $searchModel->search ( $params );
		Logs::writeLogs ( '农场管理' );
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

// 		Logs::writeLogs ( '农场XLS批量导入' );
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
	
	public function actionFarmscareful()
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
	
		$dataProvider = $searchModel->searchCareful ( $params );
	
		// 如果选择多个区域, 默认为空
		if (is_array($searchModel->management_area)) {
			$searchModel->management_area = null;
		}
		//exit;
		Logs::writeLogs ( '农场管理' );
		return $this->render ( 'farmscareful', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'params' => $params,
		] );
	}

	public function actionFarmsaccountnumber()
	{
		$whereArray = Farms::getManagementArea()['id'];
		$searchModel = new farmsSearch ();

		$params = Yii::$app->request->queryParams;
		$params ['farmsSearch'] ['state'] = [1,2,3,4,5];

		// 管理区域是否是数组
		if (empty($params['farmsSearch']['management_area'])) {
			$params ['farmsSearch'] ['management_area'] = $whereArray;
		}

		$dataProvider = $searchModel->search ( $params );

		// 如果选择多个区域, 默认为空
		if (is_array($searchModel->management_area)) {
			$searchModel->management_area = null;
		}

		Logs::writeLogs ( '农场管理' );
		return $this->render ( 'farmsaccountnumber', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'params' => $params,
		] );
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
	
	// Logs::writeLogs('宗地XLS批量导入');
	// return $this->render('farmszdxls',[
	// 'model' => $model,
	// 'rows' => $rows,
	// ]);
	// }
	// 业务办理列表页面
	public function actionFarmsbusiness($iszx=null)
	{
		$whereArray = Farms::getManagementArea()['id'];
		
		$searchModel = new farmsSearch ();
		$params = Yii::$app->request->queryParams;
// 		var_dump($params);exit;
		// 管理区域是否是数组
		if (! empty ( $whereArray ) && count ( $whereArray ) > 0) {
			$params ['farmsSearch'] ['management_area'] = $whereArray;
		}
		if($iszx == 1) {
			$params['farmsSearch']['state'] = 0;
			$params['farmsSearch']['update_at'] = User::getYear();
		}
//		$params ['farmsSearch'] ['state'] = 1;
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
			$collections = Collection::find()->where(['state'=>1,'payyear'=>User::getYear()])->all();
			$farms_ids = [];
			foreach ($collections as $collection) {
				$farms_ids[] = $collection['farms_id'];
			}
			$params['farmsSearch']['id'] = $farms_ids;
		}
		if(isset($_GET['collectiondck'])) {
			$collections = Collection::find()->where(['dckpay'=>1,'state'=>0,'payyear'=>User::getYear()])->all();
			$farms_ids = [];
			foreach ($collections as $collection) {
				$farms_ids[] = $collection['farms_id'];
			}
			$params['farmsSearch']['id'] = $farms_ids;
		}
		// $params ['farmsSearch'] ['update_at'] = date('Y');
		$dataProvider = $searchModel->search ( $params );
		Logs::writeLogs ( '业务办理' );
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
//		Farms::unLocked ( $farms_id );
		$farm = $this->findModel ( $farms_id );
		$farmsmenu = $this->showFarmmenu ( $farms_id );
		Logs::writeLog ( '进入业务办理菜单页面' ,$farms_id);
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
		$farmer = Farmerinfo::find()->where(['cardid'=>$model->cardid])->one();
// 		var_dump($farmer);exit;
		$cooperativeoffarm = CooperativeOfFarm::find ()->where ( [ 
				'farms_id' => $id 
		] )->all ();
		$zongdiarr = explode ( ' ', $model->zongdi );
		foreach ( $zongdiarr as $zongdi ) {
			$dataProvider [] = Parcel::find ()->where ( [ 
					'unifiedserialnumber' => $zongdi 
			] )->one ();
		}
		Logs::writeLogs ( '查看农场信息', $model );
		return $this->render ( 'farmsview', [ 
				'model' => $model,
				'dataProvider' => $dataProvider,
				'cooperativeoffarm' => $cooperativeoffarm,
				'farmer' => $farmer,
		] );
	}
	public function actionFarmsadminview($id) {
		$model = $this->findModel ( $id );
		$farmer = Farmerinfo::find()->where(['cardid'=>$model->cardid])->one();
// 		var_dump($farmer);exit;
		$cooperativeoffarm = CooperativeOfFarm::find ()->where ( [
			'farms_id' => $id
		] )->all ();
		$zongdiarr = explode ( ' ', $model->zongdi );
		foreach ( $zongdiarr as $zongdi ) {
			$dataProvider [] = Parcel::find ()->where ( [
				'unifiedserialnumber' => $zongdi
			] )->one ();
		}
		Logs::writeLogs ( '查看农场信息', $model );
		return $this->render ( 'farmsadminview', [
			'model' => $model,
			'dataProvider' => $dataProvider,
			'cooperativeoffarm' => $cooperativeoffarm,
			'farmer' => $farmer,
		] );
	}
	//八大块，宜农林地查看详情
	public function actionFarmslandview($id) {
		$model = $this->findModel ( $id );
		$farmer = Farmerinfo::find()->where(['cardid'=>$model->cardid])->one();
		$cooperativeoffarm = CooperativeOfFarm::find ()->where ( [
				'farms_id' => $id
		] )->all ();
		$zongdiarr = explode ( ' ', $model->zongdi );
		foreach ( $zongdiarr as $zongdi ) {
			$dataProvider [] = Parcel::find ()->where ( [
					'unifiedserialnumber' => $zongdi
			] )->one ();
		}
		Logs::writeLogs ( '查看农场信息', $model );
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
		$farm = Farms::findOne($farms_id);
		$ttpozongdiModel = Ttpozongdi::find ()->orWhere ( [ 
				'oldnewfarms_id' => $farms_id
		] )->orWhere ( [ 
				'newnewfarms_id' => $farms_id
		] )->all ();

		Logs::writeLog ( '农场转让信息', $farms_id );
		return $this->render ( 'farmsttpomenu', [ 
//				'ttpoModel' => $ttpozongdiModel,
				'ttpozongdiModel' => $ttpozongdiModel,
				'farms_id' => $farms_id,
				'farm' => $farm,
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
		Logs::writeLogs ( '农场转让信息', $ttpoModel ,'ttopzongdi');
		return $this->render ( 'farmsttpoview', [ 
				'ttpoModel' => $ttpoModel,
				'oldFarm' => $oldFarm,
				'newFarm' => $newFarm 
		] );
	}
	// 查看转让信息
	public function actionFarmsttpozongdiview($id) {
		$ttpoModel = Ttpozongdi::findOne ( $id );
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
		Logs::writeLogs ( '农场转让信息', $ttpoModel ,'ttpozongdi');
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
		Logs::writeLog ( '整体过户-新建',$farms_id);
		$oldmodel = $this->findModel ( $farms_id );
		
		$old = $oldmodel->attributes;
		// $model->state = 0;
		
		$reviewprocess = new Reviewprocess ();
		
		$newmodel = new Farms ();
// 		var_dump($oldmodel);exit;
		$new = $newmodel->attributes;
		if ($newmodel->load ( Yii::$app->request->post () )) {
//			var_dump($newmodel->contractnumber);exit;
			// 保存审核信息，成功返回reviewprocessID，失败返回flase
// 			var_dump(Yii::$app->request->post ());
// 			var_dump($newmodel);exit;
			// var_dump($reviewprocessModel->getErrors());exit;
			$lockedinfoModel = new Lockedinfo ();
			$lockedinfoModel->farms_id = $farms_id;
			$lockedinfoModel->lockedcontent = '整体过户审核中，已被冻结。';
			$lockedinfoModel->save ();
			Logs::writeLogs ( '增加整体过户冻结信息', $lockedinfoModel ,'lockedinfo');
						
// 			$newmodel->address = $oldmodel->address;
			$newmodel->management_area = $oldmodel->management_area;
			$newmodel->spyear = $oldmodel->spyear;
			$newmodel->measure = $newmodel->measure;
			$newmodel->contractarea =  $oldmodel->contractarea;
			$newmodel->zongdi = Farms::zongdiHB($newmodel->zongdi);
			$newmodel->cooperative_id = $oldmodel->cooperative_id;
			$newmodel->surveydate = $oldmodel->surveydate;
			$newmodel->groundsign = $oldmodel->groundsign;
			$newmodel->investigator = $oldmodel->investigator;
			$newmodel->notclear = $newmodel->notclear;
			$newmodel->notstate = $newmodel->notstate;
			$newmodel->begindate = date('Y-m-d');
			$newmodel->enddate = '2025-09-13';
			$newmodel->create_at = time ();
			$newmodel->update_at = $newmodel->create_at;
			$newmodel->pinyin = Pinyin::encode ( $newmodel->farmname );
			$newmodel->farmerpinyin = Pinyin::encode ( $newmodel->farmername );
			$newmodel->state = 0;
			$newmodel->locked = 1;
			$newmodel->tempdata = 0;
//			var_dump($newmodel->contractnumber);
//			var_dump(Farms::getContractstate($farms_id));
			if(Farms::getContractstate($farms_id) !== 'W' and Farms::getContractstate($farms_id) !== 'M') {
				$newmodel->contractnumber = $oldmodel->contractnumber;
			}
//			var_dump($newmodel->contractnumber);
// 			var_dump($newmodel);exit;
			$newmodel->save ();
			Logs::writeLogs ( '新增农场', $newmodel );
// 			var_dump($newmodel->getErrors());exit;
			$farmerModel = new Farmer();
			$farmerModel->farms_id = $newmodel->id;
			$farmerModel->save();
			Logs::writeLogs ( '新增法人', $farmerModel ,'farmer');
			$oldModel = $this->findModel ( $farms_id );
			$oldModel->locked = 1;
			$oldModel->save ();
			Logs::writeLogs ( '原农场冻结', $oldModel);
// 			var_dump($oldModel->getErrors());exit;
// 			$reviewprocessID = Reviewprocess::processRun ( $model->id, $nowModel->id );
			$ttpoModel = new Ttpozongdi ();
			$ttpoModel->management_area = $oldmodel->management_area;
			$ttpoModel->oldfarms_id = $oldmodel->id;
			$ttpoModel->newfarms_id = '';
			$ttpoModel->oldnewfarms_id = '';
			$ttpoModel->newnewfarms_id = $newmodel->id;;
			$ttpoModel->create_at = (string)time ();
			
			$ttpoModel->oldzongdi = $old['zongdi'];
			$ttpoModel->oldmeasure = $old['measure'];
			$ttpoModel->oldnotclear = $old['notclear'];
			$ttpoModel->oldnotstate = $old['notstate'];
			$ttpoModel->oldcontractnumber = $old['contractnumber'];
			//原转让改变的信息
//			$ttpoModel->oldchangezongdi = $oldmodel->zongdi;
//			$ttpoModel->oldchangemeasure = $oldmodel->measure;
//			$ttpoModel->oldchangenotclear = $oldmodel->notclear;
//			$ttpoModel->oldchangenotstate = $oldmodel->notstate;
//			$ttpoModel->oldchangecontractnumber = $oldmodel->contractnumber;
			
			$ttpoModel->auditprocess_id = 1;
			//新转让信息
//			$ttpoModel->newzongdi = $new['zongdi'];
//			$ttpoModel->newmeasure = $new['measure'];
//			$ttpoModel->newnotclear = $new['notclear'];
//			$ttpoModel->newnotstate = $new['notstate'];
//			$ttpoModel->newcontractnumber = $new['contractnumber'];
			//新转让改变信息
			$ttpoModel->newchangezongdi = Farms::zongdiHB($newmodel->zongdi);
			$ttpoModel->newchangemeasure = $newmodel->measure;
			$ttpoModel->newchangenotclear = $newmodel->notclear;
			$ttpoModel->newchangenotstate = $newmodel->notstate;
			$ttpoModel->newchangecontractnumber = $newmodel->contractnumber;
//			var_dump(Yii::$app->request->post ( 'ttpozongdi' ));exit;
			$ttpoModel->ttpozongdi = Farms::zongdiHB(Yii::$app->request->post ( 'ttpozongdi' ));
			$ttpoModel->ttpoarea = Yii::$app->request->post ( 'ttpoarea' );
			$ttpoModel->actionname = \Yii::$app->controller->action->id;
			$ttpoModel->state = 0;
			$ttpoModel->save ();
			Logs::writeLogs ( '创建过户信息', $ttpoModel,'ttopzongdi');
			Zongdioffarm::zongdiUpdate($ttpoModel->oldfarms_id, $ttpoModel->oldnewfarms_id,$ttpoModel->oldchangezongdi);
			Zongdioffarm::zongdiUpdate($ttpoModel->newfarms_id, $ttpoModel->newnewfarms_id,$ttpoModel->newchangezongdi);
			$oldFarm = Farms::find()->where(['id'=>$ttpoModel->oldfarms_id])->one();
			$newFarm = Farms::find()->where(['id'=>$ttpoModel->newnewfarms_id])->one();
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
					'newmodel' => $newmodel,
					'farms_id' => $farms_id
			] );
		}
	}
	//整体合并
	public function actionFarmstransfermergecontract($farms_id,$newfarms_id) {
		Logs::writeLog ( '整体过户-合并',$farms_id);
		$oldmodel = $this->findModel ( $farms_id );
		$old = $oldmodel->attributes;

		$newmodel = $this->findModel($newfarms_id);
		$new = $newmodel->attributes;
		$reviewprocess = new Reviewprocess ();
	
		$oldnewmodel = new Farms();
		$newnewmodel = new Farms();
		$post = Yii::$app->request->post ();
		if ($post) {
//			var_dump($post);exit;
			// 保存审核信息，成功返回reviewprocessID，失败返回flase
			// 			var_dump($nowModel);exit;
			// var_dump($reviewprocessModel->getErrors());exit;
//			var_dump($newmodel);
			if(isset($post['Farms']['cardid'])) {
				$newmodel->cardid = $post['Farms']['cardid'];
			}
			$newmodel->address = $post['Farms']['address'];
			$newmodel->telephone = $post['Farms']['telephone'];
			$newmodel->latitude = $post['Farms']['latitude'];
			$newmodel->longitude = $post['Farms']['longitude'];
			$newmodel->save();
//			var_dump($newmodel->getErrors());exit;
			$lockedinfoModel = new Lockedinfo ();
			$lockedinfoModel->farms_id = $farms_id;
			$lockedinfoModel->lockedcontent = '整体过户审核中，已被冻结。';
			$lockedinfoModel->save();
			Logs::writeLogs ( '增加整体过户冻结信息', $lockedinfoModel ,'lockedinfo');
			$lockedinfoModel = new Lockedinfo ();
			$lockedinfoModel->farms_id = $newfarms_id;
			$lockedinfoModel->lockedcontent = '整体过户审核中，已被冻结。';
			$lockedinfoModel->save();
			Logs::writeLogs ( '增加整体过户冻结信息', $lockedinfoModel ,'lockedinfo');
			$newnewmodel->farmname = $newmodel->farmname;
			$newnewmodel->farmername = $newmodel->farmername;
			$newnewmodel->cardid = $newmodel->cardid;
			$newnewmodel->telephone = $newmodel->telephone;
			$newnewmodel->address = $newmodel->address;
			$newnewmodel->management_area = $newmodel->management_area;
			$newnewmodel->spyear = $newmodel->spyear;
//			var_dump($post);exit;
			$newnewmodel->measure = $post['Farms']['measure'];
			if(isset($post['Farms']['zongdi'])) {
				$newnewmodel->zongdi = Farms::zongdiHB($post['Farms']['zongdi']);
			} else {
				$newnewmodel->zongdi = Farms::zongdiHB($newmodel->zongdi.'、'.$oldmodel->zongdi);
			}
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
			$newnewmodel->update_at = $newnewmodel->create_at;

			$newnewmodel->notclear = $post['Farms']['notclear'];
			$newnewmodel->notstate = $post['Farms']['notstate'];
			$newnewmodel->contractarea = $post['Farms']['contractarea'];
			$newnewmodel->contractnumber = $post['Farms']['contractnumber'];
			$newnewmodel->state = 0;
			$newnewmodel->locked = 0;
			$newnewmodel->begindate = date('Y-m-d');
//			$newyear = date('Y') + 15;
			$newnewmodel->enddate = "2025-09-13";
//			var_dump($newnewmodel);exit;
			$newnewmodel->save();
			Logs::writeLogs ( '新增农场', $newnewmodel );
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
			Logs::writeLogs ( '法人信息变更', $farmerModel ,'farmer');
			$ttpoModel = new Ttpozongdi ();
			$ttpoModel->management_area = $oldmodel->management_area;
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
			$ttpoModel->oldchangezongdi = '';
			$ttpoModel->oldchangemeasure = 0;
			$ttpoModel->oldchangenotclear = 0;
			$ttpoModel->oldchangenotstate = 0;
			$ttpoModel->oldchangecontractnumber = $oldmodel->setContractNumberArea($oldmodel->contractnumber,0);
				
			$ttpoModel->auditprocess_id = 1;
			//新转让信息
			$ttpoModel->newzongdi = Farms::zongdiHB($new['zongdi']);
			$ttpoModel->newmeasure = $new['measure'];
			$ttpoModel->newnotclear = $new['notclear'];
			$ttpoModel->newnotstate = $new['notstate'];
			$ttpoModel->newcontractnumber = $new['contractnumber'];
//			var_dump($ttpoModel);
//			var_dump($new);exit;
			//新转让改变信息
			if($oldmodel->zongdi and $newmodel->zongdi) {
				$ttpoModel->newchangezongdi = Farms::zongdiHB($newmodel->zongdi . '、' . $oldmodel->zongdi);
			} else {
				if($oldmodel->zongdi)
					$ttpoModel->newchangezongdi = Farms::zongdiHB($oldmodel->zongdi);
				if($newmodel->zongdi)
					$ttpoModel->newchangezongdi = Farms::zongdiHB($newnewmodel->zongdi);
			}
//			var_dump($newnewmodel);
//			var_dump($ttpoModel->newchangezongdi);
			$ttpoModel->newchangemeasure = $newnewmodel->measure;
			$ttpoModel->newchangenotclear = $newnewmodel->notclear;
			$ttpoModel->newchangenotstate = $newnewmodel->notstate;
			$ttpoModel->newchangecontractnumber = $newnewmodel->contractnumber;
			if(isset($post['Farms']['zongdi'])) {
				$ttpoModel->ttpozongdi = Farms::zongdiHB($post['Farms']['zongdi']);
			} else {
				$ttpoModel->ttpozongdi = $oldmodel->zongdi;
			}
			$ttpoModel->ttpoarea = $oldmodel->contractarea;
			$ttpoModel->actionname = \Yii::$app->controller->action->id;
			$ttpoModel->state = 0;
//			var_dump($ttpoModel);exit;
			$ttpoModel->save ();

			Logs::writeLogs ( '创建过户信息', $ttpoModel ,'ttopzongdi');

			$Oldmodel = $this->findModel($ttpoModel->oldfarms_id);
			$Oldmodel->locked = 1;
			$Oldmodel->save();

			Logs::writeLogs ( '农场冻结', $Oldmodel );

			$Newmocel = $this->findModel($ttpoModel->newfarms_id);
			$Newmocel->locked = 1;
			$Newmocel->save();
			Logs::writeLogs ( '农场冻结', $Newmocel );
			Zongdioffarm::zongdiUpdate($ttpoModel->oldfarms_id, $ttpoModel->oldnewfarms_id,$ttpoModel->oldchangezongdi);
			Zongdioffarm::zongdiUpdate($ttpoModel->newfarms_id, $ttpoModel->newnewfarms_id,$ttpoModel->newchangezongdi);
			$newAttr = $newmodel->attributes;

			$oldFarm = Farms::find()->where(['id'=>$ttpoModel->oldfarms_id])->one();
			$newFarm = Farms::find()->where(['id'=>$ttpoModel->newnewfarms_id])->one();
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
		Logs::writeLog ( '更新过户信息',$id );
		$ttpoModel = Ttpozongdi::findOne($id);
		$oldmodel = Farms::findOne($ttpoModel->oldfarms_id);
		$oldnewmodel = Farms::findOne($ttpoModel->oldnewfarms_id);
		$newnewmodel = Farms::findOne($ttpoModel->newnewfarms_id);
		$post = Yii::$app->request->post ();
		if ($post) {
			$oldnewmodel->measure = $post['oldmeasure'];
			$oldnewmodel->zongdi = $post ['oldzongdichange'];			
			$oldnewmodel->update_at = time();
			$oldnewmodel->notclear = $post['oldnotclear'];
			$oldnewmodel->notstate = $post['oldnotstate'];
			$oldnewmodel->contractarea = Farms::getContractnumberArea($post['oldcontractnumber']);
			$oldnewmodel->contractnumber = $post['oldcontractnumber'];
			$oldnewmodel->save();
			Logs::writeLogs ( '更新农场信息', $oldnewmodel );
			$newnewmodel->update_at = time();
			$newnewmodel->zongdi = Farms::zongdiHB($post['Farms']['zongdi']);
			$newnewmodel->measure = $post['Farms']['measure'];
			$newnewmodel->notclear = $post['Farms']['notclear'];
			$newnewmodel->notstate = $post['Farms']['notstate'];
			$newnewmodel->contractarea = Farms::getContractnumberArea($post['Farms']['contractnumber']);
			$newnewmodel->contractnumber = $post['Farms']['contractnumber'];
			$newnewmodel->save();
			Logs::writeLogs ( '更新农场信息', $newnewmodel );
			$ttpoModel->create_at = (string)time ();
// 			var_dump($post['Farms']);exit;
			if($ttpoModel->actionname !== 'farmstransfer') {
				//原转让改变的信息
				$ttpoModel->oldchangezongdi = Farms::zongdiHB($post['oldzongdichange']);
				$ttpoModel->oldchangemeasure = $post['oldmeasure'];
				$ttpoModel->oldchangenotclear = $post['oldnotclear'];
				$ttpoModel->oldchangenotstate = $post['oldnotstate'];
				$ttpoModel->oldchangecontractnumber = $post['oldcontractnumber'];
			}
			//新转让改变信息
			$ttpoModel->newchangezongdi = Farms::zongdiHB($post['Farms']['zongdi']);
			$ttpoModel->newchangemeasure = $post['Farms']['measure'];
			$ttpoModel->newchangenotclear = $post['Farms']['notclear'];
			$ttpoModel->newchangenotstate = $post['Farms']['notstate'];
			$ttpoModel->newchangecontractnumber = $post['Farms']['contractnumber'];
			
			$ttpoModel->ttpozongdi = $post['ttpozongdi'];
			$ttpoModel->ttpoarea = $post['ttpoarea'];
			
			$ttpoModel->state = 0;
			$ttpoModel->save ();
			Logs::writeLogs ( '更新过户信息', $ttpoModel ,'ttopzongdi');
			
			Zongdioffarm::zongdiUpdate($ttpoModel->oldfarms_id, $ttpoModel->oldnewfarms_id,$ttpoModel->oldchangezongdi);
			Zongdioffarm::zongdiUpdate($ttpoModel->newfarms_id, $ttpoModel->newnewfarms_id,$ttpoModel->newchangezongdi);
			return $this->redirect ([
					'farmsttpozongdiview',
					'id'=> $ttpoModel->id,
					'farms_id'=>$ttpoModel->oldfarms_id,
					'ttpoModel' => $ttpoModel,
					'oldFarm' => $oldnewmodel,
					'newFarm' => $newnewmodel,
			]);
		} else {
			return $this->render ( 'farmsttpoupdatefarmstozongdi', [
					'model' => $oldnewmodel,
					'nowModel' => $newnewmodel,
					'ttpoModel' => $ttpoModel,
					'farms_id' => $ttpoModel->oldfarms_id,
			] );
		}
	}
	public function actionFarmsttpoupdatefarmstransfer($id)
	{
		Logs::writeLog ( '更新过户信息',$id );
		$ttpoModel = Ttpozongdi::findOne($id);
		$oldmodel = Farms::findOne($ttpoModel->oldfarms_id);
		$newnewmodel = Farms::findOne($ttpoModel->newnewfarms_id);
		$post = Yii::$app->request->post ();
		if ($post) {

			$newnewmodel->update_at = time();
			$newnewmodel->zongdi = $post['Farms']['zongdi'];
			$newnewmodel->measure = $post['Farms']['measure'];
			$newnewmodel->notclear = $post['Farms']['notclear'];
			$newnewmodel->notstate = $post['Farms']['notstate'];
			$newnewmodel->contractarea = Farms::getContractnumberArea($post['Farms']['contractnumber']);
			$newnewmodel->contractnumber = $post['Farms']['contractnumber'];
			$newnewmodel->save();
			Logs::writeLogs ( '更新农场信息', $newnewmodel );

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
			$ttpoModel->newchangezongdi = Farms::zongdiHB($post['Farms']['zongdi']);
			$ttpoModel->newchangemeasure = $post['Farms']['measure'];
			$ttpoModel->newchangenotclear = $post['Farms']['notclear'];
			$ttpoModel->newchangenotstate = $post['Farms']['notstate'];
			$ttpoModel->newchangecontractnumber = $post['Farms']['contractnumber'];
				
			$ttpoModel->ttpozongdi = $post['ttpozongdi'];
			$ttpoModel->ttpoarea = $post['ttpoarea'];
				
			$ttpoModel->state = 0;
			$ttpoModel->save ();
			Logs::writeLogs ( '更新过户信息', $ttpoModel ,'ttopzongdi');
			Zongdioffarm::zongdiUpdate($ttpoModel->oldfarms_id,$ttpoModel->newnewfarms_id, $ttpoModel->newchangezongdi);

			return $this->redirect ([
					'farmsttpozongdiview',
					'id'=> $ttpoModel->id,
					'farms_id'=>$ttpoModel->oldfarms_id,
					'ttpoModel' => $ttpoModel,
					'oldFarm' => $oldmodel,
					'newFarm' => $newnewmodel,
			]);
		} else {
			return $this->render ( 'farmsttpoupdatefarmstransfer', [
					'model' => $oldmodel,
					'nowModel' => $newnewmodel,
					'ttpoModel' => $ttpoModel,
					'farms_id' => $ttpoModel->oldfarms_id,
			] );
		}
	}
//整体转让合并修改页面
	public function actionFarmsttpoupdatefarmstransfermergecontract($id)
	{
		Logs::writeLog ( '更新整体合并过户信息',$id );
		$ttpoModel = Ttpozongdi::findOne($id);
		$oldmodel = Farms::findOne($ttpoModel->oldfarms_id);
		$newnewmodel = Farms::findOne($ttpoModel->newnewfarms_id);
		$post = Yii::$app->request->post ();
		if ($post) {

			$newnewmodel->update_at = time();
			$newnewmodel->zongdi = Farms::zongdiHB($post['Farms']['zongdi']);
			$newnewmodel->measure = $post['Farms']['measure'];
			$newnewmodel->notclear = $post['Farms']['notclear'];
			$newnewmodel->notstate = $post['Farms']['notstate'];
			$newnewmodel->contractarea = Farms::getContractnumberArea($post['Farms']['contractnumber']);
			$newnewmodel->contractnumber = $post['Farms']['contractnumber'];
			$newnewmodel->save();
			Logs::writeLogs ( '更新农场信息', $newnewmodel );
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
//			var_dump($post);exit;
			$ttpoModel->newchangezongdi = Farms::zongdiHB($post['Farms']['zongdi']);
			$ttpoModel->newchangemeasure = $post['Farms']['measure'];
			$ttpoModel->newchangenotclear = $post['Farms']['notclear'];
			$ttpoModel->newchangenotstate = $post['Farms']['notstate'];
			$ttpoModel->newchangecontractnumber = $post['Farms']['contractnumber'];

			$ttpoModel->ttpozongdi = $post['ttpozongdi'];
			$ttpoModel->ttpoarea = $post['ttpoarea'];

			$ttpoModel->state = 0;
			$ttpoModel->save ();
			Logs::writeLogs ( '更新过户信息', $ttpoModel ,'ttpozongdi');
			Zongdioffarm::zongdiUpdate($ttpoModel->oldfarms_id,$ttpoModel->newnewfarms_id, $ttpoModel->newchangezongdi);
			return $this->redirect ([
				'farmsttpozongdiview',
				'id'=> $ttpoModel->id,
				'farms_id'=>$ttpoModel->oldfarms_id,
				'ttpoModel' => $ttpoModel,
				'oldFarm' => $oldmodel,
				'newFarm' => $newnewmodel,
			]);
		} else {
			return $this->render ( 'farmsttpoupdatefarmstransfermergecontract', [
				'oldFarm' => $oldmodel,
				'newFarm' => $newnewmodel,
				'ttpoModel' => $ttpoModel,
				'farms_id' => $ttpoModel->oldfarms_id,
			] );
		}
	}

	public function actionFarmsttpoupdatefarmssplit($id)
	{
		Logs::writeLog ( '更新部分过户信息',$id );
		$ttpoModel = Ttpozongdi::findOne($id);
		$oldmodel = Farms::findOne($ttpoModel->oldfarms_id);
		$oldnewmodel = Farms::findOne($ttpoModel->oldnewfarms_id);
		$newnewmodel = Farms::findOne($ttpoModel->newnewfarms_id);
		$post = Yii::$app->request->post ();
		if ($post) {
			
			$oldnewmodel->measure = $post['oldmeasure'];
			$oldnewmodel->zongdi = $post ['oldzongdichange'];			
			$oldnewmodel->update_at = time();
			$oldnewmodel->notclear = $post['oldnotclear'];
			$oldnewmodel->notstate = $post['oldnotstate'];
			$oldnewmodel->contractarea = Farms::getContractnumberArea($post['oldcontractnumber']);
			$oldnewmodel->contractnumber = $post['oldcontractnumber'];
			$oldnewmodel->save();
			Logs::writeLogs ( '更新农场信息', $oldnewmodel );
			$newnewmodel->update_at = time();
			$newnewmodel->zongdi = Farms::zongdiHB($post['Farms']['zongdi']);
			$newnewmodel->measure = $post['Farms']['measure'];
			$newnewmodel->notclear = $post['Farms']['notclear'];
			$newnewmodel->notstate = $post['Farms']['notstate'];
			$newnewmodel->contractarea = Farms::getContractnumberArea($post['Farms']['contractnumber']);
			$newnewmodel->contractnumber = $post['Farms']['contractnumber'];
			$newnewmodel->save();
			Logs::writeLogs ( '更新农场信息', $newnewmodel );
			$ttpoModel->create_at = (string)time ();
			// 			var_dump($post['Farms']);exit;
			// 			if($ttpoModel->actionname !== 'farmstransfer') {
//			 				原转让改变的信息
			$ttpoModel->oldchangezongdi = Farms::zongdiHB($post['oldzongdichange']);
			$ttpoModel->oldchangemeasure = $post['oldmeasure'];
			$ttpoModel->oldchangenotclear = $post['oldnotclear'];
			$ttpoModel->oldchangenotstate = $post['oldnotstate'];
			$ttpoModel->oldchangecontractnumber = $post['oldcontractnumber'];
			// 			}
			//新转让改变信息
			$ttpoModel->newchangezongdi = Farms::zongdiHB($post['Farms']['zongdi']);
			$ttpoModel->newchangemeasure = $post['Farms']['measure'];
			$ttpoModel->newchangenotclear = $post['Farms']['notclear'];
			$ttpoModel->newchangenotstate = $post['Farms']['notstate'];
			$ttpoModel->newchangecontractnumber = $post['Farms']['contractnumber'];
	
			$ttpoModel->ttpozongdi = $post['ttpozongdi'];
			$ttpoModel->ttpoarea = $post['ttpoarea'];
	
			$ttpoModel->state = 0;
			$ttpoModel->save ();
			Logs::writeLogs ( '更新过户信息', $ttpoModel ,'ttpozongdi');
			Zongdioffarm::zongdiUpdate($ttpoModel->oldfarms_id,$ttpoModel->newnewfarms_id, $ttpoModel->newchangezongdi);
			return $this->redirect ([
					'farmsttpozongdiview',
					'id'=> $ttpoModel->id,
					'farms_id'=>$ttpoModel->oldfarms_id,
					'ttpoModel' => $ttpoModel,
					'oldFarm' => $oldmodel,
					'newFarm' => $newnewmodel,
			]);
		} else {
			return $this->render ( 'farmsttpoupdatefarmssplit', [
					'model' => $oldmodel,
					'nowModel' => $newnewmodel,
					'ttpoModel' => $ttpoModel,
					'farms_id' => $ttpoModel->oldfarms_id,
			] );
		}
	}

	//获取新增合同流水号并锁定流水号
	public function actionFarmsautonumber($farms_id)
	{
		$number = Numberlock::getNumber($farms_id,'number');
		echo json_decode($number);
	}

	// 农场转让——新增
	public function actionFarmssplit($farms_id) {
		Logs::writeLog ( '农场部分转让', $farms_id );
		$oldmodel = $this->findModel ( $farms_id );
		$old = $oldmodel->attributes;
		$oldzongdi = $oldmodel->zongdi;
		$oldcontractnumber = $oldmodel->contractnumber;
		$oldmeasure = $oldmodel->measure;
		$newmodel = new Farms();
		// $ttpoModel = Ttpo::find()->orWhere(['oldfarms_id'=>$farms_id])->orWhere(['newfarms_id'=>$farms_id])->all();
		// $ttpozongdiModel = Ttpozongdi::find()->orWhere(['oldfarms_id'=>$farms_id])->orWhere(['newfarms_id'=>$farms_id])->all();
		// 原农场转让宗地后，重新签订合同后，生成新的农场信息
		$post = Yii::$app->request->post ();
		if ($post) {
			$oldmodel->locked = 1;
			$oldmodel->save();
			Logs::writeLogs('冻结农场',$oldmodel);
			if ($newmodel->load ( Yii::$app->request->post () )) {
				// var_dump($newmodel->zongdi);exit;
// 				$newmodel->farmname = $newmodel->farmname;
// 				$newmodel->farmername = $newmodel->farmername;
// 				$newmodel->cardid = $newmodel->cardid;
// 				$newmodel->telephone = $newmodel->telephone;
// 				$newmodel->address = $oldmodel->address;
				$newmodel->management_area = $oldmodel->management_area;
				$newmodel->measure = $newmodel->measure;
				$newmodel->zongdi = Farms::zongdiHB($newmodel->zongdi);
				$newmodel->begindate = $oldmodel->begindate;
				$newmodel->enddate = $oldmodel->enddate;
				$newmodel->latitude = $newmodel->latitude;
				$newmodel->longitude = $newmodel->longitude;
				$newmodel->create_at = time ();
				$newmodel->update_at = $newmodel->create_at;
				$newmodel->pinyin = Pinyin::encode($newmodel->farmname);
				$newmodel->farmerpinyin = Pinyin::encode($newmodel->farmername);
				$newmodel->contractnumber = $newmodel->contractnumber;
				$newmodel->contractarea = $newmodel->contractarea;
				$newmodel->state = 0;
				$newmodel->locked = 0;
				$newmodel->notclear = $newmodel->notclear;
				$newmodel->remarks = $newmodel->remarks;
				$newmodel->save ();
				$cn = Contractnumber::now();
				if($cn == Farms::getContractserialnumber($newmodel->id)) {
					Contractnumber::contractnumberAdd();
				}
				Logs::writeLogs('新增农场',$newmodel);
				$new = $newmodel->attributes;
// 				Parcel::parcelState(['farms_id'=>$newmodel->id,'zongdi'=>$newmodel->zongdi,'state'=>true]);
			}
// 			var_dump($post);exit;
			$lockedinfoModel = new Lockedinfo ();
			$lockedinfoModel->farms_id = $farms_id;
			$lockedinfoModel->lockedcontent = '部分过户审核中，已被冻结。';
			$lockedinfoModel->save();
			Logs::writeLogs('创建部分过户冻结信息',$lockedinfoModel,'lockedinfo');
			// $oldmodel->state = 1;
			$oldmodelmodfiy = $this->findModel ( $farms_id );
			$oldmodelmodfiy->update_at = time ();
			$oldmodelmodfiy->locked = 1;
			$oldmodelmodfiy->save ();
			Logs::writeLogs('冻结农场',$oldmodelmodfiy);
			$newoldmodel = new Farms();
			$newoldmodel->farmname = $oldmodel->farmname;
			$newoldmodel->farmername = $oldmodel->farmername;
			$newoldmodel->cardid = $oldmodel->cardid;
			$newoldmodel->telephone = $oldmodel->telephone;
			$newoldmodel->address = $oldmodel->address;
			$newoldmodel->management_area = $oldmodel->management_area;
			$newoldmodel->spyear = $oldmodel->spyear;
			$newoldmodel->measure = Yii::$app->request->post ('oldmeasure');
			$newoldmodel->zongdi = Yii::$app->request->post ('oldzongdichange');
			$newoldmodel->cooperative_id = $oldmodel->cooperative_id;
			$newoldmodel->surveydate = $oldmodel->surveydate;
			$newoldmodel->groundsign = $oldmodel->groundsign;
			$newoldmodel->farmersign = $oldmodel->farmersign;
			$newoldmodel->create_at = $oldmodel->create_at;
			$newoldmodel->pinyin = Pinyin::encode($oldmodel->farmname);
			$newoldmodel->farmerpinyin = Pinyin::encode($oldmodel->farmername);
			$newoldmodel->begindate = date('Y-m-d');
			$newoldmodel->enddate = $oldmodel->enddate;
			$newoldmodel->oldfarms_id = $oldmodel->id;
			$newoldmodel->latitude = $newmodel->latitude;
			$newoldmodel->longitude = $newmodel->longitude;
			$newoldmodel->accountnumber = $oldmodel->accountnumber;
			$newoldmodel->remarks = $oldmodel->remarks;
			$newoldmodel->create_at = time();
			$newoldmodel->update_at = $newoldmodel->create_at;
			$newoldmodel->notclear = $post['oldnotclear'];
			$newoldmodel->notstate = $post['oldnotstate'];
			$newoldmodel->contractarea = Farms::getContractnumberArea($post['oldcontractnumber']);
			$newoldmodel->contractnumber = $post['oldcontractnumber'];
			$newoldmodel->state = 0;
			$newoldmodel->locked = 0;
			$newoldmodel->save();

			Logs::writeLogs('新增农场',$newoldmodel);

			
			$ttpoModel = new Ttpozongdi ();
			$ttpoModel->management_area = $oldmodel->management_area;
			$ttpoModel->oldfarms_id = $oldmodel->id;
			$ttpoModel->newfarms_id = '';
			$ttpoModel->oldnewfarms_id = $newoldmodel->id;
			$ttpoModel->newnewfarms_id = $newmodel->id;;
			$ttpoModel->create_at = (string)time ();
			//新转让的信息
			$ttpoModel->oldzongdi = $old['zongdi'];
			$ttpoModel->oldmeasure = $old['measure'];
			$ttpoModel->oldnotclear = $old['notclear'];
			$ttpoModel->oldnotstate = $old['notstate'];
			$ttpoModel->oldcontractnumber = $old['contractnumber'];
			//原转让改变的信息
			$ttpoModel->oldchangezongdi =  Farms::zongdiHB($newoldmodel->zongdi);
			$ttpoModel->oldchangemeasure = $newoldmodel->measure;
			$ttpoModel->oldchangenotclear = $newoldmodel->notclear;
			$ttpoModel->oldchangenotstate = $newoldmodel->notstate;
			$ttpoModel->oldchangecontractnumber = $newoldmodel->contractnumber;
				
			$ttpoModel->auditprocess_id = 1;
			//新转让信息
			$ttpoModel->newzongdi = '';
			$ttpoModel->newmeasure = 0;
			$ttpoModel->newnotclear = 0;
			$ttpoModel->newnotstate = 0;
			$ttpoModel->newcontractnumber = '';
			//新转让改变信息
 			$ttpoModel->newchangezongdi = Farms::zongdiHB($newmodel->zongdi);
 			$ttpoModel->newchangemeasure = $newmodel->measure;
 			$ttpoModel->newchangenotclear = $newmodel->notclear;
 			$ttpoModel->newchangenotstate = $newmodel->notstate;
 			$ttpoModel->newchangecontractnumber = $newmodel->contractnumber;
				
			$ttpoModel->ttpozongdi = Farms::zongdiHB(Yii::$app->request->post ( 'ttpozongdi' ));
			$ttpoModel->ttpoarea = Yii::$app->request->post ( 'ttpoarea' );
			$ttpoModel->actionname = \Yii::$app->controller->action->id;
			$ttpoModel->state = 0;
			$ttpoModel->samefarms_id = $ttpoModel->oldfarms_id;
			$ttpoModel->save ();
			Logs::writeLogs('新增农场过户信息',$ttpoModel,'ttpoozongdi');

			Zongdioffarm::zongdiUpdate($oldmodel->id, $newoldmodel->id, $ttpoModel->oldchangezongdi);
			Zongdioffarm::zongdiUpdate('',$newmodel->id,$ttpoModel->newchangezongdi);
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

	public function actionFarmssplitcontinue($samefarms_id,$farms_id,$oldfarms_id,$ttpozongdi_id) {
		Logs::writeLog('分户',$samefarms_id);
		$oldmodel = $this->findModel ( $farms_id );
		$oldmodel->tempdata = 1;
		$oldmodel->save();
		$firstttpoModel = Ttpozongdi::findOne($ttpozongdi_id);
		$old = $oldmodel->attributes;
		$oldzongdi = $oldmodel->zongdi;
		$oldcontractnumber = $oldmodel->contractnumber;
		$oldmeasure = $oldmodel->measure;
		$newmodel = new Farms();
		// $ttpoModel = Ttpo::find()->orWhere(['oldfarms_id'=>$farms_id])->orWhere(['newfarms_id'=>$farms_id])->all();
		// $ttpozongdiModel = Ttpozongdi::find()->orWhere(['oldfarms_id'=>$farms_id])->orWhere(['newfarms_id'=>$farms_id])->all();
		// 原农场转让宗地后，重新签订合同后，生成新的农场信息
		$post = Yii::$app->request->post ();
		if ($post) {
			$firstttpoModel->actionname = 'farmssplitcontinue';
			$firstttpoModel->save();
			Logs::writeLogs('变更转让信息为分户',$firstttpoModel,'ttpozongdi');
// 			var_dump($post);exit;
			$lockedinfoModel = new Lockedinfo ();
			$lockedinfoModel->farms_id = $farms_id;
			$lockedinfoModel->lockedcontent = '部分过户审核中，已被冻结。';
			$lockedinfoModel->save();
			Logs::writeLogs('创建部分过户冻结信息',$lockedinfoModel,'lockedinfo');
			// $oldmodel->state = 1;
			$oldmodelmodfiy = $this->findModel ( $farms_id );
			$oldmodelmodfiy->update_at = time ();
			$oldmodelmodfiy->locked = 1;
			$oldmodelmodfiy->save ();
			Logs::writeLogs('冻结农场',$oldmodelmodfiy);
			$newoldmodel = new Farms();
			$newoldmodel->farmname = $oldmodel->farmname;
			$newoldmodel->farmername = $oldmodel->farmername;
			$newoldmodel->cardid = $oldmodel->cardid;
			$newoldmodel->telephone = $oldmodel->telephone;
			$newoldmodel->address = $oldmodel->address;
			$newoldmodel->management_area = $oldmodel->management_area;
			$newoldmodel->spyear = $oldmodel->spyear;
			$newoldmodel->measure = Yii::$app->request->post ('oldmeasure');
			$newoldmodel->zongdi = Farms::zongdiHB(Yii::$app->request->post ('oldzongdichange'));
			$newoldmodel->cooperative_id = $oldmodel->cooperative_id;
			$newoldmodel->surveydate = $oldmodel->surveydate;
			$newoldmodel->groundsign = $oldmodel->groundsign;
			$newoldmodel->farmersign = $oldmodel->farmersign;
			$newoldmodel->create_at = $oldmodel->create_at;
			$newoldmodel->pinyin = Pinyin::encode($oldmodel->farmname);
			$newoldmodel->farmerpinyin = Pinyin::encode($oldmodel->farmername);
			$newoldmodel->begindate = $oldmodel->begindate;
			$newoldmodel->enddate = $oldmodel->enddate;
			$newoldmodel->oldfarms_id = $oldmodel->id;
			$newoldmodel->latitude = $oldmodel->latitude;
			$newoldmodel->longitude = $oldmodel->longitude;
			$newoldmodel->accountnumber = $oldmodel->accountnumber;
			$newoldmodel->remarks = $oldmodel->remarks;
			$newoldmodel->create_at = time();
			$newoldmodel->update_at = $newoldmodel->create_at;
			$newoldmodel->notclear = $post['oldnotclear'];
			$newoldmodel->notstate = $post['oldnotstate'];
			$newoldmodel->contractarea = Farms::getContractnumberArea($post['oldcontractnumber']);
			$newoldmodel->contractnumber = $post['oldcontractnumber'];
//			$newoldmodel->notstateinfo = 9;
			$newoldmodel->state = 0;
			$newoldmodel->locked = 0;
			if($newoldmodel->contractarea == 0) {
				$newoldmodel->tempdata = 1;
			}
			$newoldmodel->save();
			Logs::writeLogs('新增农场',$newoldmodel);
			$oldmodel->locked = 1;
			//判断是否为临时数据
			$oldmodel->tempdata = 1;
//			$oldmodel->notstateinfo = 9;
			$oldmodel->save();
			Logs::writeLogs('冻结农场并指定临时数据',$oldmodel);
// 			var_dump($newoldmodel->getErrors());exit;

			if ($newmodel->load ( Yii::$app->request->post () )) {
				// var_dump($newmodel->zongdi);exit;
				$newmodel->farmname = $newmodel->farmname;
				$newmodel->farmername = $newmodel->farmername;
				$newmodel->cardid = $newmodel->cardid;
				$newmodel->telephone = $newmodel->telephone;
				$newmodel->address = $newmodel->address;
				$newmodel->management_area = $oldmodel->management_area;
				$newmodel->measure = $newmodel->measure;
				$newmodel->zongdi = Farms::zongdiHB($newmodel->zongdi);
				$newmodel->begindate = $oldmodel->begindate;
				$newmodel->enddate = $oldmodel->enddate;
				$newmodel->latitude = $newmodel->latitude;
				$newmodel->longitude = $newmodel->longitude;
				$newmodel->create_at = time ();
				$newmodel->update_at = $newmodel->create_at;
				$newmodel->pinyin = Pinyin::encode($newmodel->farmname);
				$newmodel->farmerpinyin = Pinyin::encode($newmodel->farmername);
				$newmodel->contractnumber = $newmodel->contractnumber;
				$newmodel->contractarea = $newmodel->contractarea;
//				$newmodel->notstateinfo = 9;
				$newmodel->state = 0;
				$newmodel->locked = 0;
				$newmodel->notclear = $newmodel->notclear;
				$newmodel->remarks = $newmodel->remarks;
				$newmodel->save ();
				$cn = Contractnumber::now();
				if($cn == Farms::getContractserialnumber($newmodel->id)) {
					Contractnumber::contractnumberAdd();
				}
				Logs::writeLogs('新增农场',$newmodel);
				$new = $newmodel->attributes;
// 				Parcel::parcelState(['farms_id'=>$newmodel->id,'zongdi'=>$newmodel->zongdi,'state'=>true]);
			}

			$ttpoModel = new Ttpozongdi ();
			$ttpoModel->management_area = $oldmodel->management_area;
			$ttpoModel->oldfarms_id = $oldmodel->id;
			$ttpoModel->newfarms_id = '';
			$ttpoModel->oldnewfarms_id = $newoldmodel->id;
			$ttpoModel->newnewfarms_id = $newmodel->id;;
			$ttpoModel->create_at = (string)time ();
			//新转让的信息
			$ttpoModel->oldzongdi = $old['zongdi'];
			$ttpoModel->oldmeasure = $old['measure'];
			$ttpoModel->oldnotclear = $old['notclear'];
			$ttpoModel->oldnotstate = $old['notstate'];
			$ttpoModel->oldcontractnumber = $firstttpoModel->oldcontractnumber;
			//原转让改变的信息
			$ttpoModel->oldchangezongdi =  Farms::zongdiHB($newoldmodel->zongdi);
			$ttpoModel->oldchangemeasure = $newoldmodel->measure;
			$ttpoModel->oldchangenotclear = $newoldmodel->notclear;
			$ttpoModel->oldchangenotstate = $newoldmodel->notstate;
			$ttpoModel->oldchangecontractnumber = $newoldmodel->contractnumber;

			$ttpoModel->auditprocess_id = 1;
			//新转让信息
			$ttpoModel->newzongdi = '';
			$ttpoModel->newmeasure = 0;
			$ttpoModel->newnotclear = 0;
			$ttpoModel->newnotstate = 0;
			$ttpoModel->newcontractnumber = '';
			//新转让改变信息
			$ttpoModel->newchangezongdi = Farms::zongdiHB($newmodel->zongdi);
			$ttpoModel->newchangemeasure = $newmodel->measure;
			$ttpoModel->newchangenotclear = $newmodel->notclear;
			$ttpoModel->newchangenotstate = $newmodel->notstate;
			$ttpoModel->newchangecontractnumber = $newmodel->contractnumber;

			$ttpoModel->ttpozongdi = Yii::$app->request->post ( 'ttpozongdi' );
			$ttpoModel->ttpoarea = Yii::$app->request->post ( 'ttpoarea' );
			$ttpoModel->actionname = \Yii::$app->controller->action->id;
			$ttpoModel->state = 0;
			//
			$ttpoModel->samefarms_id = $samefarms_id;
			$ttpoModel->save ();
			Logs::writeLogs('创建农场转让信息',$ttpoModel,'ttopzongdi');

			Zongdioffarm::zongdiUpdate($oldmodel->id, $newoldmodel->id, $ttpoModel->oldchangezongdi);
			Zongdioffarm::zongdiUpdate('',$newmodel->id,$ttpoModel->newchangezongdi);
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

			return $this->render ( 'farmssplitcontinue', [
				'oldFarm' => $oldmodel,
				'newFarm' => $newmodel,
				'ttpoModel' => $firstttpoModel,
			] );
		}
	}

	// 农场转让
	public function actionFarmsttpotransfer($farms_id) {
		Logs::writeLog('过户转让',$farms_id);
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
		Logs::writeLog('部分合并查询',$farms_id);
		$management_area = Farms::getManagementArea();
		$searchModel = new farmsSearch ();

		$params = Yii::$app->request->queryParams;
		$params['farmsSearch']['management_area'] = $management_area['id'];
//		$params['farmsSearch']['state'] = [1,2,3,4];
//		$params['farmsSearch']['locked'] = 0;
		$dataProvider = $searchModel->search ( $params );

		return $this->render ( 'farmsttpozongdi', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'farms_id' => $farms_id
		] );
	}
	// 农场转让，整体合并
	public function actionFarmstransfermerge($farms_id)
	{
		Logs::writeLog('整体合并查询',$farms_id);
		$management_area = Farms::getManagementArea();
		$searchModel = new farmsSearch ();

		$params = Yii::$app->request->queryParams;
		$params['farmsSearch']['management_area'] = $management_area['id'];
		$params['farmsSearch']['state'] = 1;
//		$params['farmsSearch']['locked'] = 0;
		$dataProvider = $searchModel->search ( $params );
// 		var_dump($dataProvider);exit;
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
		Logs::writeLog('部分转让-合并',$farms_id);
		$oldmodel = $this->findModel ( $farms_id );
		$old = $oldmodel->attributes;
		$newmodel = $this->findModel($newfarms_id);
		$new = $newmodel->attributes;
		
		$oldzongdi = $oldmodel->zongdi;
		$oldcontractnumber = $oldmodel->contractnumber;
		$oldmeasure = $oldmodel->measure;

// 		var_dump($new);exit;
		$zongditemp = $newmodel->zongdi;
		$lockedinfoModel = new Lockedinfo ();
		$lockedinfoModel->farms_id = $farms_id;
		$lockedinfoModel->lockedcontent = '部分过户审核中，已被冻结。';
		$lockedinfoModel->save();
		Logs::writeLogs('创建部分过户冻结信息',$lockedinfoModel,'lockedinfo');
		$lockedinfoModel = new Lockedinfo ();
		$lockedinfoModel->farms_id = $newmodel->id;
		$lockedinfoModel->lockedcontent = '部分过户审核中，已被冻结。';
		$lockedinfoModel->save();
		Logs::writeLogs('创建部分过户冻结信息',$lockedinfoModel,'lockedinfo');
		// $ttpoModel = Ttpo::find()->orWhere(['oldfarms_id'=>$farms_id])->orWhere(['newfarms_id'=>$farms_id])->all();
		// $ttpozongdiModel = Ttpozongdi::find()->orWhere(['oldfarms_id'=>$farms_id])->orWhere(['newfarms_id'=>$farms_id])->all();
		// 原农场转让宗地后，重新签订合同后，生成新的农场信息
		$post = Yii::$app->request->post ();
		if ($post) {
//			var_dump($post);exit;
			$oldmodel->update_at = time ();
			$oldmodel->locked = 1;
			$oldmodel->tempdata = 1;
			$oldmodel->save ();
			Logs::writeLogs('冻结农场',$oldmodel);
//			var_dump($post);exit;
			if(empty($newmodel->cardid)) {
				$newmodel->cardid = $post['Farms']['cardid'];
			}
			if(empty($newmodel->telephone)) {
				$newmodel->telephone = $post['Farms']['telephone'];
			}
			if(empty($newmodel->address)) {
				$newmodel->address = $post['Farms']['address'];
			}
			if(empty($newmodel->latitude)) {
				$newmodel->latitude = $post['Farms']['latitude'];
			}
			if(empty($newmodel->longitude)) {
				$newmodel->longitude = $post['Farms']['longitude'];
			}
			$newmodel->locked = 1;
			$newmodel->update_at = time();
			$newmodel->save ();
			Logs::writeLogs('冻结农场',$newmodel);
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
			$newoldmodel->create_at = time();
			$newoldmodel->pinyin = $oldmodel->pinyin;
			$newoldmodel->farmerpinyin = $oldmodel->farmerpinyin;
			$newoldmodel->begindate = $oldmodel->begindate;
			$newoldmodel->enddate = $oldmodel->enddate;
			$newoldmodel->oldfarms_id = $oldmodel->id;
			$newoldmodel->latitude = $oldmodel->latitude;
			$newoldmodel->longitude = $oldmodel->longitude;
			$newoldmodel->accountnumber = $oldmodel->accountnumber;
			$newoldmodel->remarks = $oldmodel->remarks;
			$newoldmodel->update_at = $newoldmodel->create_at;
			$newoldmodel->measure = Yii::$app->request->post ('oldmeasure');
			$newoldmodel->zongdi = Yii::$app->request->post ('oldzongdi');
			$newoldmodel->notclear = Yii::$app->request->post ('oldnotclear');
			$newoldmodel->notstate = Yii::$app->request->post ('oldnotstate');
			$newoldmodel->contractarea = Farms::getContractnumberArea(Yii::$app->request->post ('oldcontractnumber'));
			$newoldmodel->contractnumber = Yii::$app->request->post ('oldcontractnumber');
			$newoldmodel->state = 0;
			$newoldmodel->locked = 0;
			$newoldmodel->begindate = date('Y-m-d');
			$newoldmodel->enddate = '2025-09-13';
			$newoldmodel->save();
			Logs::writeLogs('新增农场',$newoldmodel);
// 			var_dump($newoldmodel->getErrors());exit;

			$newnewmodel = new Farms();
			
			$newnewmodel->farmname = $newmodel->farmname;
			$newnewmodel->farmername = $newmodel->farmername;
			$newnewmodel->cardid = $newmodel->cardid;
			$newnewmodel->telephone = $newmodel->telephone;
			$newnewmodel->address = $newmodel->address;
			$newnewmodel->management_area = $newmodel->management_area;
			$newnewmodel->spyear = $newmodel->spyear;
			$newnewmodel->measure = $post['Farms']['measure'];
			$newnewmodel->zongdi = Farms::zongdiHB($post['Farms']['zongdi']);
			$newnewmodel->cooperative_id = $newmodel->cooperative_id;
			$newnewmodel->surveydate = $newmodel->surveydate;
			$newnewmodel->groundsign = $newmodel->groundsign;
			$newnewmodel->farmersign = $newmodel->farmersign;
			$newnewmodel->create_at = time();
			$newnewmodel->pinyin = $newmodel->pinyin;
			$newnewmodel->farmerpinyin = $newmodel->farmerpinyin;
			$newnewmodel->begindate = $newmodel->begindate;
			$newnewmodel->enddate = $newmodel->enddate;
			$newnewmodel->oldfarms_id = $newmodel->id;
			$newnewmodel->latitude = $newmodel->latitude;
			$newnewmodel->longitude = $newmodel->longitude;
			$newnewmodel->accountnumber = $newmodel->accountnumber;
			$newnewmodel->remarks = $newmodel->remarks;
			$newnewmodel->update_at = $newnewmodel->create_at;
			$newnewmodel->notclear = $post['Farms']['notclear'];
			$newnewmodel->notstate = $post['Farms']['notstate'];
			$newnewmodel->contractarea = Farms::getContractnumberArea($post['Farms']['contractnumber']);
			$newnewmodel->contractnumber = $post['Farms']['contractnumber'];
			$newnewmodel->state = 0;
			$newnewmodel->locked = 0;
			$newnewmodel->begindate = date('Y-m-d');
			$newnewmodel->enddate = '2025-09-13';
			$newnewmodel->save();
			Logs::writeLogs('新增农场',$newnewmodel);
			
// 			var_dump($newnewmodel->getErrors());exit;
			$ttpoModel = new Ttpozongdi ();
			$ttpoModel->management_area = $oldmodel->management_area;
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
			
			$ttpoModel->oldchangezongdi =  Farms::zongdiHB($newoldmodel->zongdi);
			$ttpoModel->oldchangemeasure = $newoldmodel->measure;
			$ttpoModel->oldchangenotclear = $newoldmodel->notclear;
			$ttpoModel->oldchangenotstate = $newoldmodel->notstate;
			$ttpoModel->oldchangecontractnumber = $newoldmodel->contractnumber;
				
			$ttpoModel->auditprocess_id = 1;
			//新转让信息
			$ttpoModel->newzongdi = Farms::zongdiHB($new['zongdi']);
			$ttpoModel->newmeasure = $new['measure'];
			$ttpoModel->newnotclear = $new['notclear'];
			$ttpoModel->newnotstate = $new['notstate'];
			$ttpoModel->newcontractnumber = $new['contractnumber'];
			//新转让改变信息
			$ttpoModel->newchangezongdi = Farms::zongdiHB($newnewmodel->zongdi);
			$ttpoModel->newchangemeasure = $newnewmodel->measure;
			$ttpoModel->newchangenotclear = $newnewmodel->notclear;
			$ttpoModel->newchangenotstate = $newnewmodel->notstate;
			$ttpoModel->newchangecontractnumber = $newnewmodel->contractnumber;
				
			$ttpoModel->ttpozongdi = Yii::$app->request->post ( 'ttpozongdi' );
			$ttpoModel->ttpoarea = Yii::$app->request->post ( 'ttpoarea' );
			$ttpoModel->actionname = \Yii::$app->controller->action->id;
			$ttpoModel->state = 0;
			$ttpoModel->samefarms_id = $ttpoModel->oldfarms_id;
			$ttpoModel->save ();
			Logs::writeLogs('创建转让信息',$ttpoModel,'ttpozongdi');
// 			var_dump($_POST);
// 			var_dump($newmodel);exit;
			Contractnumber::contractnumberAdd ();
//			var_dump($ttpoModel);exit;
			Zongdioffarm::zongdiUpdate($oldmodel->id,$newoldmodel->id, $ttpoModel->oldchangezongdi);
			Zongdioffarm::zongdiUpdate($newmodel->id,$newnewmodel->id, $ttpoModel->newchangezongdi);
			// var_dump($ttpozongdi->getErrors());exit;
			return $this->redirect ([
					'farmsttpozongdiview',
					'id'=> $ttpoModel->id,
//					'farms_id'=>$ttpoModel->oldfarms_id,
//					'ttpoModel' => $ttpoModel->id,
//					'oldFarm' => $oldmodel,
//					'newFarm' => $newmodel,
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
			$model->update_at = $model->create_at;
// 			if($model->surveydate)
				$model->surveydate = (string)strtotime($model->surveydate);
			$model->pinyin = Pinyin::encode ( $model->farmname );
			$model->farmerpinyin = Pinyin::encode ( $model->farmername );
			$model->contractarea = Farms::getContractnumberArea($model->contractnumber);
// 			$model->save();
// 			var_dump($model);
// 			var_dump($model->getErrors());exit;
			if($model->save ()) {
				Logs::writeLogs('新增农场',$model);
				$farmerModel = new Farmer();
				$farmerModel->farms_id = $model->id;
				$farmerModel->save();
				Logs::writeLogs('新增法人',$farmerModel,'farmer');
				if($model->state < 3 and $model->state >0) {
					$collection = Collection::find()->where(['farms_id' => $model->id, 'payyear' => date('Y')])->one();

					if ($collection) {

						if ($collection['ypayarea'] !== $model->contractarea) {
							$collectionModel = Collection::findOne($collection['id']);
							$collectionModel->ypayarea = $model->contractarea;
							$collectionModel->ypaymoney = $collectionModel->getAR($collection['payyear'], $model->id);
							$collectionModel->amounts_receivable = $collectionModel->ypaymoney;
							$collectionModel->update_at = time();
							$collectionModel->save();
							Logs::writeLogs('更新农场缴费信息',$collectionModel,'collection');
						}
					} else {
						// 					var_dump('lll');exit;
						$collectionModel = new Collection();
						$collectionModel->create_at = time();
						$collectionModel->update_at = $collectionModel->create_at;
						$collectionModel->payyear = date('Y');
						$collectionModel->farms_id = $model->id;
						$collectionModel->amounts_receivable = $collectionModel->getAR($collectionModel->payyear, $model->id);
						$collectionModel->ypayarea = $model->contractarea;
						$collectionModel->ypaymoney = $collectionModel->amounts_receivable;
						$collectionModel->owe = 0.0;
						$collectionModel->dckpay = 0;
						$collectionModel->state = 0;
						$collectionModel->management_area = $model->management_area;
						$collectionModel->save();
						Logs::writeLogs('创建农场缴费信息',$collectionModel,'collection');
						// 					var_dump($collectionModel->getErrors());exit;
					}
				}
			}

			Zongdioffarm::zongdiUpdate($model->id,$model->id, $model->zongdi);
			return $this->redirect ( [ 
					'farmsview',
					'id' => $model->id 
			] );
		} else {
			// Logs::writeLogs('农场创建表单');
			return $this->render ( 'farmscreate', [ 
					'model' => $model 
			] );
		}
	}

	public function actionFarmsadmincreate() {
		$model = new farms ();

		if ($model->load ( Yii::$app->request->post () )) {
			$model->create_at = time ();
			$model->update_at = $model->create_at;
// 			if($model->surveydate)
			$model->surveydate = (string)strtotime($model->surveydate);
			$model->pinyin = Pinyin::encode ( $model->farmname );
			$model->farmerpinyin = Pinyin::encode ( $model->farmername );
			$model->contractarea = Farms::getContractnumberArea($model->contractnumber);
// 			$model->save();
// 			var_dump($model);
// 			var_dump($model->getErrors());exit;
			if($model->save ()) {
				Logs::writeLogs('新增农场',$model);
				$farmerModel = new Farmer();
				$farmerModel->farms_id = $model->id;
				$farmerModel->save();
				Logs::writeLogs('新增法人',$farmerModel,'farmer');
				if($model->state < 3 and $model->state >0) {
					$collection = Collection::find()->where(['farms_id' => $model->id, 'payyear' => date('Y')])->one();

					if ($collection) {

						if ($collection['ypayarea'] !== $model->contractarea) {
							$collectionModel = Collection::findOne($collection['id']);
							$collectionModel->ypayarea = $model->contractarea;
							$collectionModel->ypaymoney = $collectionModel->getAR($collection['payyear'], $model->id);
							$collectionModel->amounts_receivable = $collectionModel->ypaymoney;
							$collectionModel->update_at = time();
							$collectionModel->save();
							Logs::writeLogs('更新农场缴费信息',$collectionModel,'collection');
						}
					} else {
						// 					var_dump('lll');exit;
						$collectionModel = new Collection();
						$collectionModel->create_at = time();
						$collectionModel->update_at = $collectionModel->create_at;
						$collectionModel->payyear = date('Y');
						$collectionModel->farms_id = $model->id;
						$collectionModel->amounts_receivable = $collectionModel->getAR($collectionModel->payyear, $model->id);
						$collectionModel->ypayarea = $model->contractarea;
						$collectionModel->ypaymoney = $collectionModel->amounts_receivable;
						$collectionModel->owe = 0.0;
						$collectionModel->dckpay = 0;
						$collectionModel->state = 0;
						$collectionModel->management_area = $model->management_area;
						$collectionModel->save();
						Logs::writeLogs('创建农场缴费信息',$collectionModel,'collection');
						// 					var_dump($collectionModel->getErrors());exit;
					}
				}
			}

			Zongdioffarm::zongdiUpdate($model->id,$model->id, $model->zongdi);
			return $this->redirect ( [
				'farmsview',
				'id' => $model->id
			] );
		} else {
			// Logs::writeLogs('农场创建表单');
			return $this->render ( 'farmsadmincreate', [
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
	// Logs::writeLogs('农场转让信息',$farms_id,$oldAttr,$newAttr);
	
	// return $this->redirect(['farmsview', 'id' => $nowModel->id]);
	// } else {
	// return $this->render('farmsttpo', [
	// 'model' => $model,
	// 'nowModel' => $nowModel,
	// ]);
	// }
	// }

//	public function actionFarmsaccountnumber()
//	{
//		$searchModel = new farmsSearch ();
//		$whereArray = Farms::getManagementArea();
//		$params = Yii::$app->request->queryParams;
//		$params ['farmsSearch'] ['state'] = 1;
//		// 管理区域是否是数组
//		if (empty($params['farmsSearch']['mamagement_area'])) {
//			$params ['farmsSearch'] ['management_area'] = $whereArray['id'];
//		}
//		// var_dump($params);exit;
//		$dataProvider = $searchModel->search ( $params );
//		Logs::writeLogs ( '农场管理' );
//		return $this->render ( 'farmsaccountnumber', [
//				'searchModel' => $searchModel,
//				'dataProvider' => $dataProvider,
//
//		] );
//	}
	
	public function actionFarmsupdateaccountnumber($id)
	{
		$model = $this->findModel($id);
		if($model->load(Yii::$app->request->post()) && $model->save()) {
			Logs::writeLogs('更新帐页号',$model);
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

			if($model->save ()) {
				$cn = Contractnumber::now();
				if($cn == Farms::getContractserialnumber($id)) {
					Contractnumber::contractnumberAdd();
				}
				Logs::writeLogs('更新农场信息',$model);
				$collection = Collection::find()->where(['farms_id' => $id, 'payyear' => date('Y')])->one();
				if($model->state > 0 and $model->state < 3) {


					if ($collection) {

						if ($collection['ypayarea'] !== $model->contractarea) {
							$collectionModel = Collection::findOne($collection['id']);
							$collectionModel->ypayarea = $model->contractarea;
							$collectionModel->ypaymoney = $collectionModel->getAR($collection['payyear'], $id);
							$collectionModel->amounts_receivable = $collectionModel->ypaymoney;
							$collectionModel->update_at = time();
							$collectionModel->save();
							Logs::writeLogs('更新农场缴费信息',$collectionModel,'collection');
						}
					} else {
// 					var_dump('lll');exit;
						$collectionModel = new Collection();
						$collectionModel->create_at = time();
						$collectionModel->update_at = $collectionModel->create_at;
						$collectionModel->payyear = date('Y');
						$collectionModel->farms_id = $id;
						$collectionModel->amounts_receivable = $collectionModel->getAR($collectionModel->payyear, $id);
						$collectionModel->ypayarea = $model->contractarea;
						$collectionModel->ypaymoney = $collectionModel->amounts_receivable;
						$collectionModel->owe = 0.0;
						$collectionModel->dckpay = 0;
						$collectionModel->state = 0;
						$collectionModel->management_area = $model->management_area;
						$collectionModel->save();
						Logs::writeLogs('创建农场缴费信息',$collectionModel,'collection');
// 					var_dump($collectionModel->getErrors());exit;
					}
				} else {
					if($collection) {
						$collectionModel = Collection::findOne($collection['id']);
						$collectionModel->delete();
						Logs::writeLogs('删除缴费信息',$collectionModel,'collection');
					}
				}
			}
			Zongdioffarm::zongdiUpdate($id,$id, $model->zongdi);
			
			return $this->redirect ( [ 
					'farmsview',
					'id' => $model->id 
			] );
		} else {
			// Logs::writeLogs('农场更新表单');
			return $this->render ( 'farmsupdate', [ 
					'model' => $model 
			] );
		}
	}

	public function actionFarmsadminupdate($id) {
		$model = $this->findModel ( $id );
		$oldAttr = $model->attributes;
		if ($model->load ( Yii::$app->request->post () )) {
			$model->update_at = time ();
			$model->pinyin = Pinyin::encode ( $model->farmname );
			$model->farmerpinyin = Pinyin::encode ( $model->farmername );
			$model->surveydate = (string)strtotime($model->surveydate);
 			//var_dump($model);eixt;
			if($model->save ()) {
				Logs::writeLogs('更新农场信息',$model);
				$collection = Collection::find()->where(['farms_id' => $id, 'payyear' => date('Y')])->one();
				if($model->state > 0 and $model->state < 3) {


					if ($collection) {

						if ($collection['ypayarea'] !== $model->contractarea) {
							$collectionModel = Collection::findOne($collection['id']);
							$collectionModel->ypayarea = $model->contractarea;
							$collectionModel->ypaymoney = $collectionModel->getAR($collection['payyear'], $id);
							$collectionModel->amounts_receivable = $collectionModel->ypaymoney;
							$collectionModel->update_at = time();
							$collectionModel->save();
							Logs::writeLogs('更新农场缴费信息',$collectionModel,'collection');
						}
					} else {
// 					var_dump('lll');exit;
						$collectionModel = new Collection();
						$collectionModel->create_at = time();
						$collectionModel->update_at = $collectionModel->create_at;
						$collectionModel->payyear = date('Y');
						$collectionModel->farms_id = $id;
						$collectionModel->amounts_receivable = $collectionModel->getAR($collectionModel->payyear, $id);
						$collectionModel->ypayarea = $model->contractarea;
						$collectionModel->ypaymoney = $collectionModel->amounts_receivable;
						$collectionModel->owe = 0.0;
						$collectionModel->dckpay = 0;
						$collectionModel->state = 0;
						$collectionModel->management_area = $model->management_area;
						$collectionModel->save();
						Logs::writeLogs('创建农场缴费信息',$collectionModel,'collection');
// 					var_dump($collectionModel->getErrors());exit;
					}
				} else {
					if($collection) {
						$collectionModel = Collection::findOne($collection['id']);
						$collectionModel->delete();
						Logs::writeLogs('删除缴费信息',$collectionModel,'collection');
					}
				}
			}
			Zongdioffarm::zongdiUpdate($id,$id, $model->zongdi);

			return $this->redirect ( [
				'farmsadminview',
				'id' => $model->id
			] );
		} else {
			// Logs::writeLogs('农场更新表单');
			return $this->render ( 'farmsadminupdate', [
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
//		var_dump(Yii::$app->getUser()->getIdentity()->department_id);exit;
        $department = Department::findOne(Yii::$app->getUser()->getIdentity()->department_id);
//		$businessmenu = MenuToUser::find ()->where ( [
//				'role_id' => User::getItemname ()
//		] )->one ()['businessmenu'];
// 		var_dump($businessmenu);exit;
//		$arrayBusinessMenu = explode ( ',', $department->businessmenu );
        $arrayBusinessMenu = Mainmenu::find ()->where ( [
            'id' => explode ( ',', $department->businessmenu )
        ] )->orderBy('sort asc')->all ();

        if($department->businessmenu) {
            $html = '<div class="row" >';

            foreach ($arrayBusinessMenu as $menu) {
				switch (Yii::$app->user->identity->template) {
					case 'default':
						$html .= $this->showMenuPic ( $menu, $farms_id );
						break;
					case 'template2018':
						$html .= $this->showMenuPic2018 ( $menu, $farms_id );
						break;
				}

            }
            $html .= '</div>';
        }
        return $html;
	}
	

	public function getHtml($divInfo,$template='default')
	{
		$html = '';
		switch ($template) {
			case 'default':
				$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;">';
//			$html .= '<a href=' . $divInfo ['url'] . '>';
				$html .= '<div class="info-box" style="background-color:#c9c9c9;">';
				$html .= '<span class="info-box-icon"><i class="' . $divInfo ['icon'] . '"></i></span>';
				$html .= '<div class="info-box-content">';
				$html .= '<span class="info-box-number">' . $divInfo ['title'] . ' </span>';
				$html .= '<span class="info-box-text">' . $divInfo ['info'] . '</span>';
				$html .= '<!-- The progress section is optional -->';
				$html .= '<div class="progress">';
				$html .= '<div class="progress-bar" style="width: 100%"></div>';
				$html .= '</div>';
				$html .= '<span class="progress-description">';
				$html .= '业务已经冻结,了解详情请联系管理员。';
				$html .= '</span>';
				$html .= '</div><!-- /.info-box-content -->';
				$html .= '</div><!-- /.info-box -->';
				$html .= '</div>';
				break;
			case 'tempplate2018':
				$html = '<div class="col-md-1-5" style="text-align:center;" >';
				$html .= '<a href=' . $divInfo ['url'] . '>';
				$html .= '<div class="card card-stats" >';
				$html .= '<div class="card-header" data-background-color="'.$divInfo['color'].'" data-header-animation="true">';
				$html .= '<i class="' . $divInfo ['icon'] . '"></i>';
				$html .= '</div>';
				$html .= '<div class="card-content">';
				$html .= '<p class="category"><strong>' . $divInfo ['description'] . '</strong></p>';
				$html .= '<h3 class="card-title"><strong>' . $divInfo ['title'] . '</strong></h3>';
				$html .= '</div>';
				$html .= '<div class="card-footer text-right">';
				$html .= '<div class="stats">';
//		$html .= '<i class="material-icons text-danger">warning</i>';
				$html .= '<a href="#pablo"><strong>'.$divInfo ['info'].'</strong></a>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '<!-- /.info-box --></a>';
				$html .= '</div>';
				break;

		}

	}

	public function showMenuPic2018($menuUrl, $farms_id) {
		$farm = Farms::findOne($farms_id);
		$str = explode ( '/', $menuUrl ['menuurl'] );
		$divInfo = $this->getMenuInfo ( $str [0], $menuUrl, $farms_id );
		if(Lockstate::isLocked($menuUrl['id']) or $farm->state == 0) {
			$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;" >';
			if($divInfo['title'] == '种植结构' or $divInfo['title'] == '租赁' or $divInfo['title'] == '农业基础数据调查表') {
				$html .= '<a href='.$divInfo['url'].'>';
				$color = $divInfo['color'];
				$idarray = Reviewprocess::getReviewFarms($farms_id);
//				var_dump($idarray);exit;
				if(Plantingstructurecheck::isPlant($idarray)) {
					$html .= '<a href="#">';
					$color = '#c9c9c9';
				}
			} else {
				$html .= '<a href="#">';
				$color = '#c9c9c9';
			}
			$html .= '<div class="card card-stats" >';
			$html .= '<div class="card-header" data-background-color='.$color.' data-header-animation="true">';
			$html .= '<i class="' . $divInfo ['icon'] . '"></i>';
			$html .= '</div>';
			$html .= '<div class="card-content">';
			$html .= '<p class="category"><strong>' . $divInfo ['info'] . '</strong></p>';
			$html .= '<h3 class="card-title"><strong>' . $divInfo ['title'] . '</strong></h3>';
			$html .= '</div>';
			$html .= '<div class="card-footer text-right">';
			$html .= '<div class="stats">';
//		$html .= '<i class="material-icons text-danger">warning</i>';
			$html .= '<a href="#pablo"><strong>'.$divInfo ['description'].'</strong></a>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '<!-- /.info-box --></a>';
			$html .= '</div>';
//			$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;">';
////			$html .= '<a href=' . $divInfo ['url'] . '>';
//			$html .= '<div class="info-box" style="background-color:#c9c9c9;">';
//			$html .= '<span class="info-box-icon"><i class="' . $divInfo ['icon'] . '"></i></span>';
//			$html .= '<div class="info-box-content">';
//			$html .= '<span class="info-box-number">' . $divInfo ['title'] . ' </span>';
//			$html .= '<span class="info-box-text">' . $divInfo ['info'] . '</span>';
//			$html .= '<!-- The progress section is optional -->';
//			$html .= '<div class="progress">';
//			$html .= '<div class="progress-bar" style="width: 100%"></div>';
//			$html .= '</div>';
//			$html .= '<span class="progress-description">';
//			$html .= '业务已经冻结,了解详情请联系管理员。';
//			$html .= '</span>';
//			$html .= '</div><!-- /.info-box-content -->';
//			$html .= '</div><!-- /.info-box -->';
//			$html .= '</div>';
		} else {
			switch ($str[0]) {
				case 'loan':
					$lockinfo = Lockstate::isLoanLocked($farms_id);
//					var_dump($lockinfo);exit;
					if($lockinfo['state'] and !Lockstate::isUnlockloan($farms_id)) {
						$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;" >';
						$html .= '<a href=#>';
						$html .= '<div class="card card-stats" >';
						$html .= '<div class="card-header" data-background-color="#c9c9c9" data-header-animation="true">';
						$html .= '<i class="' . $divInfo ['icon'] . '"></i>';
						$html .= '</div>';
						$html .= '<div class="card-content">';
						$html .= '<p class="category"><strong>' . $divInfo ['info'] . '</strong></p>';
						$html .= '<h3 class="card-title"><strong>' . $divInfo ['title'] . '</strong></h3>';
						$html .= '</div>';
						$html .= '<div class="card-footer text-right">';
						$html .= '<div class="stats">';
//		$html .= '<i class="material-icons text-danger">warning</i>';
						$html .= '<a href="#pablo"><strong>'.$divInfo ['description'].'</strong></a>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '<!-- /.info-box --></a>';
						$html .= '</div>';
					} else {
						$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;" >';
						$html .= '<a href=' . $divInfo ['url'] . '>';
						$html .= '<div class="card card-stats" >';
						$html .= '<div class="card-header" data-background-color="'.$divInfo['color'].'" data-header-animation="true">';
						$html .= '<i class="' . $divInfo ['icon'] . '"></i>';
						$html .= '</div>';
						$html .= '<div class="card-content">';
						$html .= '<p class="category"><strong>' . $divInfo ['info'] . '</strong></p>';
						$html .= '<h3 class="card-title"><strong>' . $divInfo ['title'] . '</strong></h3>';
						$html .= '</div>';
						$html .= '<div class="card-footer text-right">';
						$html .= '<div class="stats">';
//		$html .= '<i class="material-icons text-danger">warning</i>';
						$html .= '<a href="#pablo"><strong>'.$divInfo ['description'].'</strong></a>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '<!-- /.info-box --></a>';
						$html .= '</div>';
					}

					break;
				case 'farms':
					$lockinfo = Lockstate::isTransferLocked($farms_id);
//					var_dump($lockinfo);
					if($lockinfo['state']) {
						$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;" >';
						$html .= '<a href=#>';
						$html .= '<div class="card card-stats" >';
						$html .= '<div class="card-header" data-background-color="#c9c9c9" data-header-animation="true">';
						$html .= '<i class="' . $divInfo ['icon'] . '"></i>';
						$html .= '</div>';
						$html .= '<div class="card-content">';
						$html .= '<p class="category"><strong>' . $lockinfo ['msg'] . '</strong></p>';
						$html .= '<h3 class="card-title"><strong>' . $divInfo ['title'] . '</strong></h3>';
						$html .= '</div>';
						$html .= '<div class="card-footer text-right">';
						$html .= '<div class="stats">';
//		$html .= '<i class="material-icons text-danger">warning</i>';
						$html .= '<a href="#pablo"><strong>'.$divInfo ['description'].'</strong></a>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '<!-- /.info-box --></a>';
						$html .= '</div>';
					} else {
						$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;" >';
						$html .= '<a href=' . $divInfo ['url'] . '>';
						$html .= '<div class="card card-stats" >';
						$html .= '<div class="card-header" data-background-color="'.$divInfo['color'].'" data-header-animation="true">';
						$html .= '<i class="' . $divInfo ['icon'] . '"></i>';
						$html .= '</div>';
						$html .= '<div class="card-content">';
						$html .= '<p class="category"><strong>' . $divInfo ['info'] . '</strong></p>';
						$html .= '<h3 class="card-title"><strong>' . $divInfo ['title'] . '</strong></h3>';
						$html .= '</div>';
						$html .= '<div class="card-footer text-right">';
						$html .= '<div class="stats">';
//		$html .= '<i class="material-icons text-danger">warning</i>';
						$html .= '<a href="#pablo"><strong>'.$divInfo ['description'].'</strong></a>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '<!-- /.info-box --></a>';
						$html .= '</div>';
					}
					break;
				case 'collection':
					$farm = Farms::findOne($farms_id);
//					var_dump($farm);exit;
					if($farm['state'] == 4) {
						$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;" >';
						$html .= '<a href=#>';
						$html .= '<div class="card card-stats" >';
						$html .= '<div class="card-header" data-background-color="#c9c9c9" data-header-animation="true">';
						$html .= '<i class="' . $divInfo ['icon'] . '"></i>';
						$html .= '</div>';
						$html .= '<div class="card-content">';
						$html .= '<p class="category"><strong>' . $divInfo ['info'] . '</strong></p>';
						$html .= '<h3 class="card-title"><strong>' . $divInfo ['title'] . '</strong></h3>';
						$html .= '</div>';
						$html .= '<div class="card-footer text-right">';
						$html .= '<div class="stats">';
//		$html .= '<i class="material-icons text-danger">warning</i>';
						$html .= '<a href="#pablo"><strong>'.$divInfo ['description'].'</strong></a>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '<!-- /.info-box --></a>';
						$html .= '</div>';
					} else {
						$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;" >';
						$html .= '<a href=' . $divInfo ['url'] . '>';
						$html .= '<div class="card card-stats" >';
						$html .= '<div class="card-header" data-background-color="'.$divInfo['color'].'" data-header-animation="true">';
						$html .= '<i class="' . $divInfo ['icon'] . '"></i>';
						$html .= '</div>';
						$html .= '<div class="card-content">';
						$html .= '<p class="category"><strong>' . $divInfo ['info'] . '</strong></p>';
						$html .= '<h3 class="card-title"><strong>' . $divInfo ['title'] . '</strong></h3>';
						$html .= '</div>';
						$html .= '<div class="card-footer text-right">';
						$html .= '<div class="stats">';
//		$html .= '<i class="material-icons text-danger">warning</i>';
						$html .= '<a href="#pablo"><strong>'.$divInfo ['description'].'</strong></a>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '<!-- /.info-box --></a>';
						$html .= '</div>';
					}
					break;
				case 'plantingstructure':
					$farm = Farms::findOne($farms_id);
//					var_dump($farm);exit;
					$idarray = Reviewprocess::getReviewFarms($farms_id);
//				var_dump($idarray);exit;
					if(Plantingstructurecheck::isPlant($idarray)) {

						$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;" >';
						$html .= '<a href=#>';
						$html .= '<div class="card card-stats" >';
						$html .= '<div class="card-header" data-background-color="#c9c9c9" data-header-animation="true">';
						$html .= '<i class="' . $divInfo ['icon'] . '"></i>';
						$html .= '</div>';
						$html .= '<div class="card-content">';
						$html .= '<p class="category"><strong>' . $divInfo ['info'] . '</strong></p>';
						$html .= '<h3 class="card-title"><strong>' . $divInfo ['title'] . '</strong></h3>';
						$html .= '</div>';
						$html .= '<div class="card-footer text-right">';
						$html .= '<div class="stats">';
//		$html .= '<i class="material-icons text-danger">warning</i>';
						$html .= '<a href="#pablo"><strong>'.$divInfo ['description'].'</strong></a>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '<!-- /.info-box --></a>';
						$html .= '</div>';
					} else {
						$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;" >';
						$html .= '<a href=' . $divInfo ['url'] . '>';
						$html .= '<div class="card card-stats" >';
						$html .= '<div class="card-header" data-background-color="'.$divInfo['color'].'" data-header-animation="true">';
						$html .= '<i class="' . $divInfo ['icon'] . '"></i>';
						$html .= '</div>';
						$html .= '<div class="card-content">';
						$html .= '<p class="category"><strong>' . $divInfo ['info'] . '</strong></p>';
						$html .= '<h3 class="card-title"><strong>' . $divInfo ['title'] . '</strong></h3>';
						$html .= '</div>';
						$html .= '<div class="card-footer text-right">';
						$html .= '<div class="stats">';
//		$html .= '<i class="material-icons text-danger">warning</i>';
						$html .= '<a href="#pablo"><strong>'.$divInfo ['description'].'</strong></a>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '<!-- /.info-box --></a>';
						$html .= '</div>';
					}
					break;
				case 'sixcheck':
					$farm = Farms::findOne($farms_id);
//					var_dump($farm);exit;
					$idarray = Reviewprocess::getReviewFarms($farms_id);
//				var_dump($idarray);exit;
					if(Plantingstructurecheck::isPlant($idarray)) {

						$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;" >';
						$html .= '<a href=#>';
						$html .= '<div class="card card-stats" >';
						$html .= '<div class="card-header" data-background-color="#c9c9c9" data-header-animation="true">';
						$html .= '<i class="' . $divInfo ['icon'] . '"></i>';
						$html .= '</div>';
						$html .= '<div class="card-content">';
						$html .= '<p class="category"><strong>' . $divInfo ['info'] . '</strong></p>';
						$html .= '<h3 class="card-title"><strong>' . $divInfo ['title'] . '</strong></h3>';
						$html .= '</div>';
						$html .= '<div class="card-footer text-right">';
						$html .= '<div class="stats">';
//		$html .= '<i class="material-icons text-danger">warning</i>';
						$html .= '<a href="#pablo"><strong>'.$divInfo ['description'].'</strong></a>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '<!-- /.info-box --></a>';
						$html .= '</div>';
					} else {
						$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;" >';
						$html .= '<a href=' . $divInfo ['url'] . '>';
						$html .= '<div class="card card-stats" >';
						$html .= '<div class="card-header" data-background-color="'.$divInfo['color'].'" data-header-animation="true">';
						$html .= '<i class="' . $divInfo ['icon'] . '"></i>';
						$html .= '</div>';
						$html .= '<div class="card-content">';
						$html .= '<p class="category"><strong>' . $divInfo ['info'] . '</strong></p>';
						$html .= '<h3 class="card-title"><strong>' . $divInfo ['title'] . '</strong></h3>';
						$html .= '</div>';
						$html .= '<div class="card-footer text-right">';
						$html .= '<div class="stats">';
//		$html .= '<i class="material-icons text-danger">warning</i>';
						$html .= '<a href="#pablo"><strong>'.$divInfo ['description'].'</strong></a>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '<!-- /.info-box --></a>';
						$html .= '</div>';
					}
					break;
				case 'lease':
					$farm = Farms::findOne($farms_id);
//					var_dump($farm);exit;
					$idarray = Reviewprocess::getReviewFarms($farms_id);
//				var_dump($idarray);exit;
					if(Plantingstructurecheck::isPlant($idarray)) {

						$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;" >';
						$html .= '<a href=#>';
						$html .= '<div class="card card-stats" >';
						$html .= '<div class="card-header" data-background-color="#c9c9c9" data-header-animation="true">';
						$html .= '<i class="' . $divInfo ['icon'] . '"></i>';
						$html .= '</div>';
						$html .= '<div class="card-content">';
						$html .= '<p class="category"><strong>' . $divInfo ['info'] . '</strong></p>';
						$html .= '<h3 class="card-title"><strong>' . $divInfo ['title'] . '</strong></h3>';
						$html .= '</div>';
						$html .= '<div class="card-footer text-right">';
						$html .= '<div class="stats">';
//		$html .= '<i class="material-icons text-danger">warning</i>';
						$html .= '<a href="#pablo"><strong>'.$divInfo ['description'].'</strong></a>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '<!-- /.info-box --></a>';
						$html .= '</div>';
					} else {
						$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;" >';
						$html .= '<a href=' . $divInfo ['url'] . '>';
						$html .= '<div class="card card-stats" >';
						$html .= '<div class="card-header" data-background-color="'.$divInfo['color'].'" data-header-animation="true">';
						$html .= '<i class="' . $divInfo ['icon'] . '"></i>';
						$html .= '</div>';
						$html .= '<div class="card-content">';
						$html .= '<p class="category"><strong>' . $divInfo ['info'] . '</strong></p>';
						$html .= '<h3 class="card-title"><strong>' . $divInfo ['title'] . '</strong></h3>';
						$html .= '</div>';
						$html .= '<div class="card-footer text-right">';
						$html .= '<div class="stats">';
//		$html .= '<i class="material-icons text-danger">warning</i>';
						$html .= '<a href="#pablo"><strong>'.$divInfo ['description'].'</strong></a>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '<!-- /.info-box --></a>';
						$html .= '</div>';
					}
					break;
				default:
					$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;" >';
					$html .= '<a href=' . $divInfo ['url'] . '>';
					$html .= '<div class="card card-stats" >';
					$html .= '<div class="card-header" data-background-color="'.$divInfo['color'].'" data-header-animation="true">';
					$html .= '<i class="' . $divInfo ['icon'] . '"></i>';
					$html .= '</div>';
					$html .= '<div class="card-content">';
					$html .= '<p class="category"><strong>' . $divInfo ['info'] . '</strong></p>';
					$html .= '<h3 class="card-title"><strong>' . $divInfo ['title'] . '</strong></h3>';
					$html .= '</div>';
					$html .= '<div class="card-footer text-right">';
					$html .= '<div class="stats">';
//		$html .= '<i class="material-icons text-danger">warning</i>';
					$html .= '<a href="#pablo"><strong>'.$divInfo ['description'].'</strong></a>';
					$html .= '</div>';
					$html .= '</div>';
					$html .= '</div>';
					$html .= '<!-- /.info-box --></a>';
					$html .= '</div>';
			}
		}

		return $html;
	}

	public function showMenuPic($menuUrl, $farms_id) {
		$str = explode ( '/', $menuUrl ['menuurl'] );
		$divInfo = $this->getMenuInfo ( $str [0], $menuUrl, $farms_id );
		if(Lockstate::isLocked($menuUrl['id'])) {
			$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;">';
//			$html .= '<a href=' . $divInfo ['url'] . '>';
			$html .= '<div class="info-box" style="background-color:#c9c9c9;">';
			$html .= '<span class="info-box-icon"><i class="' . $divInfo ['icon'] . '"></i></span>';
			$html .= '<div class="info-box-content">';
			$html .= '<span class="info-box-number">' . $divInfo ['title'] . ' </span>';
			$html .= '<span class="info-box-text">' . $divInfo ['info'] . '</span>';
			$html .= '<!-- The progress section is optional -->';
			$html .= '<div class="progress">';
			$html .= '<div class="progress-bar" style="width: 100%"></div>';
			$html .= '</div>';
			$html .= '<span class="progress-description">';
			$html .= '业务已经冻结,了解详情请联系管理员。';
			$html .= '</span>';
			$html .= '</div><!-- /.info-box-content -->';
			$html .= '</div><!-- /.info-box -->';
			$html .= '</div>';
		} else {
			switch ($str[0]) {
				case 'loan':
					$lockinfo = Lockstate::isLoanLocked($farms_id);
//					var_dump($lockinfo);exit;
					if($lockinfo['state'] and !Lockstate::isUnlockloan($farms_id)) {
						$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;">';
						$html .= '<div class="info-box" style="background-color:#c9c9c9;">';
						$html .= '<span class="info-box-icon"><i class="' . $divInfo ['icon'] . '"></i></span>';
						$html .= '<div class="info-box-content">';
						$html .= '<span class="info-box-number">' . $divInfo ['title'] . ' </span>';
						$html .= '<span class="info-box-text">' . $divInfo ['info'] . '</span>';
						$html .= '<!-- The progress section is optional -->';
						$html .= '<div class="progress">';
						$html .= '<div class="progress-bar" style="width: 100%"></div>';
						$html .= '</div>';
						$html .= '<span class="progress-description">';
						$html .= $lockinfo['msg'];
						$html .= '</span>';
						$html .= '</div><!-- /.info-box-content -->';
						$html .= '</div><!-- /.info-box -->';
						$html .= '</div>';
					} else {
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
					}

					break;
				case 'farms':
					$lockinfo = Lockstate::isTransferLocked($farms_id);
					if($lockinfo['state']) {
						$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;">';
						$html .= '<div class="info-box" style="background-color:#c9c9c9;">';
						$html .= '<span class="info-box-icon"><i class="' . $divInfo ['icon'] . '"></i></span>';
						$html .= '<div class="info-box-content">';
						$html .= '<span class="info-box-number">' . $divInfo ['title'] . ' </span>';
						$html .= '<span class="info-box-text">' . $divInfo ['info'] . '</span>';
						$html .= '<!-- The progress section is optional -->';
						$html .= '<div class="progress">';
						$html .= '<div class="progress-bar" style="width: 100%"></div>';
						$html .= '</div>';
						$html .= '<span class="progress-description">';
						$html .= $lockinfo['msg'];
						$html .= '</span>';
						$html .= '</div><!-- /.info-box-content -->';
						$html .= '</div><!-- /.info-box -->';
						$html .= '</div>';
					} else {
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
					}
					break;
				case 'collection':
					$farm = Farms::findOne($farms_id);
//					var_dump($farm);exit;
					if($farm['state'] == 4) {
						$html = '<div class="col-md-3 col-sm-6 col-xs-12" style="text-align:center;">';
						$html .= '<div class="info-box" style="background-color:#c9c9c9;">';
						$html .= '<span class="info-box-icon"><i class="' . $divInfo ['icon'] . '"></i></span>';
						$html .= '<div class="info-box-content">';
						$html .= '<span class="info-box-number">' . $divInfo ['title'] . ' </span>';
						$html .= '<span class="info-box-text">' . $divInfo ['info'] . '</span>';
						$html .= '<!-- The progress section is optional -->';
						$html .= '<div class="progress">';
						$html .= '<div class="progress-bar" style="width: 100%"></div>';
						$html .= '</div>';
						$html .= '<span class="progress-description">';
						$html .= '买断合同不需缴费';
						$html .= '</span>';
						$html .= '</div><!-- /.info-box-content -->';
						$html .= '</div><!-- /.info-box -->';
						$html .= '</div>';
					} else {
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
					}
					break;
				default:
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
			}
		}

		return $html;
	}
	public function getMenuInfo($controller, $menuUrl, $farms_id)
	{
		switch ($controller) {
			case 'farmer' :
				$value['color'] = 'blue';
				$value ['icon'] = 'fa fa-user';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = Farms::find ()->where ( [
						'id' => $farms_id
				] )->one ()['farmername'];
				$value ['description'] = '法人详细信息';
				break;
			case 'farms' :
				$value['color'] = 'green';
				$value ['icon'] = 'fa fa-users';
				$value ['title'] = $menuUrl ['menuname'];
//				if(Farms::isFarmsInfo($farms_id)) {
//					$value ['url'] = Url::to('index.php?r=error/nofarmsinfo' . '&farms_id=' . $farms_id);
//				} else {
					$value ['url'] = Url::to('index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id);
//				}
				$farmState = Farms::getContractstate($_GET['farms_id']);
				$ttopzongdi = Ttpozongdi::find ()->where ( [
						'newnewfarms_id' => $farms_id
				] )->count ();
				$value ['info'] = '无过户转让信息';

				if ($ttopzongdi)
					$value ['info'] = ' 转让' . $ttopzongdi . '次';
				if (Farms::getLocked ( $farms_id ))
					$value ['info'] = '已冻结';
				$value ['description'] = '过户、转让办理与历史记录';
				if($farmState == 'L') {
					$value ['url'] = '#';
					$value ['info'] = '此合同不能进行流转';
				}
				break;
			case 'lease' :
				$value['color'] = 'orange';
				$value ['icon'] = 'fa fa-street-view';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '现共' . Lease::find ()->where ( [
						'farms_id' => $_GET ['farms_id'],
						'year' => User::getYear()
				] )->count () . '人租赁';
				$value ['description'] = '承租人信息及年限';
				break;
			case 'loan' :
				$value['color'] = 'purple';
				$value ['icon'] = 'fa fa-university';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '现有' . Loan::find ()->where ( [
						'farms_id' => $_GET ['farms_id'],
						'year' => User::getYear()
				] )->count () . '条贷款信息';
				$value ['description'] = '贷款信息';
				break;
			case 'dispute' :
				$value['color'] = 'rose';
				$value ['icon'] = 'fa fa-commenting';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '现有' . Dispute::find ()->where ( [
						'farms_id' => $_GET ['farms_id']
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '个纠纷';
				$value ['description'] = '纠纷具体事项';
				break;
			case 'cooperativeoffarm' :
				$value['color'] = 'red';
				$value ['icon'] = 'fa fa-briefcase';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '参加了' . Cooperativeoffarm::find ()->where ( [
						'farms_id' => $_GET ['farms_id']
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '个合作社';
				$value ['description'] = '注册资金等信息';
				break;
			case 'employee' :
				$value['color'] = 'rs';
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
				$value['color'] = 'xm';
				$value ['icon'] = 'fa fa-sun-o';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
// 				var_dump(Theyear::getYeartime()[1]);exit;
				$value ['info'] = '种植了' . Plantingstructure::find ()->where ( [
						'farms_id' => $_GET ['farms_id'],'year'=>User::getYear()
				] )->count () . '种作物';
				$value ['description'] = '种植作物信息';
				break;
			case 'fireprevention' :
				$value['color'] = 'bx';
				$value ['icon'] = 'fa fa-fire-extinguisher';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '未完成防火工作';
				if (Fireprevention::find ()->where ( [
						'farms_id' => $_GET ['farms_id'],
						'year'=>User::getYear(),
						'finished' => 1,
				] )->count ())
					$value ['info'] = '完成防火工作';

				$value ['description'] = '防火宣传、合同签订信息';
				break;
			case 'yields' :
				$value['color'] = 'dk';
				$value ['icon'] = 'fa fa-balance-scale';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '农作物产量';
				$value ['description'] = '农产品产量信息';
				break;
			case 'sales' :
				$value['color'] = 'blue';
				$value ['icon'] = 'fa fa-cart-arrow-down';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '有' . Sales::find ()->where ( [
						'farms_id' => $_GET ['farms_id']
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '条销量信息';
				$value ['description'] = '农产品销售情况';
				break;
			case 'breed' :
				$value['color'] = 'orange';
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
				$value['color'] = 'green';
				$value ['icon'] = 'fa fa-plus';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '有' . Prevention::find ()->where ( [
						'farms_id' => $_GET ['farms_id']
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '条防疫信息';
				$value ['description'] = ' 防疫率,疫苗接种等信息';
				break;
			case 'projectapplication' :
				$value['color'] = 'rose';
				$value ['icon'] = 'fa fa-sticky-note-o';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '申报了' . Projectapplication::find ()->where ( [
						'farms_id' => $_GET ['farms_id']
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '个项目';
				$value ['description'] = '项目申报、完成情况';
				break;
			case 'disaster' :
				$value['color'] = 'green';
				$value ['icon'] = 'fa fa-soundcloud';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '有' . Disaster::find ()->where ( [
						'farms_id' => $_GET ['farms_id']
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '个灾害';
				$value ['description'] = '受灾、保险情况';
				break;
			case 'collection' :
				$value['color'] = 'red';
				$value ['icon'] = 'fa fa-cny';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$isCollection = Collection::find ()->where ( [
						'farms_id' => $_GET ['farms_id'],
						'ypayyear' => User::getYear()
				] )->count ();
				if ($isCollection)
					$value ['info'] = '已收缴本年度承包费';
				else
					$value ['info'] = '本年度承包费未收缴或有欠费';
				$value ['description'] = '地产科确认承包费并发送至财务科';
				break;
			case 'machineoffarm' :
				$value['color'] = 'xm';
				$cardid = Farms::find()->where(['id'=>$farms_id])->one()['cardid'];
				$value ['icon'] = 'fa fa-truck';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$machine = Machineoffarm::find ()->where ( [
						'cardid' => $cardid
				] )->count ();
				$value ['info'] = '有'.$machine.'台农机器具';
				$value ['description'] = '农机器具';
				break;
			case 'insurance' :
				$value['color'] = 'bx';
				$value ['icon'] = 'fa fa-file-text-o';
				$value ['title'] = $menuUrl ['menuname'];
				$farmState = Farms::getContractstate($_GET['farms_id']);
				if($farmState == 'L') {
					$value ['url'] = '#';
				} else {
					$value ['url'] = Url::to('index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id);
				}
				if($farms_id == 2070) {
					$value ['url'] = Url::to('index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id);
				}
				$insurance = Insurance::find ()->where ( [
						'farms_id' => $_GET ['farms_id'],
						'state' => 1,
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count ();
				if($insurance)
					$value ['info'] = '已参加保险';
				else
					$value ['info'] = '未参加保险';
				if($farmState == 'L') {
					$value['info'] = '<span class="text-danger">不能参加保险</span>';
				}
				if($farms_id == 2070) {
					$value ['info'] = '未参加保险';
				}
				$value ['description'] = '种植业保险';
				break;
            case 'sixcheck' :
				$value['color'] = 'dk';
                $value ['icon'] = 'fa fa-calendar-check-o';
                $value ['title'] = $menuUrl ['menuname'];
                $value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
                $plantingstructure = Plantingstructure::find ()->where ( [
                    'farms_id' => $_GET ['farms_id'],
                ] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count ();
                if($plantingstructure) {
                    $value ['info'] = '已经填报';
                    $value['url'] = Url::to ( 'index.php?r=sixcheck/sixcheckindex&farms_id=' . $farms_id );
                }
                else
                    $value ['info'] = '未填报';
                $value ['description'] = '农业基础数据';
                break;
			case 'fixed':
				$value['color'] = 'blue';
				$value ['icon'] = 'fa fa-home';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$fixed = Fixed::find ()->where ( [
					'farms_id' => $_GET ['farms_id'],
				] )->count ();
				if($fixed) {
					$value ['info'] = '有'.$fixed.'固定资产';
					$value['url'] = Url::to ( 'index.php?r=fixed/fixedindex&farms_id=' . $farms_id );
				}
				else
					$value ['info'] = '没有固定资产';
				$value ['description'] = '农场固定资产情况';
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
		Logs::writeLogs ( '删除农场信息', $model);
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
		Logs::writeLogs('综合查询-农场信息');
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
