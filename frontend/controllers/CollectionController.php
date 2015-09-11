<?php

namespace frontend\controllers;

use Yii;
use app\models\Collection;
use frontend\models\collectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use frontend\models\farmsSearch;
use app\models\Farmer;
use frontend\models\farmerSearch;
use app\models\Theyear;
use app\models\PlantPrice;
use app\models\Logs;
/**
 * CollectionController implements the CRUD actions for Collection model.
 */
class CollectionController extends Controller
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
    	$action = Yii::$app->controller->action->id;
    	if(\Yii::$app->user->can($action)){
    		return true;
    	}else{
    		throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
    	}
    }
    /**
     * Lists all Collection models.
     * @return mixed
     */
    public function actionCollectionindex($year=null)
    {
    	if($year === null)
    		$year = Theyear::findOne(1)['years'];
        $searchModel = new farmerSearch();
        $dataProvider = $searchModel->search(['years'=>$year]);
		Logs::writeLog('进入承包费收缴页面');
        return $this->render('collectionindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'years' => $year,
        ]);
    }
	
    public function actionGetamounts()
    {
    	$real = 0;
    	$amounts = 0;
    	$collections = Collection::find()->where(['farms_id'=>$arrayID])->all();
    	foreach ($collections as $value) {
    		$real += $value['real_income_amount'];
    		$amounts += $value['amounts_receivable'];
    	}
    	return ['real'=>$real,'amounts'=>$amounts];
    }
    
    /**
     * Displays a single Collection model.
     * @param integer $id
     * @return mixed
     */
    public function actionCollectionview($id)
    {
    	Logs::writeLog('查看一笔收缴信息',$id);
        return $this->render('collectionview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Collection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCollectioncreate($farmsid,$cardid)
    {
    	//$this->layout='@app/views/layouts/nomain.php';
    	$year = Theyear::findOne(1)['years'];
    	$collection = Collection::find()->where(['farms_id'=>$farmsid,'cardid'=>$cardid,'ypayyear'=>$year])->one();
    	if($collection) {
    		$model = $this->findModel($collection->id);
    		$old_real_income_amount = $collection->real_income_amount;
    		$old_ypaymoney = $collection->ypaymoney;
    	}
    	else {
    		$model = new Collection();
    		$old_real_income_amount = 0;
    	}
    	
        
        $collectiondataProvider = Collection::find()->where(['farms_id'=>$farmsid,'cardid'=>$cardid])->andWhere('ypayyear<'.$year)->all();
        $owe = $model->getOwe($cardid, $farmsid,$year);
//         if($owe)
//         	$collectiondataProvider['owe'] = $owe+$collectiondataProvider['amounts_receivable']-$collectiondataProvider['real_income_amount'];
//         else
//         	$collectiondataProvider['owe'] = $collectiondataProvider['ypaymoney'];
        //$collectiondataProvider = Collection::findAll("");
        //print_r($collectiondataProvider);
        if ($model->load(Yii::$app->request->post())) {
        	$model->amounts_receivable = $model->getAR($year);
        	$model->real_income_amount = $model->real_income_amount + $old_real_income_amount;
        	$model->ypayarea = $model->getYpayarea($year, $model->real_income_amount);
        	$model->ypaymoney = $model->getYpaymoney($year, $model->real_income_amount);
        	//$owe = $model->getOwe($farmerid, $farmsid,$year);
        	if($owe)
        		$model->owe = $owe+$model->amounts_receivable-$model->real_income_amount;
        	else
        		$model->owe = $model->ypaymoney;
			//print_r($model);
        	if($model->save()) {
        		$newAttr = $model->attributes;
        		Logs::writeLog('收缴一笔承包费',$model->id,'',$newAttr);
        		return $this->redirect(['collectionview', 'id' => $model->id]);
        	}
        } else {
            return $this->renderAjax('collectioncreate', [
                'model' => $model,
            	'year' => $year,
            	'cardid' => $cardid,
            	'farmsid' => $farmsid,
            	'collectiondataProvider' => $collectiondataProvider,
            	'owe' => $owe,
            ]);
        }
    }
	
   
    
    /**
     * Updates an existing Collection model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCollectionupdate($id)
    {
        $model = $this->findModel($id);
        $oldAttr = $model->attributes;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$newAttr = $model->attributes;
        	Logs::writeLog('更新农场信息',$id,$oldAttr,$newAttr);
            return $this->redirect(['collectionview', 'id' => $model->id]);
        } else {
            return $this->render('collectionupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Collection model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCollectiondelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['collectionindex']);
    }

    /**
     * Finds the Collection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Collection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Collection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
