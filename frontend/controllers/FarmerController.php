<?php

namespace frontend\controllers;

use app\models\Farmermembers;
use Composer\Package\Loader\ValidatingArrayLoader;
use Yii;
use app\models\Farmer;
use frontend\models\farmerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use yii\web\UploadedFile;
use app\models\Farmerinfo;
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
    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        } else {
            return true;
        }
    }
    public function actionFarmerinfo()
    {
        set_time_limit ( 0 );
        $farmers = Farmer::find()->all();
        foreach ($farmers as $farmer) {
            if($farmer['cardpic'] !== '' and $farmer['photo'] !== '') {
                $farm = Farms::find()->where(['id' => $farmer['farms_id']])->one();
                if($farm['cardid']) {
                    $farmerinfo = Farmerinfo::find()->where(['cardid' => $farm['cardid']])->count();
                    if (!$farmerinfo) {
                        $model = new Farmerinfo();
                        $model->farmerbeforename = $farmer['farmerbeforename'];
                        $model->gender = $farmer['gender'];
                        $model->nation = $farmer['nation'];
                        $model->political_outlook = $farmer['political_outlook'];
                        $model->cultural_degree = $farmer['cultural_degree'];
                        $model->domicile = $farmer['domicile'];
                        $model->nowlive = $farmer['nowlive'];
                        $model->living_room = $farmer['living_room'];
                        $model->photo = $farmer['photo'];
                        $model->cardpic = $farmer['cardpic'];
                        $model->create_at = $farmer['create_at'];
                        $model->update_at = $farmer['update_at'];
                        $model->state = $farmer['state'];
                        $model->cardpicback = $farmer['cardpicback'];
                        $model->cardid = $farm['cardid'];
                        $model->save();
                    }
                }
            }
        }
        echo 'finished';
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
    public function actionGetinfo($farms_id)
    {
    	$farmer = Farmer::find()->where(['farms_id'=>$farms_id])->one();
    	echo json_encode(['info' => $farmer]);
    }
    
    
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
	private function getSameUser($farmModel)
	{
		$allid = [];
		$farms = Farms::find()->where(['cardid'=>$farmModel->cardid,'farmername'=>$farmModel->farmername])->all();
		foreach ($farms as $farm) {
			$allid[] = Farmer::find()->where(['farms_id'=>$farm['id']])->one()['id'];
		}
		return $allid;
	}

    public function actionFarmercreate($farms_id)
    {
    	//$this->layout='@app/views/layouts/nomain.php';
    	$farmModel = Farms::findOne($farms_id);

        $farmerid = Farmer::find()->where(['farms_id'=>$farms_id])->one()['id'];
        if($farmerid) {
            $member = Farmermembers::find()->where(['farmer_id' => $farmerid])->all();
            $model = $this->findModel($farmerid);
            $model->update_at = time();
            
        } else {
            $member = '';
            $model = new Farmer();
            $model->create_at = time();
            $model->update_at = $model->create_at;
//             $farmerinfoModel = new Farmerinfo();
        }
        $farmerinfoModel = Farmerinfo::findOne($farmModel->cardid);

        if(empty($farmerinfoModel)) {
            $farmerinfoModel = new Farmerinfo();
        }
//        var_dump($farmerinfoModel);
        $post = Yii::$app->request->post();
        if ($post) {
//            var_dump($post);exit;
            if(Yii::$app->request->post('farms-telephone')) {
                $farmModel->telephone = Yii::$app->request->post('farms-telephone');
            }
            if(Yii::$app->request->post('farms-cardid')) {
                $farmModel->cardid = Yii::$app->request->post('farms-cardid');
            }
            if(Yii::$app->request->post('farms-address')) {
                $farmModel->address = Yii::$app->request->post('farms-address');
            }
            if(Yii::$app->request->post('farms-longitude')) {
                $farmModel->longitude = Yii::$app->request->post('farms-longitude');
            }
            if(Yii::$app->request->post('farms-latitude')) {
                $farmModel->latitude = Yii::$app->request->post('farms-latitude');
            }
            $farmModel->save();
            Logs::writeLogs('更新农场表中法人信息',$farmModel);
            $model->farms_id = $farms_id;
            $model->state = 1;
            $model->save();
//            var_dump($farmModel);
            Logs::writeLogs('更新法人表状态',$model);
            $farmerinfoModel = Farmerinfo::findOne($farmModel->cardid);
            if(empty($farmerinfoModel)) {
                $farmerinfoModel = new Farmerinfo();
                $farmerinfoModel->cardid = (string)$farmModel->cardid;
            }
            $farmerinfoModel->farmerbeforename = $post['Farmerinfo']['farmerbeforename'];
            $farmerinfoModel->gender = $post['Farmerinfo']['gender'];
            $farmerinfoModel->nation = $post['Farmerinfo']['nation'];
            $farmerinfoModel->political_outlook = $post['Farmerinfo']['political_outlook'];
            if(isset($post['Farmerinfo']['zhibu'])) {
                $farmerinfoModel->zhibu = $post['Farmerinfo']['zhibu'];
            }
            $farmerinfoModel->cultural_degree = $post['Farmerinfo']['cultural_degree'];
            $farmerinfoModel->domicile = $post['Farmerinfo']['domicile'];
            $farmerinfoModel->nowlive = $post['Farmerinfo']['nowlive'];

            $farmerinfoModel->photo = $post['Farmerinfo']['photo'];
            $farmerinfoModel->cardpic = $post['Farmerinfo']['cardpic'];
            $farmerinfoModel->cardpicback = $post['Farmerinfo']['cardpicback'];
            $farmerinfoModel->update_at = time();
            $farmerinfoModel->save();
//            $allfarmerid = $this->getSameUser($farmModel);
				 Logs::writeLogs('更新法人信息', $farmerinfoModel);
				 // 得到家庭成员post的数据
				 $parmembers = Yii::$app->request->post('Parmembers');
//                 var_dump($parmembers);
				 //删除不同的家庭成员
				 $this->deleteMembers($member, $parmembers['id']);
				 /* if (count($parmembers) > 0) {
                     $attr = Farmermembers::formatAttr($parmembers, []);
                 } */
				 //家庭成员的记录数
				 $row = count($parmembers['membername']);
				 $oldAttr = '';
				 for ($i = 1; $i < $row; $i++) {
					 //判断数据是否存在，如果已经存在则得到该条数据（为更新数据），如果不存在，就新建数据
//					 if ($this->findMemberModel($parmembers['id'][$i])) {
                     if(!empty($member)) {
                         if($parmembers['id'][$i]) {
                            $newmembermodel = $this->findMemberModel($parmembers['id'][$i]);
                            $oldAttr = $newmembermodel->attributes;
                         }
                         else
                             $newmembermodel = new Farmermembers();
					 } else {
                         $newmembermodel = new Farmermembers();
                     }
                     $newmembermodel->farmer_id = $farmerid;
                     $newmembermodel->farmercardid = $farmerinfoModel->cardid;
                     $newmembermodel->relationship = $parmembers['relationship'][$i];
                     $newmembermodel->membername = $parmembers['membername'][$i];
                     $newmembermodel->cardid = $parmembers['cardid'][$i];
                     $newmembermodel->remarks = $parmembers['remarks'][$i];
                     $newmembermodel->save();
					 $newAttr = $newmembermodel->attributes;
					 Logs::writeLogs('创建家庭成员', $newmembermodel);
				 }

            return $this->redirect(['farms/farmsmenu', 'farms_id' => $farms_id, 'areaid' => $farmModel->management_area]);

        } else {
            return $this->render('farmercreate', [
                'model' => $model,
                'farmerinfoModel' => $farmerinfoModel,
                'membermodel' => $member,
                'farmModel' => $farmModel,
            ]);
        }
    	
    }

    //删除post提交的家庭成员
    private function deleteMembers($nowdatabase,$postdataidarr) {
    	$databaseid = array();
        if($nowdatabase) {
            foreach ($nowdatabase as $value) {
                $databaseid[] = $value['id'];
            }
        }
    	$result = array_diff($databaseid,$postdataidarr);
    	if($result) {
	    	foreach($result as $val) {
	    		$model = Farmermembers::findOne($val);
	    		Logs::writeLogs('删除家庭成员',$model);
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
//            throw new NotFoundHttpException('The requested page does not exist.');
            return false;
    	}
    }
}
