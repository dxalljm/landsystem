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
	
	public function beforeAction($action) {
		$action = Yii::$app->controller->action->id;
		if (\Yii::$app->user->can ( $action )) {
			return true;
		} else {
			throw new \yii\web\UnauthorizedHttpException ( '对不起，您现在还没获此操作的权限' );
		}
	}
	/**
	 * Lists all farms models.
	 *
	 * @return mixed
	 */
	public function actionFarmsindex() {
		$departmentid = User::find ()->where ( [ 
				'id' => \Yii::$app->getUser ()->id 
		] )->one ()['department_id'];
		$departmentData = Department::find ()->where ( [ 
				'id' => $departmentid 
		] )->one ();
		$whereArray = explode ( ',', $departmentData ['membership'] );
		
		$searchModel = new farmsSearch ();
		
		$params = Yii::$app->request->queryParams;
		$params ['farmsSearch'] ['state'] = 1;
		// 管理区域是否是数组
		if (! empty ( $whereArray ) && count ( $whereArray ) > 0) {
			$params ['farmsSearch'] ['management_area'] = $whereArray;
		}
		// var_dump($params);exit;
		$dataProvider = $searchModel->search ( $params );
		Logs::writeLog ( '农场管理' );
		return $this->render ( 'farmsindex', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	public function getFarmsid()
	{
		$departmentid = User::find ()->where ( [
				'id' => \Yii::$app->getUser ()->id
		] )->one ()['department_id'];
		$departmentData = Department::find ()->where ( [
				'id' => $departmentid
		] )->one ();
		$whereArray = explode ( ',', $departmentData ['membership'] );
		$farms = Farms::find()->where(['management_area'=>$whereArray])->all();
		foreach ($farms as $value) {
			$farmsID[] = $value['id'];
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
// 	public function getfarmarea() {
// 		$cacheKey = 'farmsarea-hcharts2';
// 		$result = Yii::$app->cache->get ( $cacheKey );
// 		if (! empty ( $result )) {
// 			return $result;
// 		}
// 		$sum = 0;
// 		$dep_id = User::findByUsername ( yii::$app->user->identity->username )['department_id'];
// 		$departmentData = Department::find ()->where ( [ 
// 				'id' => $dep_id 
// 		] )->one ();
// 		$whereArray = explode ( ',', $departmentData ['membership'] );
// 		$all = Farms::find ()->sum ( 'measure' );
// 		foreach ( $whereArray as $value ) {
// 			$resultName = ManagementArea::find ()->where ( [ 
// 					'id' => $value 
// 			] )->one ()['areaname'];
// 			$resultValue = ( float ) Farms::find ()->where ( [ 
// 					'management_area' => $value 
// 			] )->sum ( 'measure' );
// 			$result [] = [ 
// 					$resultName,
// 					$resultValue 
// 			];
// 			$sum += $resultValue;
// 		}
// 		$allvalue = $all - $sum;
// 		$result [] = [ 
// 				'其他管理区',
// 				( float ) $allvalue 
// 		];
// 		$jsonData = Json::encode ( [ 
// 				'status' => 1,
// 				'result' => $result,
// 				'total' => $all 
// 		] );
// 		Yii::$app->cache->set ( $cacheKey, $jsonData, 1 );
		
// 		return $jsonData;
// 	}
	// xls导入
	public function actionFarmsxls() 
	{
		set_time_limit ( 0 );
		$model = new UploadForm ();
		$rows = 0;
		if (Yii::$app->request->isPost) {
			
			$model->file = UploadedFile::getInstance ( $model, 'file' );
			if ($model->file == null)
				throw new \yii\web\UnauthorizedHttpException ( '对不起，请先选择xls文件' );
			if ($model->file && $model->validate ()) {
				
				$xlsName = time () . rand ( 100, 999 );
				$model->file->name = $xlsName;
				$model->file->saveAs ( 'uploads/' . $model->file->name . '.' . $model->file->extension );
				$path = 'uploads/' . $model->file->name . '.' . $model->file->extension;
				$loadxls = \PHPExcel_IOFactory::load ( $path );
				$rows = $loadxls->getActiveSheet ()->getHighestRow ();
				$area = 0.0;
				for($i = 2; $i <= $rows; $i ++) {
					//导入农场基础信息
					// var_dump($loadxls->getActiveSheet()->getCell('H'.$i)->getValue())."<br>";exit;
					// echo ManagementArea::find()->where(['areaname'=>$loadxls->getActiveSheet()->getCell('B'.$i)->getValue()])->one()['id'];"<br>";
// 					$farmsmodel = new Farms ();
// // 					$farmsmodel = $loadxls->getActiveSheet()->getCell('A'.$i)->getValue();
// // 					$farmsmodel->id = ( int ) $loadxls->getActiveSheet ()->getCell ( 'A' . $i )->getValue ();
// 					$farmsmodel->management_area = ( int ) $loadxls->getActiveSheet ()->getCell ( 'B' . $i )->getValue ();
// 					$farmsmodel->contractnumber = $loadxls->getActiveSheet ()->getCell ( 'C' . $i )->getValue ();
// 					$farmsmodel->farmname = $loadxls->getActiveSheet ()->getCell ( 'D' . $i )->getValue ();
// 					$farmsmodel->farmername = $loadxls->getActiveSheet ()->getCell ( 'E' . $i )->getValue ();
// 					$farmsmodel->measure = $loadxls->getActiveSheet ()->getCell ( 'F' . $i )->getValue ();
// 					$farmsmodel->address = $loadxls->getActiveSheet ()->getCell ( 'G' . $i )->getValue ();
// 					$farmsmodel->longitude = $this->formatLongLat ( $loadxls->getActiveSheet ()->getCell ( 'H' . $i )->getValue (), 'E' );
// 					$farmsmodel->latitude = $this->formatLongLat ( $loadxls->getActiveSheet ()->getCell ( 'I' . $i )->getValue (), 'N' );
// 					$farmsmodel->cardid = $loadxls->getActiveSheet ()->getCell ( 'J' . $i )->getValue ();
// 					$farmsmodel->telephone = ( string ) $loadxls->getActiveSheet ()->getCell ( 'K' . $i )->getValue ();
// 					// $farmsmodel->spyear = '';
// 					$farmsmodel->begindate = '2010-09-13';
// 					$farmsmodel->enddate = '2025-09-13';
// 					// $farmsmodel->surveydate = date('Y-m-d',$time);
// 					$farmsmodel->state = 1;
// 					$farmsmodel->notclear = $loadxls->getActiveSheet ()->getCell ( 'F' . $i )->getValue ();
// 					// echo $farmsmodel->surveydate;
// 					// $farmsmodel->groundsign =
// 					// $farmsmodel->investigator =
// 					// $farmsmodel->farmersign = $loadxls->getActiveSheet()->getCell('L'.$i)->getValue();
// 					$farmsmodel->create_at = time ();
// 					$farmsmodel->update_at = time ();
// 					// var_dump(Pinyin::encode($loadxls->getActiveSheet()->getCell('D'.$i)->getValue()));
// 					$farmsmodel->pinyin = Pinyin::encode ( $loadxls->getActiveSheet ()->getCell ( 'D' . $i )->getValue () );
// 					$farmsmodel->farmerpinyin = Pinyin::encode ( $loadxls->getActiveSheet ()->getCell ( 'E' . $i )->getValue () );
// 					$farmsmodel->save ();
					// var_dump($farmsmodel);
					// exit;
					
					//导入农场宗地信息
					$OldContractNumber = $loadxls->getActiveSheet()->getCell('K'.$i)->getValue();
					$htareaArray = explode('-', $OldContractNumber);
// 					var_dump($loadxls->getActiveSheet()->getCell('B'.$i)->getValue());
// 					var_dump($OldContractNumber);
// 					exit;
					if(is_array($htareaArray))
						$htarea = $htareaArray[2];
					else 
						return $OldContractNumber;
					$j = $i + 1;
					
					if($i<=$rows) {
						$NewContractNumber = $loadxls->getActiveSheet()->getCell('K'.$j)->getValue();
						
						if ($OldContractNumber == $NewContractNumber) {
// 							$htareaArray = explode('-', $OldContractNumber);
// 							$htarea = $htareaArray[2];
// 							
							$zongdi[] = $loadxls->getActiveSheet()->getCell('G'.$i)->getValue();
							$area += Parcel::find()->where(['unifiedserialnumber'=>$loadxls->getActiveSheet()->getCell('G'.$i)->getValue()])->one()['netarea'];
						} else {
// 							
							$zongdi[] = $loadxls->getActiveSheet()->getCell('G'.$i)->getValue();
							$area += Parcel::find()->where(['unifiedserialnumber'=>$loadxls->getActiveSheet()->getCell('G'.$i)->getValue()])->one()['netarea'];
							$farm = Farms::find()->where(['contractnumber'=>$loadxls->getActiveSheet()->getCell('K'.$i)->getValue()])->one();
							if($farm)
								$farmModel = $this->findModel($farm->id);
							else 
								return '无此合同农场'.$OldContractNumber;
							$farmModel->zongdi = implode('、', $zongdi);
							
// 							var_dump($htarea);
// 							var_dump($OldContractNumber);
							if(bccomp((float)$htarea,$area) == 1) {
								$notclear = (float)sprintf("%.2f", $htarea-$area);
// 								$lastarea = $area;
							} else {
								$notclear = 0.0;
								
							}
							$farmModel->measure = $area;
							$farmModel->notclear = $notclear;
							$farmModel->save();
							$area = 0.0;
							$zongdi = [];
// 							if($notclear !== 0.0)
// 								var_dump($farmModel->attributes);
						}
					}
					
				}
			}
		}
// 		exit;
		Logs::writeLog ( '农场XLS批量导入' );
		return $this->render ( 'farmsxls', [ 
				'model' => $model,
				'rows' => $rows 
		] );
	}

	private function formatLongLat($str, $l) {
		$miao = substr ( $str, - 4 );
		$fen = substr ( $str, - 6, 2 );
		$du = '';
		if (strlen ( $str ) == 9)
			$du = substr ( $str, 0, 3 );
		if (strlen ( $str ) == 8)
			$du = substr ( $str, 0, 2 );
		$result = $l . $du . '°' . $fen . "'" . $miao . '"';
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
		$departmentid = User::find ()->where ( [ 
				'id' => \Yii::$app->getUser ()->id 
		] )->one ()['department_id'];
		$departmentData = Department::find ()->where ( [ 
				'id' => $departmentid 
		] )->one ();
		$whereArray = explode ( ',', $departmentData ['membership'] );
		$searchModel = new farmsSearch ();
		$params = Yii::$app->request->queryParams;
		
		// 管理区域是否是数组
		if (! empty ( $whereArray ) && count ( $whereArray ) > 0) {
			$params ['farmsSearch'] ['management_area'] = $whereArray;
		}
		$params ['farmsSearch'] ['state'] = 1;
		$dataProvider = $searchModel->search ( $params );
		Logs::writeLog ( '业务办理' );
		return $this->render ( 'farmsbusiness', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
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
		Farms::unLocked($farms_id);
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
		$nowModel = new Farms ();
		if ($nowModel->load ( Yii::$app->request->post () )) {
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
			$nowModel->state = 1;
			$nowModel->save ();
			$model->state = 0;
			$model->update_at = time ();
			$model->save ();
			$ttpoModel = new Ttpo ();
			$ttpoModel->oldfarms_id = $model->id;
			$ttpoModel->newfarms_id = $nowModel->id;
			$ttpoModel->create_at = time ();
			$ttpoModel->save ();
			$newAttr = $nowModel->attributes;
			Logs::writeLog ( '农场转让信息', $nowModel->id, $oldAttr, $newAttr );
			
			return $this->redirect ( [ 
					'farmsttpomenu',
					'farms_id' => $nowModel->id 
			] );
		} else {
			return $this->render ( 'farmstransfer', [ 
					'model' => $model,
					'nowModel' => $nowModel 
			] );
		}
	}
	
	// 农场转让——新增
	public function actionFarmssplit($farms_id) {
		$oldfarm = $this->findModel ( $farms_id );
		
		$model = new Farms ();
		// $ttpoModel = Ttpo::find()->orWhere(['oldfarms_id'=>$farms_id])->orWhere(['newfarms_id'=>$farms_id])->all();
		// $ttpozongdiModel = Ttpozongdi::find()->orWhere(['oldfarms_id'=>$farms_id])->orWhere(['newfarms_id'=>$farms_id])->all();
		// 原农场转让宗地后，重新签订合同后，生成新的农场信息
		if ($oldfarm->load ( Yii::$app->request->post () )) {
			$oldfarm->state = 0;
			$oldfarm->update_at = time ();
			
			$oldfarm->measure = Yii::$app->request->post ( 'oldmeasure' );
			$oldfarm->zongdi = $this->deleteZongdiDH ( Yii::$app->request->post ( 'oldzongdi' ) );
			$oldfarm->notclear = Yii::$app->request->post ( 'oldnotclear' );
			$oldfarm->save ();
			
			// $newfarm->save();
			
			if ($model->load ( Yii::$app->request->post () )) {
				
				$model->farmname = $model->farmname;
				$model->farmername = $model->farmername;
				$model->cardid = $model->cardid;
				$model->telephone = $model->telephone;
				$model->address = $model->address;
				$model->management_area = $model->management_area;
				$model->spyear = $model->spyear;
				$model->measure = $model->measure;
				$model->zongdi = $model->zongdi;
				$model->cooperative_id = $model->cooperative_id;
				$model->surveydate = $model->surveydate;
				$model->groundsign = $model->groundsign;
				$model->farmersign = $model->farmersign;
				$model->create_at = time ();
				$model->update_at = time ();
				$model->pinyin = $model->pinyin;
				$model->farmerpinyin = $model->farmerpinyin;
				$model->state = 1;
				$model->notclear = $model->notclear;
				$model->oldfarms_id = $farms_id;
				
				$model->save ();
			}
			
			$ttpozongdi = new Ttpozongdi ();
			$ttpozongdi->oldfarms_id = $oldfarm->id;
			$ttpozongdi->newfarms_id = $model->id;
			$ttpozongdi->zongdi = $model->zongdi;
			$ttpozongdi->oldzongdi = $oldfarm->zongdi;
			$ttpozongdi->create_at = $oldfarm->update_at;
			$ttpozongdi->ttpozongdi = Yii::$app->request->post ( 'ttpozongdi' );
			$ttpozongdi->ttpoarea = Yii::$app->request->post ( 'ttpoarea' );
			
			// $ttpozongdi->save();
			
			return $this->redirect ( [ 
					'farmsttpomenu',
					'farms_id' => $newfarm->id 
			] );
		} else {
			
			return $this->render ( 'farmssplit', [ 
					'oldFarm' => $oldfarm,
					'model' => $model 
			] );
		}
	}
	// 农场转让
	public function actionFarmsttpozongdi($farms_id) {
		$search = Yii::$app->request->post ( 'farmSearch' );
		$farmsSearch = null;
		$dataProvider = null;
		if ($search) {
			$farmsSearch = new farmsSearch ();
			$params = Yii::$app->request->queryParams;
			$params ['farmsSearch'] ['farmname'] = $search;
			$params ['farmsSearch'] ['farmername'] = $search;
			$params ['farmsSearch'] ['state'] = 1;
			$dataProvider = $farmsSearch->ttposearch ( $params );
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
		$oldfarm = $this->findModel ( $oldfarms_id );
		$oldfarm->state = 0;
		$oldfarm->update_at = time ();
		$oldfarm->save ();
		$model = $this->findModel ( $farms_id );
		// $ttpoModel = Ttpo::find()->orWhere(['oldfarms_id'=>$farms_id])->orWhere(['newfarms_id'=>$farms_id])->all();
		// $ttpozongdiModel = Ttpozongdi::find()->orWhere(['oldfarms_id'=>$farms_id])->orWhere(['newfarms_id'=>$farms_id])->all();
		// 原农场转让宗地后，重新签订合同后，生成新的农场信息
		if ($oldfarm->load ( Yii::$app->request->post () )) {
			$newfarm = new Farms ();
			$newfarm->farmname = $oldfarm->farmname;
			$newfarm->farmername = $oldfarm->farmername;
			$newfarm->cardid = $oldfarm->cardid;
			$newfarm->telephone = $oldfarm->telephone;
			$newfarm->address = $oldfarm->address;
			$newfarm->management_area = $oldfarm->management_area;
			$newfarm->spyear = $oldfarm->spyear;
			$newfarm->measure = Yii::$app->request->post ( 'oldmeasure' );
			$newfarm->zongdi = $this->deleteZongdiDH ( Yii::$app->request->post ( 'oldzongdi' ) );
			$newfarm->cooperative_id = $oldfarm->cooperative_id;
			$newfarm->surveydate = $oldfarm->surveydate;
			$newfarm->groundsign = $oldfarm->groundsign;
			
			$newfarm->farmersign = $oldfarm->farmersign;
			$newfarm->create_at = time ();
			$newfarm->update_at = time ();
			$newfarm->pinyin = $oldfarm->pinyin;
			$newfarm->farmerpinyin = $oldfarm->farmerpinyin;
			$newfarm->state = 1;
			$newfarm->notclear = Yii::$app->request->post ( 'oldnotclear' );
			$newfarm->oldfarms_id = $oldfarms_id;
			var_dump ( $newfarm );
			exit ();
			// $newfarm->save();
			
			if ($model->load ( Yii::$app->request->post () )) {
				$newfarm = new Farms ();
				$newfarm->farmname = $model->farmname;
				$newfarm->farmername = $model->farmername;
				$newfarm->cardid = $model->cardid;
				$newfarm->telephone = $model->telephone;
				$newfarm->address = $model->address;
				$newfarm->management_area = $model->management_area;
				$newfarm->spyear = $model->spyear;
				$newfarm->measure = $model->measure;
				$newfarm->zongdi = $model->zongdi;
				$newfarm->cooperative_id = $model->cooperative_id;
				$newfarm->surveydate = $model->surveydate;
				$newfarm->groundsign = $model->groundsign;
				
				$newfarm->farmersign = $model->farmersign;
				$newfarm->create_at = time ();
				$newfarm->update_at = time ();
				$newfarm->pinyin = $model->pinyin;
				$newfarm->farmerpinyin = $model->farmerpinyin;
				$newfarm->state = 1;
				$newfarm->notclear = $model->notclear;
				$newfarm->oldfarms_id = $oldfarms_id;
				
				// $newfarm->save();
			}
			
			$ttpozongdi = new Ttpozongdi ();
			$ttpozongdi->oldfarms_id = $oldfarm->id;
			$ttpozongdi->newfarms_id = $model->id;
			$ttpozongdi->zongdi = $model->zongdi;
			$ttpozongdi->oldzongdi = $oldfarm->zongdi;
			$ttpozongdi->create_at = $oldfarm->update_at;
			$ttpozongdi->ttpozongdi = Yii::$app->request->post ( 'ttpozongdi' );
			$ttpozongdi->ttpoarea = Yii::$app->request->post ( 'ttpoarea' );
			
			// $ttpozongdi->save();
			
			return $this->redirect ( [ 
					'farmsttpomenu',
					'farms_id' => $newfarm->id 
			] );
		} else {
			
			return $this->render ( 'farmstozongdi', [ 
					'oldFarm' => $oldfarm,
					'model' => $model 
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
	public function showFarmmenu($farms_id) {
		$businessmenu = MenuToUser::find ()->where ( [ 
				'user_id' => \Yii::$app->getUser ()->id 
		] )->one ()['businessmenu'];
		$arrayBusinessMenu = explode ( ',', $businessmenu );
		$html = '<div class="row" >';
		
		for($i = 0; $i < count ( $arrayBusinessMenu ); $i ++) {
			// if($i == 0)
			// echo '<div class="row">';
			// if($i%4 == 0) {
			
			// }
			$menuUrl = Mainmenu::find ()->where ( [ 
					'id' => $arrayBusinessMenu [$i] 
			] )->one ();
			$html .= $this->showMenuPic ( $menuUrl, $farms_id );
			// if($i == 0)
			// echo '</div>';
			// if($i%4 == 0) {
			
			// }
		}
		$html .= '</div>';
		
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
				$ttop = Ttpo::find ()->where ( [ 
						'newfarms_id' => $farms_id 
				] )->count ();
				$ttopzongdi = Ttpozongdi::find ()->where ( [ 
						'newfarms_id' => $farms_id 
				] )->count ();
				$value ['info'] = '无过户转让信息';
				if(Farms::getLocked($farms_id))
					$value['info'] = '已冻结';
				if ($ttop)
					$value ['info'] = '过户' . $ttop . '次';
				if ($ttopzongdi)
					$value ['info'] .= ' 转让' . $ttopzongdi . '次';
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
				$value ['info'] = '种植了' . Plantingstructure::find ()->where ( [ 
						'farms_id' => $_GET ['farms_id'] 
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
				$value ['info'] = '有' . Yields::find ()->where ( [ 
						'farms_id' => $_GET ['farms_id'] 
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '条产量信息';
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
				$breedinfo = Breedinfo::find ()->where ( [ 
						'breed_id' => Breed::find ()->where ( [ 
								'farms_id' => $_GET ['farms_id'] 
						] )->one ()['id'] 
				] )->all ();
				$breeds = false;
				foreach ( $breedinfo as $val ) {
					$breeds = $val ['number'] . Breedtype::find ()->where ( [ 
							'id' => $val ['breedtype_id'] 
					] )->one ()['unit'] . Breedtype::find ()->where ( [ 
							'id' => $val ['breedtype_id'] 
					] )->one ()['typename'];
				}
				if($breeds)
					$value ['info'] = '养殖' . $breeds;
				else
					$value['info'] = '无养殖信息';
				$value ['description'] = '养殖信息';
				break;
			case 'prevention' :
				$value ['icon'] = 'fa fa-plus';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '有'.Prevention::find()->where(['farms_id'=>$_GET['farms_id']])->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count().'条防疫信息';
				$value ['description'] = ' 防疫率,疫苗接种等信息';
				break;
			case 'projectapplication' :
				$value ['icon'] = 'fa fa-sticky-note-o';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '申报了' . Projectapplication::find ()->where ( [ 
						'farms_id' => $_GET ['farms_id'] 
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '个项目';
				$value ['description'] = ' 防疫率,疫苗接种等信息';
				break;
			case 'disaster' :
				$value ['icon'] = 'fa fa-soundcloud';
				$value ['title'] = $menuUrl ['menuname'];
				$value ['url'] = Url::to ( 'index.php?r=' . $menuUrl ['menuurl'] . '&farms_id=' . $farms_id );
				$value ['info'] = '有' . Disaster::find ()->where ( [ 
						'farms_id' => $_GET ['farms_id'] 
				] )->andWhere ( 'update_at>=' . Theyear::getYeartime ()[0] )->andWhere ( 'update_at<=' . Theyear::getYeartime ()[1] )->count () . '个灾害';
				$value ['description'] = ' 防疫率,疫苗接种等信息';
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
					$value ['info'] = '本年度承包佛未收缴或有欠费';
				$value ['description'] = '地产科确认承包费并发送至财务科';
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
