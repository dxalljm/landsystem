<?php

namespace frontend\controllers;

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
	
	public function actionFarmscontractprint($farms_id)
	{
		$model = $this->findModel($farms_id);
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
				
				for($i = 1; $i <= $rows; $i ++) {
					$contract = $loadxls->getActiveSheet()->getCell('A'.$i)->getValue();
// 					var_dump($contract);
// 					$array = explode('-', $contract);
// 					foreach($farms as $value) {
// 						if($contract !== $value['contractnumber']) {
// 							$data[] = $value;
// 						}
							
// 					}
					$farm = Farms::find()->where(['management_area'=>3,'state'=>1,'contractnumber'=>$contract])->one();
					if(empty($farm))
						$data[] = $contract;
					
					// 导入农场基础信息
					// var_dump($loadxls->getActiveSheet()->getCell('H'.$i)->getValue())."<br>";exit;
					// echo ManagementArea::find()->where(['areaname'=>$loadxls->getActiveSheet()->getCell('B'.$i)->getValue()])->one()['id'];"<br>";
					// $farmsmodel = new Farms ();
					// // $farmsmodel = $loadxls->getActiveSheet()->getCell('A'.$i)->getValue();
					// // $farmsmodel->id = ( int ) $loadxls->getActiveSheet ()->getCell ( 'A' . $i )->getValue ();
					// $farmsmodel->management_area = ( int ) $loadxls->getActiveSheet ()->getCell ( 'B' . $i )->getValue ();
					// $farmsmodel->contractnumber = $loadxls->getActiveSheet ()->getCell ( 'C' . $i )->getValue ();
					// $farmsmodel->farmname = $loadxls->getActiveSheet ()->getCell ( 'D' . $i )->getValue ();
					// $farmsmodel->farmername = $loadxls->getActiveSheet ()->getCell ( 'E' . $i )->getValue ();
					// $farmsmodel->measure = $loadxls->getActiveSheet ()->getCell ( 'F' . $i )->getValue ();
					// $farmsmodel->address = $loadxls->getActiveSheet ()->getCell ( 'G' . $i )->getValue ();
					// $farmsmodel->longitude = $this->formatLongLat ( $loadxls->getActiveSheet ()->getCell ( 'H' . $i )->getValue (), 'E' );
					// $farmsmodel->latitude = $this->formatLongLat ( $loadxls->getActiveSheet ()->getCell ( 'I' . $i )->getValue (), 'N' );
					// $farmsmodel->cardid = $loadxls->getActiveSheet ()->getCell ( 'J' . $i )->getValue ();
					// $farmsmodel->telephone = ( string ) $loadxls->getActiveSheet ()->getCell ( 'K' . $i )->getValue ();
					// // $farmsmodel->spyear = '';
					// $farmsmodel->begindate = '2010-09-13';
					// $farmsmodel->enddate = '2025-09-13';
					// // $farmsmodel->surveydate = date('Y-m-d',$time);
					// $farmsmodel->state = 1;
					// $farmsmodel->notclear = $loadxls->getActiveSheet ()->getCell ( 'F' . $i )->getValue ();
					// // echo $farmsmodel->surveydate;
					// // $farmsmodel->groundsign =
					// // $farmsmodel->investigator =
					// // $farmsmodel->farmersign = $loadxls->getActiveSheet()->getCell('L'.$i)->getValue();
					// $farmsmodel->create_at = time ();
					// $farmsmodel->update_at = time ();
					// // var_dump(Pinyin::encode($loadxls->getActiveSheet()->getCell('D'.$i)->getValue()));
					// $farmsmodel->pinyin = Pinyin::encode ( $loadxls->getActiveSheet ()->getCell ( 'D' . $i )->getValue () );
					// $farmsmodel->farmerpinyin = Pinyin::encode ( $loadxls->getActiveSheet ()->getCell ( 'E' . $i )->getValue () );
					// $farmsmodel->save ();
					// var_dump($farmsmodel);
					// exit;
					
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
// 					}
				}
			}
		}
		if($data)
			return $this->render ( 'farmslist', [
					'data' => $data,
			] );
