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
use app\models\UploadForm;
use app\models\ManagementArea;
use app\models\Logs;
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

    
//     public function beforeAction($action)
//     {
//     	$action = Yii::$app->controller->action->id;
//     	if(\Yii::$app->user->can($action)){
//     		return true;
//     	}else{
//     		throw new \yii\web\UnauthorizedHttpException('瀵逛笉璧凤紝鎮ㄧ幇鍦ㄨ繕娌¤幏姝ゆ搷浣滅殑鏉冮檺');
//     	}
//     }
    /**
     * Lists all farmer models.
     * @return mixed
     */
    public function actionFarmerindex()
    {
        $searchModel = new farmerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		Logs::writeLog('农场主信息');
        return $this->render('farmerindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

//     public function actionFarmercontract($id)
//     {
//     	//$this->layout='@app/views/layouts/nomain.php';
//     	$farm = Farms::find()->where(['id'=>$id])->one(); 
//     	$lease = Lease::find()->where(['farms_id'=>$id])->all();
//     	//$farmer = Farmer::find()->where(['farms_id'=>$id])->one();
//     	$farmerid = farmer::find()->where(['farms_id'=>$id])->one()['id'];
//     	$model = $this->findModel($farmerid);
// 		Logs::writeLog('农场详细信息',$id);
//             return $this->renderAjax('farmercontract', [
//                 'model' => $model,
//             	'farm' => $farm,
//             	'lease' => $lease,
//             ]);
        
//     }
    
    
    
    public function actionFarmerxls()
    {
    	set_time_limit(0);
    	$model = new UploadForm();
    	echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";
    	$rows = 0;
    	if (Yii::$app->request->isPost) {
    
    		$model->file = UploadedFile::getInstance($model, 'file');
    		if($model->file == null)
    			throw new \yii\web\UnauthorizedHttpException('对不起，请先选择xls文件');
    		if ($model->file && $model->validate()) {
    			 
    			$xlsName = time().rand(100,999);
    			$model->file->name = $xlsName;
    			$model->file->saveAs('uploads/' . $model->file->name . '.' . $model->file->extension);
    			$path = 'uploads/' . $model->file->name . '.' . $model->file->extension;
    			$loadxls = \PHPExcel_IOFactory::load($path);
    			$rows = $loadxls->getActiveSheet()->getHighestRow();
    			for($i=1;$i<=$rows;$i++) {
    				//echo $loadxls->getActiveSheet()->getCell('F'.$i)->getValue()."<br>";
    				//echo  ManagementArea::find()->where(['areaname'=>$loadxls->getActiveSheet()->getCell('B'.$i)->getValue()])->one()['id'];"<br>";
    				$farmermodel = new Farmer();

    				$farmermodel->farms_id = Farms::find()->where(['farmname'=>$loadxls->getActiveSheet()->getCell('A'.$i)->getValue()])->one()['id'];
    				$farmermodel->farmername = $loadxls->getActiveSheet()->getCell('B'.$i)->getValue();
    				$farmermodel->cardid =  $loadxls->getActiveSheet()->getCell('C'.$i)->getValue();
    				$farmermodel->telephone =  $loadxls->getActiveSheet()->getCell('D'.$i)->getValue();
  					$farmermodel->create_at = time();
  					$farmermodel->update_at = time();
    				$farmermodel->save();
    				$newAttr = $farmermodel->attributes;
    				Logs::writeLog('xls批量导入农场主信息',$farmermodel->id,'',$newAttr);
    				
    				//     				print_r($farmermodel->getErrors());
    			}
    		}
    	}
    	return $this->render('farmerxls',[
    			'model' => $model,
    			'rows' => $rows,
    	]);
    }
    
    /**
     * Displays a single Farmer model.
     * @param integer $id
     * @return mixed
     */
    public function actionFarmercreate($farms_id)
    {
    	//$this->layout='@app/views/layouts/nomain.php';
    	$year = Theyear::findOne(1)['years'];
    	$farm = Farms::find()->where(['id'=>$farms_id])->one();
		
    	$farmerid = farmer::find()->where(['farms_id'=>$farms_id])->one()['id'];
    	$membermodel = Farmermembers::find()->where(['farmer_id' => $farmerid])->all();
    	if($farmerid) {
    		 $model = $this->findModel($farmerid);
    		 $old = $model->attributes;
			 //$membermodel = Farmermembers::find()->where(['farmer_id' => $farmerid])->all();
			 if ($model->load(Yii::$app->request->post())) {

			 	$model->update_at = time();
			 	$model->save();
			 	//var_dump($_FILES);
				$new = $model->attributes;
				Logs::writeLog('更新法人信息',$model->id,$old,$new);
                 // 得到家庭成员post的数据
               	$parmembers = Yii::$app->request->post('Parmembers');
               	//var_dump($_POST);exit;
               	//删除家庭成员
               	$this->deleteMembers($membermodel, $parmembers['id']);
                 /* if (count($parmembers) > 0) {
                     $attr = Farmermembers::formatAttr($parmembers, []);
                 } */
               	//家庭成员的记录数
               	$row = count($parmembers['membername']);
               	$oldAttr = '';
               	for($i=1;$i<$row;$i++) {
	               	//判断数据是否存在，如果已经存在则得到该条数据（为更新数据），如果不存在，就新建数据
	               	if($this->findMemberModel($parmembers['id'][$i])) {
	               		$membermodel = $this->findMemberModel($parmembers['id'][$i]);
	               		$oldAttr = $membermodel->attributes;
	               	}
	               	else
	               		$membermodel = new Farmermembers();
	               	$membermodel->farmer_id = $farmerid;
	               	$membermodel->relationship = $parmembers['relationship'][$i];
	               	$membermodel->membername = $parmembers['membername'][$i];
	               	$membermodel->cardid = $parmembers['cardid'][$i];
	               	$membermodel->remarks = $parmembers['remarks'][$i];
	               	$membermodel->save();
	               	$newAttr = $membermodel->attributes;
	               	Logs::writeLog('创建家庭成员',$membermodel->id,$oldAttr,$newAttr);
               	}
               	
               	return $this->redirect(['farms/farmsmenu','farms_id'=>$farms_id,'areaid'=>$farm->management_area]);
    		 } else {
    		 	return $this->render('farmercreate', [
		                'model' => $model,
    		 			'membermodel' => $membermodel,
		            	'farm' => $farm,
		            ]);
    		 }
    	} else {
    		$model = new farmer(); 
    		
    		$membermodel = Farmermembers::find()->where(['farmer_id' => $farmerid])->all();
    		if ($model->load(Yii::$app->request->post())) {
	    	 	
	    	 	$model->create_at = time();
	    	 	$model->update_at = $model->create_at;
	    	 	$model->years = $year;
	    	 	$issave = $model->save();
	    	 	
	    	 	$newAttr = $model->attributes;
	    	 	Logs::writeLog('添加法人信息',$model->id,'',$newAttr);
	    	 	$parmembers = Yii::$app->request->post('Parmembers');
	    	 	//print_r($parmembers);
	    	 	$row = count($parmembers['membername']);
	    	 	for($i=1;$i<$row;$i++) {
	    	 		$membermodel = new Farmermembers();
	    	 		$membermodel->farmer_id = $model->id;
	    	 		$membermodel->relationship = $parmembers['relationship'][$i];
	    	 		$membermodel->membername = $parmembers['membername'][$i];
	    	 		$membermodel->cardid = $parmembers['cardid'][$i];
	    	 		$membermodel->remarks = $parmembers['remarks'][$i];
	    	 		$membermodel->save();
	    	 		$new = $membermodel->attributes;
	    	 		Logs::writeLog('添加家庭成员',$membermodel->id,'',$new);
	    	 	}
	    	 	if($issave)
	            	return $this->redirect(['farms/farmsmenu','farms_id'=>$farms_id,'areaid'=>$farm->management_area]);
		        } else {
		            return $this->render('farmercreate', [
		                'model' => $model,
		            	'membermodel' => $membermodel,
		            	'farm' => $farm,
		            ]);
		        } 
    	}
    }

    //删除post提交的家庭成员
    private function deleteMembers($nowdatabase,$postdataidarr) {
    	$databaseid = array();
    	foreach($nowdatabase as $value) {
    		$databaseid[] = $value['id'];
    	}
    	$result = array_diff($databaseid,$postdataidarr);
    	if($result) {
	    	foreach($result as $val) {
	    		$model = Farmermembers::findOne($val);
	    		$oldAttr = $model->attributes;
	    		Logs::writeLog('删除家庭成员',$val,$oldAttr);
	    		$model->delete();
	    	}
	    	return true;
    	} else
    		return false;
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
//     public function actionFarmerupdate($farms_id)
//     {
//     	$farmModel = Farms::findOne($farms_id);
    	
//         $model = Farmer::find()->where(['farms_id'=>$farms_id])->one();

//         if ($model->load(Yii::$app->request->post()) && $model->save()) {
//             return $this->redirect(['farmerview', 'id' => $model->id]);
//         } else {
//             return $this->render('farmerupdate', [
//                 'model' => $model,
//             ]);
//         }
//     }
    
//     public function addtime()
//     {
//     	set_time_limit(0);
//     	$farmers = Farmer::find()->all();
    
//     	foreach ($farmers  as $value) {
//     		foreach ($value as $key=>$val) {
//     			$model = $this->findModel($val['id']);
//     			$model->create_at = time();
//     			$model->update_at = time();
//     			$model->save();
//     		}
//     	}
//     	//return $this->render('farmerupdate');
//     }

    /**
     * Deletes an existing Farmer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
//     public function actionFarmerdelete($id)
//     {
//         $this->findModel($id)->delete();

//         return $this->redirect(['farmerindex']);
//     }

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
    
    protected function findMemberModel($id)
    {
    	if (($model = Farmermembers::findOne($id)) !== null) {
    		//print_r($model);
    		return $model;
    	} else {
    		return false;
    	}
    }
}
