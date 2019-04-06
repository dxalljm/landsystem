<?php

namespace frontend\controllers;

use app\models\Farmerinfo;
use app\models\Logs;
use app\models\Machineapply;
use app\models\Machinescanning;
use app\models\ManagementArea;
use Yii;
use app\models\Afterchenqian;
use frontend\models\AfterchenqianSearch;
use yii\debug\models\search\Log;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use app\models\Farmer;
use app\models\Electronicarchives;
use frontend\helpers\imageClass;

/**
 * AfterchenqianController implements the CRUD actions for Afterchenqian model.
 */
class PhotographController extends Controller
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
     * Lists all Afterchenqian models.
     * @return mixed
     */

	public function actionGetphoto($farms_id,$select)
	{
		$selectArray = explode('-', $select);
		if($selectArray[0] == 'electronicarchives') {

		} else {
			$model = Farmerinfo::find()->where(['cardid'=>Farms::getFarmsCardID($farms_id)])->one();
		}
		$photo = $model[$selectArray[1]];
		if($photo)
			$photoInfo = imageClass::getImageInfo($photo);
		else
			$photoInfo = ['width'=>0,'height'=>0];
		echo json_encode(['url' => 'http://192.168.1.10/'.$photo,'info'=>$photoInfo,'page'=>0,'id'=>$farms_id]);
	}

    public function actionPhotographindex($farms_id,$select=null)
    {
		Logs::writeLogs('电子信息采集');
    	$eaResult = [];
		$ea = [];
    	if(!empty($select)) {
			$selectArray = explode('-', $select);
//			$class = 'app\\models\\'.ucfirst($selectArray[0]);
			if($selectArray[0] == 'electronicarchives') {
				Logs::writeLogs('电子信息采集-合同扫描件');
				$data = Electronicarchives::find()->where(['farms_id'=>$farms_id])->orderBy('pagenumber DESC')->one();
				$ea = Electronicarchives::find()->where(['farms_id'=>$farms_id])->all();
				$eacount = count($ea);
				$i=0;
				for($j=0;$j<$eacount;$j++) {
					if($j%4 == 0 and $j != 0)
						$i++;
					$eaResult[$i][] = $ea[$j];
					
				}
				$model = Electronicarchives::findOne($data['id']);
				if($model)
					$pagenumber = $model->pagenumber;
				else
					$pagenumber = 0;
				$photo = '';
				$photoInfo = ['width'=>0,'height'=>0];
//				var_dump($ea);exit;
//				var_dump($eaResult);exit;
			}
			else {
				$model = Farmerinfo::find()->where(['cardid'=>Farms::getFarmsCardID($farms_id)])->one();
				Logs::writeLogs('电子信息采集-法人身份信息扫描',$model);
				$pagenumber = 0;
				$photo = $model[$selectArray[1]];
//				var_dump($photo);
				if($photo)
					$photoInfo = imageClass::getImageInfo($photo);
				else
					$photoInfo = ['width'=>0,'height'=>0];
			}

		} else {
			$cardid = Farms::getFarmsCardID($farms_id);
			$model = Farmerinfo::find()->where(['cardid'=>$cardid])->one();
			$photo = '';
			$photoInfo = ['width'=>0,'height'=>0];
			$pagenumber = 0;
		}
		$farm = Farms::findOne($farms_id);
// 		$ea = Electronicarchives::find()->where(['farms_id'=>$farms_id])->all();
		$selectItem = ['prampt'=>'请选择...'];
// 		if($farmer['photo'] == '') {
			$selectItem['farmerinfo-photo']='法人近照';
// 		}
// 		if($farmer['cardpic'] == '') {
			$selectItem['farmerinfo-cardpic']='身份证扫描件正面';
// 		}
// 		if($farmer['cardpicback'] == '') {
			$selectItem['farmerinfo-cardpicback']='身份证扫描件反面';
// 		}
// 		if(!$ea) {
			$selectItem['electronicarchives-archivesimage']='合同扫描件';
// 		}
//		var_dump($photo);
        return $this->render('photographindex', [
           		'selectItem' => $selectItem,
        		'farms_id' => $farms_id,
        		'model' => $model,
        		'pagenumber' => $pagenumber,
        		'photo' => $photo,
        		'photoInfo' => $photoInfo,
        		'farm' => $farm,
        		'ea' => $eaResult,
        		'select' => $select,
        ]);
    }

	public function actionPhotographmachine($apply_id,$select=NULL)
	{
		$eaResult = [];
		$machineapply = Machineapply::findOne($apply_id);
		$farm = Farms::findOne($machineapply->farms_id);
		$model = Farmerinfo::find()->where(['cardid'=>$machineapply->cardid])->one();
		if(empty($model)) {
			$farmer = Farmer::find()->where(['farms_id'=>$machineapply->farms_id])->one();
			$model = new Farmerinfo();
			$model->create_at = time();
			$model->update_at = $model->create_at;
			$model->cardid = (string)$farm['cardid'];
			$model->farmerbeforename = $farmer['farmerbeforename'];
			$model->gender = $farmer['gender'];
			$model->nation = $farmer['nation'];
			$model->political_outlook = $farmer['political_outlook'];
			$model->cultural_degree = $farmer['cultural_degree'];
			$model->domicile = $farmer['domicile'];
			$model->nowlive = $farmer['nowlive'];

			$model->photo = $farmer['photo'];
			$model->cardpic = $farmer['cardpic'];
			$model->cardpicback = $farmer['cardpicback'];
			$model->update_at = time();
			$model->save();
			Logs::writeLogs('农机补贴资料上传-创建法人信息',$model);
		}
		$ea = [];
		if(!empty($select)) {
			$selectArray = explode('-', $select);
//			$class = 'app\\models\\'.ucfirst($selectArray[0]);
			if($selectArray[0] == 'machinescanning') {
				Logs::writeLogs('农机补贴资料上传-农机证明材料');
				$data = Machinescanning::find()->where(['cardid'=>$machineapply->cardid,'farms_id'=>$farm['id'],'machineapplymachine_id'=>$machineapply['machineoffarm_id']])->orderBy('pagenumber DESC')->one();
				$ea = Machinescanning::find()->where(['cardid'=>$machineapply->cardid,'farms_id'=>$farm['id'],'machineapplymachine_id'=>$machineapply['machineoffarm_id']])->all();
				$eacount = count($ea);
				$i=0;
				for($j=0;$j<$eacount;$j++) {
					if($j%4 == 0 and $j != 0)
						$i++;
					$eaResult[$i][] = $ea[$j];

				}
				$model = Machinescanning::findOne($data['id']);
				if($model)
					$pagenumber = $model->pagenumber;
				else
					$pagenumber = 0;
				$photo = '';
				$photoInfo = ['width'=>0,'height'=>0];
//				var_dump($ea);exit;
//				var_dump($eaResult);exit;
			}
			else {

				$pagenumber = 0;
				$photo =  $model[$selectArray[1]];
//				var_dump($photo);exit;
//				if($photo)
//					$photoInfo = imageClass::getImageInfo(iconv("UTF-8","gbk//TRANSLIT",$photo));
//				else
//					$photoInfo = ['width'=>0,'height'=>0];
			}

		} else {
//			$cardid = Farms::getFarmsCardID($machineapply->farms_id);
			$model = Farmerinfo::find()->where(['cardid'=>$machineapply->cardid])->one();
			Logs::writeLogs('农机补贴资料上传-法人身份信息');
			$photo = '';
			$photoInfo = ['width'=>0,'height'=>0];
			$pagenumber = 0;
		}
		$farm = Farms::findOne($machineapply->farms_id);
// 		$ea = Electronicarchives::find()->where(['farms_id'=>$farms_id])->all();
		$selectItem = ['prampt'=>'请选择...'];
// 		if($farmer['photo'] == '') {
		$selectItem['farmerinfo-photo']='法人近照';
// 		}
// 		if($farmer['cardpic'] == '') {
		$selectItem['farmerinfo-cardpic']='身份证扫描件正面';
// 		}
// 		if($farmer['cardpicback'] == '') {
		$selectItem['farmerinfo-cardpicback']='身份证扫描件反面';
// 		}
// 		if(!$ea) {
		$selectItem['machinescanning-scanimage']='扫描相关材料';
// 		}
//		var_dump($photo);
		return $this->render('photographmachine', [
			'selectItem' => $selectItem,
			'farms_id' => $machineapply->farms_id,
			'model' => $model,
			'pagenumber' => $pagenumber,
			'photo' => $photo,
//			'photoInfo' => $photoInfo,
			'farm' => $farm,
			'ea' => $eaResult,
			'select' => $select,
			'apply_id' => $apply_id,
		]);
	}

	public function actionPhotographexplode($farms_id)
	{
		$farm = Farms::findOne($farms_id);
		$areaname = ManagementArea::getAreaname($farm['management_area']);
		$farmerinfo = Farmerinfo::findOne($farm['cardid']);
		$this->render('photographexplode',[
			'path' => $areaname.'/'.$farm['farmername'],
			'file' => ['photo'=>$farmerinfo['photo'],'cardpic'=>$farmerinfo['cardpic'],'cardpicback'=>$farmerinfo['cardpicback']]
		]);
	}

	public function actionPhotographcontractclaim($farms_id,$select=NULL)
	{
		$eaResult = [];
		if(!empty($select)) {
//     		var_dump($select);exit;
			$selectArray = explode('-', $select);
			$class = 'app\\models\\'.ucfirst($selectArray[0]);
// 			var_dump($class);
			if($selectArray[0] == 'electronicarchives') {
				$data = $class::find()->where(['farms_id'=>$farms_id])->orderBy('pagenumber DESC')->one();
				$ea = $class::find()->where(['farms_id'=>$farms_id])->all();
// 				$eaResult = [];
// 				$j=0;
				$eacount = count($ea);
				$i=0;
// 				var_dump($row);exit;
				for($j=0;$j<$eacount;$j++) {
					if($j%4 == 0 and $j != 0)
						$i++;
					$eaResult[$i][] = $ea[$j];

				}
// 				var_dump($eaResult);exit;
				$model = $class::findOne($data['id']);
				if($model)
					$pagenumber = $model->pagenumber;
				else
					$pagenumber = 0;
// 				var_dump($ea);exit;
			}
			else {
				$data = $class::find()->where(['farms_id'=>$farms_id])->one();
				$model = $class::findOne($data['id']);
				$ea = '';
				$pagenumber = 0;
			}


			$photo = $model[$selectArray[1]];
			if($photo)
				$photoInfo = imageClass::getImageInfo(iconv("UTF-8","gbk//TRANSLIT", $photo));
			else
				$photoInfo = ['width'=>0,'height'=>0];
// 			var_dump($photoInfo);exit;
		}
		else {
			$cardid = Farms::getFarmsCardID($farms_id);
			$model = Farmerinfo::find()->where(['cardid'=>$cardid])->one();
			$photo = '';
			$photoInfo = ['width'=>0,'height'=>0];
			$pagenumber = 0;
			$ea = '';
		}
// 		$farmer =
		$farm = Farms::findOne($farms_id);
// 		$ea = Electronicarchives::find()->where(['farms_id'=>$farms_id])->all();
		$selectItem = ['prampt'=>'请选择...'];
// 		if($farmer['photo'] == '') {
		$selectItem['farmerinfo-photo']='法人近照';
// 		}
// 		if($farmer['cardpic'] == '') {
		$selectItem['farmerinfo-cardpic']='身份证扫描件正面';
// 		}
// 		if($farmer['cardpicback'] == '') {
		$selectItem['farmerinfo-cardpicback']='身份证扫描件反面';
// 		}
// 		if(!$ea) {
		$selectItem['electronicarchives-archivesimage']='合同扫描件';
// 		}
		$farmer = Farmerinfo::find()->where(['cardid'=>$farm->cardid])->one();
		return $this->render('photographcontractclaim', [
			'selectItem' => $selectItem,
			'farms_id' => $farms_id,
			'model' => $model,
			'pagenumber' => $pagenumber,
			'photo' => $photo,
			'photoInfo' => $photoInfo,
			'farm' => $farm,
			'ea' => $eaResult,
			'select' => $select,
			'farmer' => $farmer,
		]);
	}

    public function actionPhotogrpahcontract()
    {
    	return $this->render('photographshow', [
    			 
    	]);
    }
}