// 		exit;
// 		echo 'finished';
		Logs::writeLog ( '农场XLS批量导入' );
		return $this->render ( 'farmsxls', [ 
				'model' => $model,
				'rows' => $rows, 
// 				'area' => $area
		] );
	}
	
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
// 		set_time_limit ( 0 );
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
		$farms = Farms::find ()->all ();
		foreach ( $farms as $farm ) {
			$model = $this->findModel($farm['id']);
			if($model->notstate === NULL) {
				$model->notstate = 0;
				$model->save();
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
				'cooperativeoffarm' => $cooperativeoffarm 
		] );
	}
	// 转让主页面
	public function actionFarmsttpomenu($farms_id) {
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
		return $this->render ( 'farmsttpomenu', [ 
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
		$oldFarm = Farms::find ()->where ( [ 
				'id' => $ttpoModel->oldfarms_id 
		] )->one ();
		$newFarm = Farms::find ()->where ( [ 
				'id' => $ttpoModel->newfarms_id 
		] )->one ();
		return $this->render ( 'farmsttpozongdiview', [ 
				'ttpoModel' => $ttpoModel,
				'oldFarm' => $oldFarm,
				'newFarm' => $newFarm 
		] );
	}
	// 农场过户，state状态：0为已经失效，1为当前正用
	public function actionFarmstransfer($farms_id) {
		$model = $this->findModel ( $farms_id );
		$oldAttr = $model->attributes;
		// $model->state = 0;
		
		$reviewprocess = new Reviewprocess ();
		
		$nowModel = new Farms ();
		
		if ($nowModel->load ( Yii::$app->request->post () )) {
			// 保存审核信息，成功返回reviewprocessID，失败返回flase
			
			// var_dump($reviewprocessModel->getErrors());exit;
			$lockedinfoModel = new Lockedinfo ();
			$lockedinfoModel->farms_id = $farms_id;
			$lockedinfoModel->lockedcontent = '整体过户审核中，已被冻结。';
			$lockedinfoModel->save ();
			$nowModel->address = $model->address;
			$nowModel->management_area = $model->management_area;
			$nowModel->spyear = $model->spyear;
			$nowModel->measure = $model->measure;
			$nowModel->zongdi = $model->zongdi;
			$nowModel->cooperative_id = $model->cooperative_id;
			$nowModel->surveydate = $model->surveydate;
			$nowModel->groundsign = $model->groundsign;
			$nowModel->investigator = $model->investigator;
			$nowModel->notclear = $model->notclear;
			$nowModel->create_at = time ();
			$nowModel->update_at = time ();
			$nowModel->pinyin = Pinyin::encode ( $nowModel->farmname );
			$nowModel->farmerpinyin = Pinyin::encode ( $nowModel->farmername );
			$nowModel->state = 0;
			$nowModel->locked = 1;
			$nowModel->save ();
			$oldModel = $this->findModel ( $farms_id );
			$oldModel->locked = 1;
			$oldModel->save ();
// 			var_dump($oldModel->getErrors());exit;
			$reviewprocessID = Reviewprocess::processRun ( $model->id, $nowModel->id );
			$ttpoModel = new Ttpo ();
			$ttpoModel->oldfarms_id = $model->id;
			$ttpoModel->newfarms_id = $nowModel->id;
			$ttpoModel->create_at = time ();
			$ttpoModel->reviewprocess_id = $reviewprocessID;
			$ttpoModel->save ();
			$newAttr = $nowModel->attributes;
			Logs::writeLog ( '农场转让信息', $nowModel->id, $oldAttr, $newAttr );
			
			return $this->redirect ( [ 
					Reviewprocess::getReturnAction (),
					'newfarmsid' => $nowModel->id,
					'oldfarmsid' => $model->id,
					'reviewprocessid' => $reviewprocessID 
			] );
		} else {
			return $this->render ( 'farmstransfer', [ 
					'model' => $model,
					'nowModel' => $nowModel,
					'farms_id' => $farms_id 
			] );
		}
	}
	
	// 农场转让——新增
	public function actionFarmssplit($farms_id) {
		$oldmodel = $this->findModel ( $farms_id );
		
		$newmodel = new Farms ();
		// $ttpoModel = Ttpo::find()->orWhere(['oldfarms_id'=>$farms_id])->orWhere(['newfarms_id'=>$farms_id])->all();
		// $ttpozongdiModel = Ttpozongdi::find()->orWhere(['oldfarms_id'=>$farms_id])->orWhere(['newfarms_id'=>$farms_id])->all();
		// 原农场转让宗地后，重新签订合同后，生成新的农场信息
		if ($oldmodel->load ( Yii::$app->request->post () )) {
			$lockedinfoModel = new Lockedinfo ();
			$lockedinfoModel->farms_id = $farms_id;
			$lockedinfoModel->lockedcontent = '部分过户审核中，已被冻结。';
			$lockedinfoModel->save();
			// $oldmodel->state = 1;
			$oldmodel = $this->findModel ( $farms_id );
			$oldmodel->update_at = time ();
			// var_dump( Yii::$app->request->post ( 'oldzongdi' ) );exit;
			$oldmodel->farmname = $oldmodel->farmname;
			$oldmodel->measure = Yii::$app->request->post ( 'oldmeasure' );
			$oldmodel->zongdi = $this->deleteZongdiDH ( Yii::$app->request->post ( 'oldzongdi' ) );
			$oldmodel->notclear = Yii::$app->request->post ( 'oldnotclear' );
			$oldmodel->contractnumber = Yii::$app->request->post ( 'oldcontractnumber' );
			$oldmodel->locked = 1;
			$oldmodel->save ();
			// var_dump($oldmodel);exit;
			// $newfarm->save();
			
			if ($newmodel->load ( Yii::$app->request->post () )) {
				// var_dump($newmodel->zongdi);exit;
				$newmodel->farmname = $newmodel->farmname;
				$newmodel->farmername = $newmodel->farmername;
				$newmodel->cardid = $newmodel->cardid;
				$newmodel->telephone = $newmodel->telephone;
				$newmodel->address = $oldmodel->address;
				$newmodel->management_area = $oldmodel->management_area;
				$newmodel->spyear = $oldmodel->spyear;
				$newmodel->measure = $newmodel->measure;
				$newmodel->zongdi = $newmodel->zongdi;
				$newmodel->cooperative_id = $newmodel->cooperative_id;
				$newmodel->surveydate = $oldmodel->surveydate;
				$newmodel->groundsign = $oldmodel->groundsign;
				$newmodel->farmersign = $newmodel->farmersign;
				$newmodel->create_at = time ();
				$newmodel->update_at = $newmodel->create_at;
				$newmodel->pinyin = Pinyin::encode($newmodel->pinyin);
				$newmodel->farmerpinyin = Pinyin::encode($newmodel->farmerpinyin);
				$newmodel->state = 0;
				$newmodel->locked = 0;
				$newmodel->notclear = $newmodel->notclear;
				$newmodel->oldfarms_id = $farms_id;
				
				$newmodel->save ();
			}
			$reviewprocessID = Reviewprocess::processRun ( $oldmodel->id, $newmodel->id );
			$ttpozongdi = new Ttpozongdi ();
			$ttpozongdi->oldfarms_id = $oldmodel->id;
			$ttpozongdi->newfarms_id = $newmodel->id;
			$ttpozongdi->zongdi = $newmodel->zongdi;
			$ttpozongdi->oldzongdi = $oldmodel->zongdi;
			$ttpozongdi->reviewprocess_id = $reviewprocessID;
			$ttpozongdi->create_at = $oldmodel->update_at;
			$ttpozongdi->ttpozongdi = Yii::$app->request->post ( 'ttpozongdi' );
			$ttpozongdi->ttpoarea = Yii::$app->request->post ( 'ttpoarea' );
			
			$ttpozongdi->save ();
			Contractnumber::contractnumberAdd ();
			// var_dump($ttpozongdi->getErrors());exit;
			return $this->redirect ( [ 
					Reviewprocess::getReturnAction (),
					'newfarmsid' => $newmodel->id,
					'oldfarmsid' => $oldmodel->id,
					'reviewprocessid' => $reviewprocessID 
			] );
		} else {
			
			return $this->render ( 'farmssplit', [ 
					'oldFarm' => $oldmodel,
					'newFarm' => $newmodel 
			] );
		}
	}
	// 农场转让
	public function actionFarmsttpozongdi($farms_id) {
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
		return $this->render ( 'farmsttpozongdi', [ 
				'searchModel' => $farmsSearch,
				'dataProvider' => $dataProvider,
				'oldfarms_id' => $farms_id 
		] );
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
	public function actionFarmstozongdi($farms_id, $oldfarms_id) {
		$oldmodel = $this->findModel ( $oldfarms_id );
		// var_dump($oldmodel);
		$newmodel = $this->findModel ( $farms_id );
		// $ttpoModel = Ttpo::find()->orWhere(['oldfarms_id'=>$farms_id])->orWhere(['newfarms_id'=>$farms_id])->all();
		// $ttpozongdiModel = Ttpozongdi::find()->orWhere(['oldfarms_id'=>$farms_id])->orWhere(['newfarms_id'=>$farms_id])->all();
		// 原农场转让宗地后，重新签订合同后，生成新的农场信息
		if ($oldmodel->load ( Yii::$app->request->post () )) {
			$lockedinfoModel = new Lockedinfo ();
			$lockedinfoModel->farms_id = $farms_id;
			$lockedinfoModel->lockedcontent = '部分过户审核中，已被冻结。';
			$lockedinfoModel->save();
			$lockedinfoModel = new Lockedinfo ();
			$lockedinfoModel->farms_id = $oldfarms_id;
			$lockedinfoModel->lockedcontent = '部分过户审核中，已被冻结。';
			$lockedinfoModel->save();
			// $oldmodel->state = 1;
			$oldmodel = $this->findModel ( $oldfarms_id );
			$oldmodel->update_at = time ();
			// var_dump( Yii::$app->request->post ( 'oldzongdi' ) );exit;
			$oldmodel->farmname = $oldmodel->farmname;
			$oldmodel->measure = Yii::$app->request->post ( 'oldmeasure' );
			$oldmodel->zongdi = $this->deleteZongdiDH ( Yii::$app->request->post ( 'oldzongdi' ) );
			$oldmodel->notclear = Yii::$app->request->post ( 'oldnotclear' );
			$oldmodel->contractnumber = Yii::$app->request->post ( 'oldcontractnumber' );
			$oldmodel->locked = 1;
			$oldmodel->save ();
			// var_dump($oldmodel);exit;
			// $newfarm->save();
			
			if ($newmodel->load ( Yii::$app->request->post () )) {
// 				var_dump($oldmodel);exit;
// 				$newmodel->farmname = $newmodel->farmname;
// 				$newmodel->farmername = $newmodel->farmername;
// 				$newmodel->cardid = $newmodel->cardid;
// 				$newmodel->telephone = $newmodel->telephone;
// 				$newmodel->address = $oldmodel->address;
// 				$newmodel->management_area = $oldmodel->management_area;
// 				$newmodel->spyear = $oldmodel->spyear;
				$newmodel->measure = $newmodel->measure;
				$newmodel->zongdi = $newmodel->zongdi;
// 				$newmodel->cooperative_id = $newmodel->cooperative_id;
// 				$newmodel->surveydate = $oldmodel->surveydate;
// 				$newmodel->groundsign = $oldmodel->groundsign;
// 				$newmodel->farmersign = $newmodel->farmersign;
// 				$newmodel->longitude = $oldmodel->longitude;
// 				$newmodel->latitude = $oldmodel->latitude;
				$newmodel->locked = 1;
// 				$newmodel->create_at = time ();
				$newmodel->update_at = time();
// 				$newmodel->pinyin = Pinyin::encode($newmodel->farmname);
// 				$newmodel->farmerpinyin = Pinyin::encode($newmodel->farmername);
				$newmodel->state = 1;
				$newmodel->notclear = $newmodel->notclear;
				$newmodel->oldfarms_id = $oldfarms_id;
				
				$newmodel->save ();
			}
			$reviewprocessID = Reviewprocess::processRun ( $oldmodel->id, $newmodel->id );
			$ttpozongdi = new Ttpozongdi ();
			$ttpozongdi->oldfarms_id = $oldmodel->id;
			$ttpozongdi->newfarms_id = $newmodel->id;
			$ttpozongdi->zongdi = $newmodel->zongdi;
			$ttpozongdi->oldzongdi = $oldmodel->zongdi;
			// var_dump($newmodel->zongdi);exit;
			$ttpozongdi->create_at = $oldmodel->update_at;
			$ttpozongdi->ttpozongdi = Yii::$app->request->post ( 'ttpozongdi' );
			$ttpozongdi->ttpoarea = Yii::$app->request->post ( 'ttpoarea' );
			
			$ttpozongdi->save ();
			Contractnumber::contractnumberAdd ();
			// var_dump($ttpozongdi->getErrors());exit;
			return $this->redirect ( [ 
					Reviewprocess::getReturnAction (),
					'newfarmsid' => $farms_id,
					'oldfarmsid' => $oldfarms_id,
					'reviewprocessid' => $reviewprocessID 
			] );
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
			$model->save ();
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
					$$value ['info'] = '完成防火工作';
				
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
