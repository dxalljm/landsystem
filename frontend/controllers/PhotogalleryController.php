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
