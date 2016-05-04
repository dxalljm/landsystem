<?php

namespace frontend\controllers;

use Yii;
use app\models\PlantPrice;
use frontend\models\plantpriceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Logs;
use app\models\Collection;
use app\models\Farms;
use app\models\Theyear;

/**
 * PlantpriceController implements the CRUD actions for PlantPrice model.
 */
class PlantpriceController extends Controller
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

//     public function beforeAction($action)
//     {
//     	$action = Yii::$app->controller->action->id;
//     	if(\Yii::$app->user->can($action)){
//     		return true;
//     	}else{
//     		throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
//     	}
//     }
    /**
     * Lists all PlantPrice models.
     * @return mixed
     */
    public function actionPlantpriceindex()
    {
        $searchModel = new plantpriceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		Logs::writeLog('缴费基数');
        return $this->render('plantpriceindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Plantprice model.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantpriceview($id)
    {
    	Logs::writeLog('查看缴费基数',$id);
        return $this->render('plantpriceview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PlantPrice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPlantpricecreate()
    {
        $model = new PlantPrice();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$farms = Farms::find()->where(['state'=>0])->all();
        	foreach ($farms as $farm) {
        		$collection = Collection::find()->where(['farms_id'=>$farm['id'],'payyear'=>$model->years])->one();
        		$oldCollection = Collection::find()->where(['farms_id'=>$farm['id'],'payyear'=>$model->years-1])->one();
        		if($collection) {
        			$collectionModel = Collection::findOne($collection['id']);
        			$collectionModel->update_at = time();
        		} else {
        			$collectionModel = new Collection();
        			$collectionModel->create_at = time();
        			$collectionModel->update_at = $collectionModel->create_at;
        		}
        		$collectionModel->payyear = $model->years;
        		$collectionModel->farms_id = $farm['id'];
        		$collectionModel->amounts_receivable = $collectionModel->getAR($model->years);
        		$collectionModel->ypayarea = $farm['contractarea'];
        		$collectionModel->ypaymoney = $collectionModel->amounts_receivable;
        		$collectionModel->dckpay = 0;
        		$collectionModel->state = 0;
        		$collectionModel->management_area = $farm['management_area'];
        		if($oldCollection) {
        			$collectionModel->owe = $oldCollection->owe;
        		}
        		$collectionModel->save();
           	}
        	$new = $model->attributes;
        	Logs::writeLog('添加缴费基数',$model->id,'',$new);
            return $this->redirect(['plantpriceview', 'id' => $model->id]);
        } else {
            return $this->render('plantpricecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Plantprice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantpriceupdate($id)
    {
        $model = $this->findModel($id);
		$old = $model->attributes;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	
        	$new = $model->attributes;
        	Logs::writeLog('更新缴费基数',$id,$old,$new);
//             return $this->redirect(['plantpriceview', 'id' => $model->id]);
        } else {
            return $this->render('plantpriceupdate', [
                'model' => $model,
            ]);
        }
    }

    public function actionPlantpricemodel($id)
    {
    	$model = $this->findModel($id);
    	return $this->renderAjax('plantpricemodel',['model'=>$model]);
    }
    
    /**
     * Deletes an existing Plantprice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantpricedelete($id)
    {
        $model = $this->findModel($id);
    	$old = $model->attributes;
    	Logs::writeLog('删除缴费基数',$id,$old);
        $model->delete();

        return $this->redirect(['plantpriceindex']);
    }

    /**
     * Finds the PlantPrice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlantPrice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PlantPrice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
