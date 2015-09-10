<?php

namespace frontend\controllers;

use Yii;
use app\models\Goodseed;
use frontend\models\goodseedSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Plant;
use app\models\Logs;
/**
 * GoodseedController implements the CRUD actions for Goodseed model.
 */
class GoodseedController extends Controller
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
     * Lists all Goodseed models.
     * @return mixed
     */
    public function actionGoodseedindex()
    {
        $searchModel = new goodseedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		Logs::writeLog('良种信息');
        return $this->render('goodseedindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Goodseed model.
     * @param integer $id
     * @return mixed
     */
    public function actionGoodseedview($id)
    {
    	Logs::writeLog('查看良种信息',$id);
        return $this->render('goodseedview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Goodseed model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionGoodseedcreate()
    {
        $model = new Goodseed();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$new = $model->attributes;
        	Logs::writeLog('创建良种信息',$model->id,'',$new);
            return $this->redirect(['goodseedview', 'id' => $model->id]);
        } else {
            return $this->render('goodseedcreate', [
                'model' => $model,
            ]);
        }
    }
    
    public function  actionGoodseedgetmodel($plant_id)
    {
    	$goodseed = Goodseed::find()->where(['plant_id'=>$plant_id])->all();
    	$newData = NULL;
    	foreach($goodseed as $key=>$val){
    		$newData[$key] = $val->attributes;
    	}
    	echo json_encode(['status'=>1,'goodseed'=>$newData]);
    }

    /**
     * Updates an existing Goodseed model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionGoodseedupdate($id)
    {
        $model = $this->findModel($id);
		$old = $model->attributes;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$new = $model->attributes;
        	Logs::writeLog('更新良种信息',$id,$old,$new);
            return $this->redirect(['goodseedview', 'id' => $model->id]);
        } else {
            return $this->render('goodseedupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Goodseed model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionGoodseeddelete($id)
    {
    	$model = $this->findModel($id);
    	$old = $model->attributes;
    	Logs::writeLog('删除良咱信息',$id,$old);
        $model->delete();

        return $this->redirect(['goodseedindex']);
    }

    
    /**
     * Finds the Goodseed model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Goodseed the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Goodseed::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
