<?php

namespace frontend\controllers;

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
    	$strc = iconv("UTF-8","gbk//TRANSLIT", Tables::getCtablename($_GET['controller']));
    	$stra = iconv("UTF-8","gbk//TRANSLIT", Tablefields::getCfields($_GET['controller'],$_GET['field']));
    	$management_area = iconv("UTF-8","gbk//TRANSLIT", ManagementArea::getAreanameOne(Farms::getFarmsAreaID($_GET['farms_id'])));

    	$filename = time();
    	$path = 'photo_gallery/'.$management_area.'/'.$strc.'/'.$stra;
    	fileUtil::createDir($path);
    	$filepath = $path.'/'.$filename.'.'.$extphoto;
    	$file->saveAs($filepath);
    	echo json_encode(['url' => iconv("GBK", "UTF-8", $filepath)]);
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
//     	var_dump($extphoto);exit;
    	$selectArray = explode('-', $select);
//     	var_dump($selectArray);exit;
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
