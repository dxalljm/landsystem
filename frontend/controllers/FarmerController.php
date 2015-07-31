<?php

namespace frontend\controllers;

use app\models\Farmermembers;
use Yii;
use app\models\Farmer;
use frontend\models\farmerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use yii\web\UploadedFile;
use app\models\Lease;
use app\models\Theyear;
/**
 * FarmerController implements the CRUD actions for farmer model.
 */
class FarmerController extends Controller
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
     * Lists all farmer models.
     * @return mixed
     */
    public function actionFarmerindex()
    {
        $searchModel = new farmerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('farmerindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionFarmercontract($id)
    {
    	//$this->layout='@app/views/layouts/nomain.php';
    	$farm = Farms::find()->where(['id'=>$id])->one(); 
    	$lease = Lease::find()->where(['farms_id'=>$id])->all();
    	//$farmer = Farmer::find()->where(['farms_id'=>$id])->one();
    	$farmerid = farmer::find()->where(['farms_id'=>$id])->one()['id'];
    		 $model = $this->findModel($farmerid);

            return $this->renderAjax('farmercontract', [
                'model' => $model,
            	'farm' => $farm,
            	'lease' => $lease,
            ]);
        
    }
    
    /**
     * Displays a single Farmer model.
     * @param integer $id
     * @return mixed
     */
    public function actionFarmercreate($id)
    {
    	//$this->layout='@app/views/layouts/nomain.php';
    	$year = Theyear::findOne(1)['years'];
    	$farm = Farms::find()->where(['id'=>$id])->one();

        $farmembers = new Farmermembers;

    	$farmerid = farmer::find()->where(['farms_id'=>$id,'years'=>$year])->one()['id'];
    	if($farmerid) {
    		 $model = $this->findModel($farmerid);

    		 if ($model->load(Yii::$app->request->post()) && $model->save()) {
    		 	return $this->redirect(['farms/farmsmenu','id'=>$id,'areaid'=>$farm->management_area]);
    		 } else {
    		 	return $this->render('farmercreate', [
		                'model' => $model,
		            	'farm' => $farm,
                        'farmembers' => $farmembers,
		            ]);
    		 }
    	} else {
    		$model = new farmer(); 	
	    	 if ($model->load(Yii::$app->request->post())) {
	    	 	if($model->photo != '') {
	    	 		$upload = new UploadedFile();
		    	 	$photo =  $upload->getInstance($model,'photo');
	 	    	 	$extphoto = $photo->getExtension();
	 	    	 	$photoName = time().rand(100,999).'.'.$extphoto;
	 	    	 	$photo->saveAs('uploads/'.$photoName);
	 	    	 	$model->photo = 'uploads/'.$photoName;
	    	 	}
	    	 	if($model->cardpic != '') {
	 	    	 	$cardpic =  UploadedFile::getInstance($model,'cardpic');
	 	    	 	$extcardpic = $cardpic->getExtension();
		    	 	$cardpicName = time().rand(100,999).'.'.$extcardpic;
		    	 	$cardpic->saveAs('uploads/'.$cardpicName);
		    	 	$model->cardpic = 'uploads/'.$cardpicName;
	    	 	}
	    	 	$model->years = $year;
	    	 	if($model->save())
	            	return $this->redirect(['farms/farmsmenu','id'=>$id,'areaid'=>$farm->management_area]);
		        } else {
		            return $this->render('farmercreate', [
		                'model' => $model,
		            	'farm' => $farm,
		            ]);
		        } 
    	}
    }

    /**
     * Creates a new farmer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//     public function actionFarmercreate()
//     {
//         $model = new farmer();

//         if ($model->load(Yii::$app->request->post()) && $model->save()) {
//             return $this->redirect(['farmerview', 'id' => $model->id]);
//         } else {
//             return $this->render('farmercreate', [
//                 'model' => $model,
//             ]);
//         }
//     }

    /**
     * Updates an existing Farmer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFarmerupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['farmerview', 'id' => $model->id]);
        } else {
            return $this->render('farmerupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Farmer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFarmerdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['farmerindex']);
    }

    /**
     * Finds the farmer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return farmer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = farmer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
