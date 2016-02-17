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
	
    public function actionFileupload($controller,$action,$farms_id)
    {
    	$file = UploadedFile::getInstanceByName('upload_file');
//     	var_dump($file);exit;
    	$extphoto = $file->getExtension();
    	$filename = time().$extphoto;
    	$path = $controller.$action;
    	fileUtil::createDir($path);
    	$filepath = $path.$filename;
    	$file->saveAs($filepath);
    	echo json_encode(['url' => $filepath]);
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
