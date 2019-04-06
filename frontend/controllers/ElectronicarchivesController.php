<?php

namespace frontend\controllers;

use Yii;
use app\models\Electronicarchives;
use frontend\models\electronicarchivesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ElectronicarchivesController implements the CRUD actions for Electronicarchives model.
 */
class ElectronicarchivesController extends Controller
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
     * Lists all Electronicarchives models.
     * @return mixed
     */
    public function actionElectronicarchivesindex()
    {
        $searchModel = new electronicarchivesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('electronicarchivesindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Electronicarchives model.
     * @param integer $id
     * @return mixed
     */
    public function actionElectronicarchivesview($id)
    {
        return $this->render('electronicarchivesview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Electronicarchives model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionElectronicarchivescreate()
    {
        $model = new Electronicarchives();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['electronicarchivesview', 'id' => $model->id]);
        } else {
            return $this->render('electronicarchivescreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Electronicarchives model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionElectronicarchivesupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['electronicarchivesview', 'id' => $model->id]);
        } else {
            return $this->render('electronicarchivesupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Electronicarchives model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionElectronicarchivesdelete($id)
    {
    	$model = $this->findModel($id);
    	$farms_id = $model->farms_id;
    	if(file_exists(iconv("UTF-8","gbk//TRANSLIT", $model->archivesimage))){
    		unlink(iconv("UTF-8","gbk//TRANSLIT", $model->archivesimage));
    	}
    	$model->delete();
    	echo json_encode(['id'=>$model->id,'select'=>'electronicarchives-archivesimage']);
//         return $this->redirect(['photograph/photographindex','farms_id'=>$farms_id,'select'=>'electronicarchives-archivesimage']);
    }

    /**
     * Finds the Electronicarchives model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Electronicarchives the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Electronicarchives::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
