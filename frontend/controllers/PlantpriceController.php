<?php

namespace frontend\controllers;

use app\models\Plant;
use Composer\IO\NullIO;
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
    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        } else {
            return true;
        }
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
        $model = $this->findModel($id);
    	Logs::writeLog('查看缴费基数',$model);
        return $this->render('plantpriceview', [
            'model' => $model,
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
        	$farms = Farms::find()->where(['state'=>[1,2,3]])->all();
        	foreach ($farms as $farm) {
        		$collection = Collection::find()->where(['farms_id'=>$farm['id'],'payyear'=>$model->years])->one();
        		$oldCollection = Collection::find()->where(['farms_id'=>$farm['id'],'payyear'=>$model->years-1])->one();
        		if($collection) {
        			$collectionModel = Collection::findOne($collection['id']);

        			$collectionModel->update_at = time();
//                    $collectionModel->payyear = $model->years;
//                    $collectionModel->farms_id = $farm['id'];
                    $collectionModel->amounts_receivable = $collectionModel->getAR($model->years,$farm['id']);
//                    $collectionModel->dckpay = 0;
//                    $collectionModel->state = 0;
                    $collectionModel->management_area = $farm['management_area'];
                    if($oldCollection) {
                        $collectionModel->owe = $oldCollection->owe;
                    }
                    if($collectionModel->state > 0) {
                        $collectionModel->owe += bcsub($collectionModel->getAR($model->years,$farm['id']), $collectionModel->real_income_amount,2);
                        $collectionModel->ypayarea = bcdiv(bcsub($collectionModel->getAR($model->years,$farm['id']), $collectionModel->real_income_amount,2),$model->price,2);
                        $collectionModel->ypaymoney = bcsub($collectionModel->getAR($model->years,$farm['id']), $collectionModel->real_income_amount,2);
                    } else {
                        $collectionModel->ypaymoney = $collectionModel->amounts_receivable;
                    }
                    $collectionModel->farmstate = $farm['state'];
                    $collectionModel->contractarea = $farm['contractarea'];
                    $collectionModel->save();
//                    var_dump($collectionModel->getErrors());exit;
        		} else {
        			$collectionModel = new Collection();
        			$collectionModel->create_at = time();
        			$collectionModel->update_at = $collectionModel->create_at;
                    $collectionModel->payyear = $model->years;
                    $collectionModel->farms_id = $farm['id'];
                    $collectionModel->amounts_receivable = $collectionModel->getAR($model->years,$farm['id']);
                    $collectionModel->ypayarea = $farm['contractarea'];
                    $collectionModel->ypaymoney = $collectionModel->amounts_receivable;
                    $collectionModel->owe = 0.0;
                    $collectionModel->dckpay = 0;
                    $collectionModel->state = 0;
                    $collectionModel->farmstate = $farm['state'];
                    $collectionModel->management_area = $farm['management_area'];
                    $collectionModel->contractarea = $farm['contractarea'];
                    if($oldCollection) {
                        $collectionModel->owe = $oldCollection->owe;
                    }
                    $collectionModel->save();
        		}

           	}
        	Logs::writeLogs('添加缴费基数',$model);
            return $this->redirect(['collection/collectioninfo']);
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

    public function actionPlantpriceaddcollection($id)
    {
        $model = $this->findModel($id);
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $farms = Farms::find()->where(['state'=>[1,2,3]])->all();
            foreach ($farms as $farm) {
                $collection = Collection::find()->where(['farms_id'=>$farm['id'],'payyear'=>$model->years,])->one();
                if(empty($collection)) {
                    $collectionModel = new Collection();
                    $collectionModel->create_at = time();
                    $collectionModel->update_at = $collectionModel->create_at;
                    $collectionModel->payyear = $model->years;
                    $collectionModel->farms_id = $farm['id'];
                    $collectionModel->amounts_receivable = $collectionModel->getAR($model->years,$collectionModel->farms_id);
                    $collectionModel->ypayarea = $farm['contractarea'];
                    $collectionModel->ypaymoney = $collectionModel->amounts_receivable;
                    $collectionModel->owe = 0.0;
                    $collectionModel->dckpay = 0;
                    $collectionModel->state = 0;
                    $collectionModel->management_area = $farm['management_area'];
//                    if($oldCollection) {
//                        $collectionModel->owe = $oldCollection->owe;
//                    }
                    $collectionModel->save();
                    Logs::writeLogs('追加缴费任务列表',$collectionModel);
                }
            }
//            $new = $model->attributes;
//            Logs::writeLog('追加缴费任务列表',$id,$old,$new);
            return $this->redirect(['collection/collectioninfo']);
//             return $this->redirect(['plantpriceview', 'id' => $model->id]);
//            return $this->render('plantpriceindex', [
//                'model' => $model,
//            ]);
    }

    public function actionPlantpriceupdate($id)
    {
        $model = $this->findModel($id);
		$old = $model->attributes;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $farms = Farms::find()->where(['state'=>[1,2,3]])->all();
            foreach ($farms as $farm) {
                $collection = Collection::find()->where(['farms_id'=>$farm['id'],'payyear'=>$model->years,'state'=>0])->one();
                $oldCollection = Collection::find()->where(['farms_id'=>$farm['id'],'payyear'=>$model->years-1])->one();
                if($collection) {
                    $collectionModel = Collection::findOne($collection['id']);

//                    $collectionModel->update_at = time();
//                    $collectionModel->payyear = $model->years;
//                    $collectionModel->farms_id = $farm['id'];
                    $collectionModel->amounts_receivable = $collectionModel->getAR($model->years,$farm['id']);
//                    $collectionModel->dckpay = 0;
//                    $collectionModel->state = 0;
                    $collectionModel->management_area = $farm['management_area'];
                    if($model->years !== date('Y')) {
                        $collectionModel->owe = $collectionModel->ypaymoney;
                        $collectionModel->ypayyear = $model->years;
                    }
//                    if($collectionModel->state > 0) {
//                        $collectionModel->owe += bcsub($collectionModel->getAR($model->years,$farm['id']), $collectionModel->real_income_amount,2);
//                        var_dump($collectionModel->owe);exit;
                        $collectionModel->ypayarea = bcdiv(bcsub($collectionModel->getAR($model->years,$farm['id']), $collectionModel->real_income_amount,2),$model->price,2);
                        $collectionModel->ypaymoney = bcsub($collectionModel->getAR($model->years,$farm['id']), $collectionModel->real_income_amount,2);
                        $collectionModel->measure = bcdiv($collectionModel->real_income_amount,$model->price,2);
//                    } else {
//                        $collectionModel->ypaymoney = $collectionModel->amounts_receivable;
//                    }
                    $collectionModel->farmstate = $farm['state'];
                    $collectionModel->contractarea = $farm['contractarea'];
                    $collectionModel->save();
                } else {
                    $collectionModel = new Collection();
                    $collectionModel->create_at = time();
                    $collectionModel->update_at = $collectionModel->create_at;
                    $collectionModel->payyear = $model->years;
                    $collectionModel->farms_id = $farm['id'];
                    $collectionModel->amounts_receivable = $collectionModel->getAR($model->years,$collectionModel->farms_id);
                    $collectionModel->ypayarea = $farm['contractarea'];
                    $collectionModel->ypaymoney = $collectionModel->amounts_receivable;
                    $collectionModel->owe = 0.0;
                    $collectionModel->dckpay = 0;
                    $collectionModel->state = 0;
                    $collectionModel->farmstate = $farm['state'];
                    $collectionModel->management_area = $farm['management_area'];
                    $collectionModel->contractarea = $farm['contractarea'];
//                    if($oldCollection) {
//                        $collectionModel->owe = $oldCollection->owe;
//                    }
                    $collectionModel->save();
                }
            }
        	Logs::writeLogs('更新缴费基数',$model);
            return $this->redirect(['collection/collectioninfo']);
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
        $model->delete();
        Logs::writeLogs('删除缴费基数',$model);
        return $this->redirect(['plantpriceindex']);
    }

    public function actionGetprice($year)
    {
        $price = PlantPrice::find()->where(['years'=>$year])->one()['price'];
        echo json_encode($price);
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
