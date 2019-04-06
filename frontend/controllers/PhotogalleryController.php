<?php

namespace frontend\controllers;

use app\models\Farmerinfo;
use app\models\Logs;
use app\models\Machineapply;
use app\models\Machinescanning;
use Yii;
use app\models\Photogallery;
use frontend\models\PhotogallerySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use frontend\helpers\fileUtil;
use app\models\Tables;
use app\models\Tablefields;
use app\models\Farms;
use app\models\ManagementArea;
use app\models\Farmer;
use app\models\Electronicarchives;
use frontend\helpers\WaterMask;
use app\models\Loan;
/**
 * PhotogalleryController implements the CRUD actions for Photogallery model.
 */
class PhotogalleryController extends Controller
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
	 * Lists all Photogallery models.
	 * @return mixed
	 */
	public function actionPhotogalleryindex()
	{
		$searchModel = new PhotogallerySearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('photogalleryindex', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Photogallery model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPhotogalleryview($id)
	{
		return $this->render('photogalleryview', [
			'model' => $this->findModel($id),
		]);
	}

	public function actionFileupload()
	{

		$file = UploadedFile::getInstanceByName('upload_file');
		$extphoto = strtolower(pathinfo($file->name, PATHINFO_EXTENSION));
		$strc = Tables::getCtablename($_GET['controller']);
		$stra = Tablefields::getCfields($_GET['controller'],$_GET['field']);
		$farm = Farms::find()->where(['id'=>$_GET['farms_id']])->one();
		$management_area = ManagementArea::getAreanameOne(Farms::getFarmsAreaID($_GET['farms_id']));

		$filename = time();
//		chmod('photo_gallery/'.$management_area,0777);
		$path = 'photo_gallery/'.$management_area.'/'.$farm['farmname'].'-'.$farm['farmername'].'/'.$strc.'/'.$stra;
//		var_dump($path);
		fileUtil::createDir($path);
		$filepath = $path.'/'.$filename.'.'.$extphoto;
		$file->saveAs($filepath);
		echo json_encode(['url' => $filepath]);
	}

	public function actionFtpupload($farms_id,$select)
	{
		$ftp = Yii::$app->ftp;

		$selectArray = explode('-',$_GET['select']);
		$strc = iconv("UTF-8","gbk//TRANSLIT",Tables::getCtablename($selectArray[0]));
		$stra = iconv("UTF-8","gbk//TRANSLIT",Tablefields::getCfields($selectArray[0],$selectArray[1]));
		$farm = Farms::find()->where(['id'=>$_GET['farms_id']])->one();
		$management_area = iconv("UTF-8","gbk//TRANSLIT",ManagementArea::getAreanameOne(Farms::getFarmsAreaID($_GET['farms_id'])));

		$filename = time().'jpg';
//		chmod('photo_gallery/'.$management_area,0777);
		$path = 'photo_gallery/'.$management_area.'/'.iconv("UTF-8","gbk//TRANSLIT",$farm['farmname']).'-'.iconv("UTF-8","gbk//TRANSLIT",$farm['farmername']).'/'.$strc.'/'.$stra;
		$uploadpath = $management_area.'/'.iconv("UTF-8","gbk//TRANSLIT",$farm['farmname']).'-'.iconv("UTF-8","gbk//TRANSLIT",$farm['farmername']).'/'.$strc.'/'.$stra;
		$filepath = $uploadpath.'/'.$filename;
		$ftp->put('/imagesJPG.jpg',$filepath);
		Logs::writeLogs('上传图片到FTP');
	}

	public function getImageInfo($images)
	{
		$img_info = getimagesize($images);
		switch ($img_info[2]){
			case 1:
				$imgtype = "gif";
				break;
			case 2:
				$imgtype = "jpg";
				break;
			case 3:
				$imgtype = "png";
				break;
		}
		$img_type = $imgtype;
		//获取文件大小
		$img_size = ceil(filesize($images)/1000)."k";
		$new_img_info = array (
			'file' => $images,
			"width"=>$img_info[0], //图像宽
			"height"=>$img_info[1], //图像高
			"type"=>$img_type, //图像类型
			"size"=>$img_size //图像大小
		);
		return $new_img_info;
	}

	private function getPath($file)
	{
//     	var_dump($file);exit;
		$array = explode('/',$file);
		$row = count($array);
		unset($array[$row-1]);
		$result = implode('/', $array);
//     	var_dump($result);exit;
<<<<<<< HEAD
		return $result;
	}
	private function getFilename($file)
	{
		if($file == '')
			return false;
		$array = explode('/',$file);
		$row = count($array);
		$result = $array[$row-1];
		if($result)
			return $result;
		else
			return false;
	}

	public function dirArray($array)
	{
		$result = [];
		foreach($array as $value) {
//			print_r($value->filename);
			$result[] = $value->filename;
		}
		return $result;
	}

	public function actionPhotograph($farms_id,$select,$eid = NULL)
	{
		$waterMask = new WaterMask('uploadimage/imgTemp/JPG.jpg');
		$waterMask->output();
		$ftp = Yii::$app->ftp;
//		print_r($ftp->ls());exit;
		$imageInfo = $this->getImageInfo('uploadimage/imgTemp/JPG.jpg');
//    	$file = UploadedFile::loadFiles($imageInfo);
//    	$extphoto = strtolower(pathinfo('/uploadimage/imgTemp/JPG.jpg', PATHINFO_EXTENSION));
//     	var_dump($extphoto);exit;
		$selectArray = explode('-',$select);
//     	var_dump($selectArray);exit;
		$farm = Farms::findOne($farms_id);
//		$cardidFarms = Farms::find()->where(['cardid'=>$farm['cardid'],'farmername'=>$farm['farmername']])->all();
//     	var_dump($cardidFarms);exit;
		$pn = 0;
//		foreach ($cardidFarms as $value) {
//     		var_dump($value['farmername']);exit;
			$strc = iconv("UTF-8","gbk//TRANSLIT", $farm['farmname'].'-'.$farm['farmername']);
			$stra = iconv("UTF-8","gbk//TRANSLIT", Tablefields::getCfields($selectArray[0],$selectArray[1]));
//			$management_area_id = Farms::getFarmsAreaID($value['id']);
			$management_area = iconv("UTF-8","gbk//TRANSLIT", ManagementArea::getAreanameOne($farm['management_area']));
//			var_dump($management_area);
			$filename = time().'.jpg';
			$path = 'photo_gallery/'.$management_area.'/'.$strc.'/'.$stra;
//			var_dump($path);
			$filepath = $path.'/'.$filename;
			$uploadpath = $management_area.'/'.$strc.'/'.$stra;
			$uploadfile = $uploadpath.'/'.$filename;

//			var_dump($path);
//			exit;
//    		fileUtil::createDir($path);
		$gen = $this->dirArray($ftp->ls());
		foreach(explode('/',$uploadpath) as $value) {
			switch ($value) {
//				case 'machine_image':
//					if (in_array($value, $gen)) {
//						$gen = $this->dirArray($ftp->ls($value));
//						$ftp->chdir($value);
//					} else {
//						$ftp->mkdir($value);
//						$ftp->chdir($value);
//					}
//					break;
				case $management_area:
					if (in_array($value, $gen)) {
						$gen = $this->dirArray($ftp->ls($value));
						$ftp->chdir($value);
					} else {
						$ftp->mkdir($value);
						$ftp->chdir($value);
					}
					break;
				case $strc:
					if (in_array($value, $gen)) {
						$gen = $this->dirArray($ftp->ls($value));
						$ftp->chdir($value);
					} else {
						$ftp->mkdir($value);
						$ftp->chdir($value);
					}
					break;
				case $stra:
					if (in_array($value, $gen)) {
						$gen = $this->dirArray($ftp->ls($value));
						$ftp->chdir($value);
					} else {
						$ftp->mkdir($value);
						$ftp->chdir($value);
					}
					break;
			}
		}
//			var_dump($ftp->put('uploadimage/imgTemp/JPG.jpg',$uploadfile));exit;
			if($ftp->put('uploadimage/imgTemp/JPG.jpg',$filename)) {
//     				echo '111111';exit;
				if($selectArray[0] == 'electronicarchives') {
					//     			echo '000';
					$ea = Electronicarchives::find()->where(['farms_id'=>$farm['id']])->orderBy('pagenumber DESC')->one()['pagenumber'];

					if($ea)
						$pn = ++$ea;
					else
						$pn = 1;
					//     			var_dump($pn);exit;
					if($eid) {
						$model = Electronicarchives::findOne($eid);
						$model->update_at = time();
					}
					else {
						$model = new Electronicarchives();
						$model->farms_id = $farm['id'];
						$model->archivesimage = iconv("gbk//TRANSLIT", "UTF-8", $filepath);
						$model->create_at = (string)time();
						$model->update_at = $model->create_at;
						$model->pagenumber = $pn;
					}
					$model->save();
					//     			var_dump($model);
				} else {
//     				echo '2222';exit;
//					$class = 'app\\models\\'.ucfirst($selectArray[0]);
					$model = Farmerinfo::find()->where(['cardid'=>$farm['cardid']])->one();
//    				$model = $class::findOne($temp);
//					$farmerinfo = Farmerinfo::findOne($farm->cardid);
//					$farmerinfo->photo =
//     				var_dump($model);exit;
					if($od=opendir($this->getPath($path))) //$d是目录名
					{
						if(file_exists(iconv("UTF-8","gbk//TRANSLIT", $model->$selectArray[1]))){
							unlink(iconv("UTF-8","gbk//TRANSLIT", $model->$selectArray[1]));
						}
					}
					$model->$selectArray[1] = iconv("gbk//TRANSLIT", "UTF-8", $filepath);
					$model->save();
//     				var_dump($model);
				}
			}
//		}
//     	var_dump($imageInfo);var_dump($pn);exit;
		echo json_encode(['url' => 'http://192.168.1.10/'.iconv("GBK", "UTF-8", $filepath),'info'=>$imageInfo,'page'=>$pn,'id'=>$model->id]);
	}

	public function actionPhotographmachine($apply_id,$select,$eid = NULL)
	{
		$machineapply = Machineapply::findOne($apply_id);
		$waterMask = new WaterMask('uploadimage/imgTemp/JPG.jpg');
		$waterMask->output();
		$ftp = Yii::$app->ftp;
//		print_r($ftp->ls());exit;
		$imageInfo = $this->getImageInfo('uploadimage/imgTemp/JPG.jpg');
//    	$file = UploadedFile::loadFiles($imageInfo);
//    	$extphoto = strtolower(pathinfo('/uploadimage/imgTemp/JPG.jpg', PATHINFO_EXTENSION));
=======
    	return $result;
    }
    private function getFilename($file)
    {
    	if($file == '')
    		return false;
    	$array = explode('/',$file);
    	$row = count($array);
    	$result = $array[$row-1];
    	if($result)
    		return $result;
    	else
    		return false;
    }
    public function actionPhotograph($farms_id,$select,$eid = NULL)
    {
    	$waterMask = new WaterMask('D:\\wamp\\www\landsystem\\frontend\\web\\uploadimage\\imgTemp\\JPG.jpg');
    	$waterMask->output();
    	
		$imageInfo = $this->getImageInfo('D:\\wamp\\www\landsystem\\frontend\\web\\uploadimage\\imgTemp\\JPG.jpg');
    	$file = UploadedFile::loadFiles($imageInfo);
    	$extphoto = strtolower(pathinfo('D:\\wamp\\www\landsystem\\frontend\\web\\uploadimage\\imgTemp\\JPG.jpg', PATHINFO_EXTENSION));
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
//     	var_dump($extphoto);exit;
		$selectArray = explode('-',$select);
//     	var_dump($selectArray);exit;
<<<<<<< HEAD
		$farm = Farms::findOne($machineapply->farms_id);
//		$cardidFarms = Farms::find()->where(['cardid'=>$farm['cardid'],'farmername'=>$farm['farmername']])->all();
//     	var_dump($cardidFarms);exit;
		$pn = 0;
//		foreach ($cardidFarms as $value) {
//     		var_dump($value['farmername']);exit;
			$strc = iconv("UTF-8","gbk//TRANSLIT", $farm['farmname'].'-'.$farm['farmername']);
			$stra = iconv("UTF-8","gbk//TRANSLIT", Tablefields::getCfields($selectArray[0],$selectArray[1]));
//			$management_area_id = Farms::getFarmsAreaID($value['id']);
			$management_area = iconv("UTF-8","gbk//TRANSLIT", ManagementArea::getAreanameOne($farm['management_area']));
//			var_dump($management_area);
			$filename = time().'.jpg';
			$path = 'photo_gallery/machine_image/'.$management_area.'/'.$strc.'/'.$stra;
			$farmerpath = 'photo_gallery/'.$management_area.'/'.$strc.'/'.$stra;
//			var_dump($path);
			$filepath = $path.'/'.$filename;
			$farmerfilepath = $farmerpath.'/'.$filename;
			$uploadpath = 'machine_image/'.$management_area.'/'.$strc.'/'.$stra;
			$uploadfile = $uploadpath.'/'.$filename;
		$farmeruploadpath = $management_area.'/'.$strc.'/'.$stra;
		$farmeruploadfile = $uploadpath.'/'.$filename;

//			var_dump($path);
//			exit;
//    		fileUtil::createDir($path);
		$ftp->chdir('/');
		if($selectArray[0] == 'machinescanning') {
			$gen = $this->dirArray($ftp->ls());
			foreach (explode('/', $uploadpath) as $value) {
				switch ($value) {
					case 'machine_image':
						if (in_array($value, $gen)) {
							$gen = $this->dirArray($ftp->ls($value));
							$ftp->chdir($value);
						} else {
							$ftp->mkdir($value);
							$ftp->chdir($value);
						}
						break;
					case $management_area:
						if (in_array($value, $gen)) {
							$gen = $this->dirArray($ftp->ls($value));
							$ftp->chdir($value);
						} else {
							$ftp->mkdir($value);
							$ftp->chdir($value);
						}
						break;
					case $strc:
						if (in_array($value, $gen)) {
							$gen = $this->dirArray($ftp->ls($value));
							$ftp->chdir($value);
						} else {
							$ftp->mkdir($value);
							$ftp->chdir($value);
						}
						break;
					case $stra:
						if (in_array($value, $gen)) {
							$gen = $this->dirArray($ftp->ls($value));
							$ftp->chdir($value);
						} else {
							$ftp->mkdir($value);
							$ftp->chdir($value);
						}
						break;
				}
			}

		} else {
			$gen = $this->dirArray($ftp->ls());
			foreach (explode('/', $farmeruploadpath) as $value) {
				switch ($value) {
					case 'machine_image':
						if (in_array($value, $gen)) {
							$gen = $this->dirArray($ftp->ls($value));
							$ftp->chdir($value);
						} else {
							$ftp->mkdir($value);
							$ftp->chdir($value);
						}
						break;
					case $management_area:
						if (in_array($value, $gen)) {
							$gen = $this->dirArray($ftp->ls($value));
							$ftp->chdir($value);
						} else {
							$ftp->mkdir($value);
							$ftp->chdir($value);
						}
						break;
					case $strc:
						if (in_array($value, $gen)) {
							$gen = $this->dirArray($ftp->ls($value));
							$ftp->chdir($value);
						} else {
							$ftp->mkdir($value);
							$ftp->chdir($value);
						}
						break;
					case $stra:
						if (in_array($value, $gen)) {
							$gen = $this->dirArray($ftp->ls($value));
							$ftp->chdir($value);
						} else {
							$ftp->mkdir($value);
							$ftp->chdir($value);
						}
						break;
				}
			}

		}

//			var_dump($ftp->put('uploadimage/imgTemp/JPG.jpg',$uploadfile));exit;
			if($ftp->put('uploadimage/imgTemp/JPG.jpg',$filename)) {
//     				echo '111111';exit;
				if($selectArray[0] == 'machinescanning') {
					//     			echo '000';
					$ea = Machinescanning::find()->where(['farms_id'=>$farm['id'],'machineapplymachine_id'=>$machineapply['machineoffarm_id']])->orderBy('pagenumber DESC')->one()['pagenumber'];

					if($ea)
						$pn = ++$ea;
					else
						$pn = 1;
					//     			var_dump($pn);exit;
					if($eid) {
						$model = Machinescanning::findOne($eid);
						$model->update_at = time();
					}
					else {
						$model = new Machinescanning();
						$model->farms_id = $farm['id'];
						$model->cardid = $farm['cardid'];
						$model->scanimage = iconv("gbk//TRANSLIT", "UTF-8", $filepath);
						$model->create_at = time();
						$model->update_at = $model->create_at;
						$model->pagenumber = $pn;
						$model->machineapplymachine_id = $machineapply['machineoffarm_id'];
					}
					$model->save();
					$id = $model->id;
					echo json_encode(['url' => 'http://192.168.1.10/'.iconv("GBK", "UTF-8", $filepath),'info'=>$imageInfo,'page'=>$pn,'id'=>$id]);
					//     			var_dump($model);
				} else {
//     				echo '2222';exit;
//					$class = 'app\\models\\'.ucfirst($selectArray[0]);
					$model = Farmerinfo::find()->where(['cardid'=>$farm['cardid']])->one();

//    				$model = $class::findOne($temp);
//					$farmerinfo = Farmerinfo::findOne($farm->cardid);
//					$farmerinfo->photo =
//     				var_dump($farmerpath);exit;
//					if($od=opendir($this->getPath($farmerpath))) //$d是目录名
//					{
//						if(file_exists(iconv("UTF-8","gbk//TRANSLIT", $model->$selectArray[1]))){
//							unlink(iconv("UTF-8","gbk//TRANSLIT", $model->$selectArray[1]));
//						}
//					}
					$model->$selectArray[1] = iconv("gbk//TRANSLIT", "UTF-8", $farmerfilepath);
					$model->save();
					$id = $farm['id'];
					echo json_encode(['url' => 'http://192.168.1.10/'.iconv("GBK", "UTF-8", $farmerfilepath),'info'=>$imageInfo,'page'=>$pn,'id'=>$id]);
//     				var_dump($model);
				}
			}
//		}
//     	var_dump($imageInfo);var_dump($pn);exit;

	}

	public function actionPhotographdialog($farms_id,$field)
	{

		$imageInfo = $this->getImageInfo('D:\\wamp\\www\landsystem\\frontend\\web\\uploadimage\\imgTemp\\JPG.jpg');
		$file = UploadedFile::loadFiles($imageInfo);
		$extphoto = strtolower(pathinfo('D:\\wamp\\www\landsystem\\frontend\\web\\uploadimage\\imgTemp\\JPG.jpg', PATHINFO_EXTENSION));

		$farm = Farms::findOne($farms_id);
		$cardidFarms = Farms::find()->where(['cardid'=>$farm['cardid'],'farmername'=>$farm['farmername']])->all();
		foreach ($cardidFarms as $value) {
			$strc = iconv("UTF-8","gbk//TRANSLIT", $value['farmname'].'-'.$value['farmername']);
			$stra = iconv("UTF-8","gbk//TRANSLIT", Tablefields::getCfields('farmer',$field));
//     		var_dump($stra);exit;
			$management_area_id = Farms::getFarmsAreaID($value['id']);
			$management_area = iconv("UTF-8","gbk//TRANSLIT", ManagementArea::getAreanameOne($management_area_id));

			$filename = time();
			$path = 'photo_gallery/'.$management_area.'/'.$strc.'/'.$stra;
			fileUtil::createDir($path);
			$filepath = $path.'/'.$filename.'.'.$extphoto;

			if($file->saveAs2($filepath)) {
				$farmer_id = Farmer::find()->where(['farms_id'=>$value['id']])->one()['id'];
				$model = Farmer::findOne($farmer_id);
				if($od=opendir($this->getPath($path))) //$d是目录名
				{
//     					var_dump($model);
//     					var_dump(file_exists(iconv("UTF-8","gbk//TRANSLIT", $model->$field)));exit;
					if(file_exists(iconv("UTF-8","gbk//TRANSLIT", $model->$field))){
						unlink(iconv("UTF-8","gbk//TRANSLIT", $model->$field));
					}
				}
				$model->$field = iconv("GBK", "UTF-8", $filepath);
				$model->save();
//     				var_dump($model->getErrors());exit;
			}
		}
		echo json_encode(['url' => iconv("GBK", "UTF-8", $filepath),'id'=>$model->id]);
	}

	public function actionPhotographdialogloan($loan_id,$field)
	{

		$imageInfo = $this->getImageInfo('D:\\wamp\\www\landsystem\\frontend\\web\\uploadimage\\imgTemp\\JPG.jpg');
		$file = UploadedFile::loadFiles($imageInfo);
		$extphoto = strtolower(pathinfo('D:\\wamp\\www\landsystem\\frontend\\web\\uploadimage\\imgTemp\\JPG.jpg', PATHINFO_EXTENSION));

		$loan = Loan::findOne($loan_id);
		$farm = Farms::findOne($loan->farms_id);

		$strc = iconv("UTF-8","gbk//TRANSLIT", $farm->farmname.'-'.$farm->farmername);
		$stra = iconv("UTF-8","gbk//TRANSLIT", Tablefields::getCfields('loan',$field));
		//     		var_dump($stra);exit;
		$management_area_id = Farms::getFarmsAreaID($farm->id);
		$management_area = iconv("UTF-8","gbk//TRANSLIT", ManagementArea::getAreanameOne($management_area_id));

		$filename = time();
		$path = 'photo_gallery/'.$management_area.'/'.$strc.'/'.$stra;
		fileUtil::createDir($path);
		$filepath = $path.'/'.$filename.'.'.$extphoto;

		if($file->saveAs2($filepath)) {
//     			$loan_id = Loan::find()->where(['farms_id'=>$value['id']])->one()['id'];
//     			$model = Farmer::findOne($farmer_id);
			if($od=opendir($this->getPath($path))) //$d是目录名
			{
				//     					var_dump($model);
				//     					var_dump(file_exists(iconv("UTF-8","gbk//TRANSLIT", $model->$field)));exit;
				if(file_exists(iconv("UTF-8","gbk//TRANSLIT", $loan->$field))){
					unlink(iconv("UTF-8","gbk//TRANSLIT", $loan->$field));
				}
			}
			$loan->$field = iconv("GBK", "UTF-8", $filepath);
			$loan->save();
			//     				var_dump($model->getErrors());exit;
		}

		echo json_encode(['url' => iconv("GBK", "UTF-8", $filepath),'id'=>$loan->id]);
	}

	/**
	 * Creates a new Photogallery model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionPhotogallerycreate()
	{
		$model = new Photogallery();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['photogalleryview', 'id' => $model->id]);
		} else {
			return $this->render('photogallerycreate', [
				'model' => $model,
				'file' => $file,
			]);
		}
	}

	/**
	 * Updates an existing Photogallery model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPhotogalleryupdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['photogalleryview', 'id' => $model->id]);
		} else {
			return $this->render('photogalleryupdate', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing Photogallery model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPhotogallerydelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['photogalleryindex']);
	}

	/**
	 * Finds the Photogallery model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Photogallery the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Photogallery::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
=======
    	$farm = Farms::findOne($farms_id);
    	$cardidFarms = Farms::find()->where(['cardid'=>$farm['cardid']])->all();
//     	var_dump($cardidFarms);exit;
		$pn = 0;
    	foreach ($cardidFarms as $value) {
//     		var_dump($value['farmername']);exit;
    		$strc = iconv("UTF-8","gbk//TRANSLIT", $value['farmname'].'-'.$value['farmername']);
    		$stra = iconv("UTF-8","gbk//TRANSLIT", Tablefields::getCfields($selectArray[0],$selectArray[1]));
    		$management_area_id = Farms::getFarmsAreaID($value['id']);
    		$management_area = iconv("UTF-8","gbk//TRANSLIT", ManagementArea::getAreanameOne($management_area_id));
    		
    		$filename = time();
    		$path = 'photo_gallery/'.$management_area.'/'.$strc.'/'.$stra;
    		fileUtil::createDir($path);
    		$filepath = $path.'/'.$filename.'.'.$extphoto;
    		 
    		if($file->saveAs2($filepath)) {
//     				echo '111111';exit;
    			if($selectArray[0] == 'electronicarchives') {
    				//     			echo '000';
    				$ea = Electronicarchives::find()->where(['farms_id'=>$value['id']])->orderBy('pagenumber DESC')->one()['pagenumber'];
    				 
    				if($ea)
    					$pn = ++$ea;
    				else
    					$pn = 1;
    				//     			var_dump($pn);exit;
    				if($eid) {
    					$model = Electronicarchives::findOne($eid);
    					$model->update_at = time();
    				}
    				else {
    					$model = new Electronicarchives();
    					$model->farms_id = $value['id'];
    					$model->archivesimage = iconv("GBK", "UTF-8", $filepath);
    					$model->create_at = (string)time();
    					$model->update_at = $model->create_at;
    					$model->pagenumber = $pn;
    				}
    				$model->save();
    				//     			var_dump($model);
    			} else {
//     				echo '2222';exit;
    				$class = 'app\\models\\'.ucfirst($selectArray[0]);
    				$temp = $class::find()->where(['farms_id'=>$value['id']])->one()['id'];
    				$model = $class::findOne($temp);
//     				var_dump($model);exit;
    				if($od=opendir($this->getPath($path))) //$d是目录名
    				{
    					if(file_exists(iconv("UTF-8","gbk//TRANSLIT", $model->$selectArray[1]))){
    						unlink(iconv("UTF-8","gbk//TRANSLIT", $model->$selectArray[1]));
    					}
    				}
    				$model->$selectArray[1] = iconv("GBK", "UTF-8", $filepath);
    				$model->save();
//     				var_dump($model);exit;
    			}
    		}
    	}   	
//     	var_dump($imageInfo);var_dump($pn);exit;
    	echo json_encode(['url' => iconv("GBK", "UTF-8", $filepath),'info'=>$imageInfo,'page'=>$pn,'id'=>$model->id]);
    }
    
    public function actionPhotographdialog($farms_id,$field)
    {
   	 	
    	$imageInfo = $this->getImageInfo('D:\\wamp\\www\landsystem\\frontend\\web\\uploadimage\\imgTemp\\JPG.jpg');
    	$file = UploadedFile::loadFiles($imageInfo);
    	$extphoto = strtolower(pathinfo('D:\\wamp\\www\landsystem\\frontend\\web\\uploadimage\\imgTemp\\JPG.jpg', PATHINFO_EXTENSION));

    	$farm = Farms::findOne($farms_id);
    	$cardidFarms = Farms::find()->where(['cardid'=>$farm['cardid']])->all();
    	foreach ($cardidFarms as $value) {
    		$strc = iconv("UTF-8","gbk//TRANSLIT", $value['farmname'].'-'.$value['farmername']);
    		$stra = iconv("UTF-8","gbk//TRANSLIT", Tablefields::getCfields('farmer',$field));
//     		var_dump($stra);exit;
    		$management_area_id = Farms::getFarmsAreaID($value['id']);
    		$management_area = iconv("UTF-8","gbk//TRANSLIT", ManagementArea::getAreanameOne($management_area_id));
    
    		$filename = time();
    		$path = 'photo_gallery/'.$management_area.'/'.$strc.'/'.$stra;
    		fileUtil::createDir($path);
    		$filepath = $path.'/'.$filename.'.'.$extphoto;
    		 
    		if($file->saveAs2($filepath)) {
    			$farmer_id = Farmer::find()->where(['farms_id'=>$value['id']])->one()['id'];
    				$model = Farmer::findOne($farmer_id);
    				if($od=opendir($this->getPath($path))) //$d是目录名
    				{
//     					var_dump($model);
//     					var_dump(file_exists(iconv("UTF-8","gbk//TRANSLIT", $model->$field)));exit;
    					if(file_exists(iconv("UTF-8","gbk//TRANSLIT", $model->$field))){
    						unlink(iconv("UTF-8","gbk//TRANSLIT", $model->$field));
    					}
    				}
    				$model->$field = iconv("GBK", "UTF-8", $filepath);
    				$model->save();
//     				var_dump($model->getErrors());exit;
    		}
    	}
    	echo json_encode(['url' => iconv("GBK", "UTF-8", $filepath),'id'=>$model->id]);
    }
    
    /**
     * Creates a new Photogallery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPhotogallerycreate()
    {   	
        $model = new Photogallery();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['photogalleryview', 'id' => $model->id]);
        } else {
            return $this->render('photogallerycreate', [
                'model' => $model,
            	'file' => $file,
            ]);
        }
    }

    /**
     * Updates an existing Photogallery model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPhotogalleryupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['photogalleryview', 'id' => $model->id]);
        } else {
            return $this->render('photogalleryupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Photogallery model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPhotogallerydelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['photogalleryindex']);
    }

    /**
     * Finds the Photogallery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Photogallery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Photogallery::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
