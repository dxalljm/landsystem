<?php

namespace frontend\controllers;

use Yii;
use app\models\Farms;
use app\models\User;
use app\models\Department;
use frontend\models\farmsSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Theyear;
use app\models\UploadForm;
use app\models\Farmer;
use app\models\ManagementArea;
use yii\web\UploadedFile;
use frontend\models\parcelSearch;
use app\models\Parcel;
use app\models\Logs;
use app\models\CooperativeOfFarm;
use frontend\helpers\Pinyin;

/**
 * FarmsController implements the CRUD actions for farms model.
 */
class FarmsController extends Controller
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
     * Lists all farms models.
     * @return mixed
     */
    public function actionFarmsindex()
    {
        $departmentid = User::find()->where(['id'=>\Yii::$app->getUser()->id])->one()['department_id'];
        $strdepartment = Department::find()->where(['id'=>$departmentid])->one()['membership'];
        $where = explode(',', $strdepartment);
        $searchModel = new farmsSearch();

        $params = Yii::$app->request->queryParams;

        // 管理区域是否是数组
        if (!empty($where) && count($where) > 0) {
          $params['farmsSearch']['management_area'] = $where;
        }
        
        $dataProvider = $searchModel->search($params);
        Logs::writeLog('农场管理');
        return $this->render('farmsindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionFarmszongdi()
    {
    	$farms = Farms::find()->all();
    	foreach ($farms as $val) {
    		$model = $this->findModel($val['id']);
    		$model->zongdi = substr($val['zongdi'],0,strlen($val['zongdi'])-1); 
    		$model->save();
    	}
    }
    
    public function actionGetfarmrows()
    {
    	$dep_id = User::findByUsername(yii::$app->user->identity->username)['department_id'];
    	$departmentData = Department::find()->where(['id'=>$dep_id])->one();
    	$whereArray = explode(',', $departmentData['membership']);
    	$farmsRows = Farms::find()->where(['management_area'=>$whereArray])->count();
    	//echo $departmentData['membership'];
		echo Json::encode(['status' => 1, 'count' => $farmsRows]);
		Yii::$app->end();
    }
    
    public function actionGetfarmarea()
    {
    	$sumMeasure = 0;
    	$dep_id = User::findByUsername(yii::$app->user->identity->username)['department_id'];
    	$departmentData = Department::find()->where(['id'=>$dep_id])->one();
    	$whereArray = explode(',', $departmentData['membership']);
    	$farms = Farms::find()->where(['management_area'=>$whereArray])->all();
    	foreach ($farms as $value) {
    		if(is_array($value)) {
    			foreach ($value as $k => $v) {
    				$arrayID[] = $v['id'];
    				$sumMeasure += $v['measure'];
    			}
    		} else {
    			$arrayID[] = $value['id'];
    			$sumMeasure += $value['measure'];
    		}
    	}
		echo Json::encode(['status' => 1, 'count' => $sumMeasure]);
		Yii::$app->end();
    }
    
    public function actionFarmsxls()
    {
    	set_time_limit(0);
    	$model = new UploadForm();
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
    				$farmsmodel = new Farms();
					//$farmsmodel = $loadxls->getActiveSheet()->getCell('A'.$i)->getValue();
    				$farmsmodel->id = $loadxls->getActiveSheet()->getCell('A'.$i)->getValue();
    				$farmsmodel->management_area = ManagementArea::find()->where(['areaname'=>$loadxls->getActiveSheet()->getCell('B'.$i)->getValue()])->one()['id'];
    				$farmsmodel->farmname =  $loadxls->getActiveSheet()->getCell('C'.$i)->getValue();
    				$farmsmodel->farmername = $loadxls->getActiveSheet()->getCell('D'.$i)->getValue();
    				$farmsmodel->address =  $loadxls->getActiveSheet()->getCell('E'.$i)->getValue();
    				$farmsmodel->cardid = $loadxls->getActiveSheet()->getCell('F'.$i)->getValue();
    				$farmsmodel->telephone = $loadxls->getActiveSheet()->getCell('G'.$i)->getValue();
    				$farmsmodel->spyear =  $loadxls->getActiveSheet()->getCell('H'.$i)->getValue();
					$time = ($loadxls->getActiveSheet()->getCell('I'.$i)->getValue() - 25569)*24*60*60;
    				$farmsmodel->surveydate =  date('Y-m-d',$time);
    				//echo $farmsmodel->surveydate;
    				$farmsmodel->groundsign =  $loadxls->getActiveSheet()->getCell('J'.$i)->getValue();
    				$farmsmodel->investigator =  $loadxls->getActiveSheet()->getCell('K'.$i)->getValue();
    				$farmsmodel->farmersign =  $loadxls->getActiveSheet()->getCell('L'.$i)->getValue();
    				$farmsmodel->create_at = time();
    				$farmsmodel->update_at = time();
					//var_dump(Pinyin::encode($loadxls->getActiveSheet()->getCell('D'.$i)->getValue()));
    				$farmsmodel->pinyin = Pinyin::encode($loadxls->getActiveSheet()->getCell('C'.$i)->getValue());
    				$farmsmodel->farmerpinyin = Pinyin::encode($loadxls->getActiveSheet()->getCell('D'.$i)->getValue());
     				$farmsmodel->save();
     				//var_dump($farmsmodel->getErrors());
    			}
    		}
    	}
    	Logs::writeLog('农场XLS批量导入');
    	return $this->render('farmsxls',[
    			'model' => $model,
    			'rows' => $rows,
    	]);
    }
    
    public function actionFarmszdxls()
    {
    	set_time_limit(0);
    	$model = new UploadForm();
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
    				//echo $loadxls->getActiveSheet()->getCell('A'.$i)->getValue().'-----'.$loadxls->getActiveSheet()->getCell('B'.$i)->getValue().'-----'.$loadxls->getActiveSheet()->getCell('C'.$i)->getValue().'<br>';
    				//echo $loadxls->getActiveSheet()->getCell('F'.$i)->getValue()."<br>";
    				//echo  ManagementArea::find()->where(['areaname'=>$loadxls->getActiveSheet()->getCell('B'.$i)->getValue()])->one()['id'];"<br>";
    				$isFarm = Farms::find()->where(['farmname'=>$loadxls->getActiveSheet()->getCell('B'.$i)->getValue(),'farmername'=>$loadxls->getActiveSheet()->getCell('C'.$i)->getValue()])->one()['id'];
    				//echo $loadxls->getActiveSheet()->getCell('A'.$i)->getValue().'-------'.$isFarm.'-------'.$loadxls->getActiveSheet()->getCell('B'.$i)->getValue().'<br>';
    				if($isFarm) {
    					$farmsmodel = $this->findModel($isFarm);
    						$zongdistr = '';
	    					$zongdistr .= $loadxls->getActiveSheet()->getCell('D'.$i)->getValue().'、';
	    					$farmsmodel->zongdi = substr($zongdistr,0,strlen($zongdistr)-1);
	    					$farmsmodel->measure += Parcel::find()->where(['unifiedserialnumber' => $loadxls->getActiveSheet()->getCell('D'.$i)->getValue()])->one()['grossarea'];
	    					$farmsmodel->save();
	    					echo $loadxls->getActiveSheet()->getCell('A'.$i)->getValue().'-------'.$isFarm.'-------'.$loadxls->getActiveSheet()->getCell('C'.$i)->getValue().'<br>';
    					//}
    					
    				}
  				//print_r($farmsmodel->getErrors());
    			}
    		}
    		echo '完成';
    	}
    	
    	Logs::writeLog('宗地XLS批量导入');
    	return $this->render('farmszdxls',[
    			'model' => $model,
    			'rows' => $rows,
    	]);
    }
    
    public function actionFarmsbusiness()
    {
    	
    	$departmentid = User::find()->where(['id'=>\Yii::$app->getUser()->id])->one()['department_id'];
    	$strdepartment = Department::find()->where(['id'=>$departmentid])->one()['membership'];
    	$where = explode(',', $strdepartment);
    	$searchModel = new farmsSearch();
    	$params = Yii::$app->request->queryParams;

        // 管理区域是否是数组
        if (!empty($where) && count($where) > 0) {
          $params['farmsSearch']['management_area'] = $where;
        }
        
        $dataProvider = $searchModel->search($params);
    	Logs::writeLog('业务办理');
    	return $this->render('farmsbusiness', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    }

    public function actionFarmsmenu($farms_id)
    {
    	$farm = $this->findModel($farms_id);
    	Logs::writeLog('进入业务办理菜单页面',$farms_id);
    	return $this->render('farmsmenu',[
    		'farm' => $farm,
    		'year' => Theyear::findOne(1),
    	]);
    }
    
    /**
     * Displays a single Farms model.
     * @param integer $id
     * @return mixed
     */
    public function actionFarmsview($id)
    {
    	$model = $this->findModel($id);
    	$cooperativeoffarm = CooperativeOfFarm::find()->where(['farms_id'=>$id])->all();
    	$zongdiarr = explode(' ', $model->zongdi);
    	foreach($zongdiarr as $zongdi) {
    		$dataProvider[] = Parcel::find()->where(['unifiedserialnumber' => $zongdi])->one();
    	}
    	Logs::writeLog('查看农场信息',$id);
        return $this->render('farmsview', [
            'model' => $model,
        	'dataProvider' => $dataProvider,
        	'cooperativeoffarm' => $cooperativeoffarm,
        ]);
    }

    /**
     * Creates a new farms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionFarmscreate()
    {
        $model = new farms();
        
        if ($model->load(Yii::$app->request->post())) {
        	$model->create_at = time();
        	$model->update_at = time();
        	$model->save();
        	$newAttr = $model->attributes;
        	Logs::writeLog('创建农场',$model->id,'',$newAttr);
            return $this->redirect(['farmsview', 'id' => $model->id]);
        } else {
        	//Logs::writeLog('农场创建表单');
            return $this->render('farmscreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Farms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFarmsupdate($id)
    {
        $model = $this->findModel($id);
		$oldAttr = $model->attributes;
        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->save();
        	$newAttr = $model->attributes;
        	Logs::writeLog('更新农场信息',$id,$oldAttr,$newAttr);
            
            return $this->redirect(['farmsview', 'id' => $model->id]);
        } else {
        	//Logs::writeLog('农场更新表单');
            return $this->render('farmsupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Farms model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFarmsdelete($id)
    {
    	$model = $this->findModel($id);
    	$oldAttr = $model->getAttributes();
        $model->delete();      
        Logs::writeLog('删除农场信息',$id,$oldAttr);
        return $this->redirect(['farmsindex']);
    }

    /**
     * Finds the farms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return farms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = farms::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
