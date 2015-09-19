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
        $dataProvider = $searchModel->search(['management_area'=>$where]);
        Logs::writeLog('农场管理');
        return $this->render('farmsindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
					$farmsmodel = $this->findModel($loadxls->getActiveSheet()->getCell('A'.$i)->getValue());
    				$farmsmodel->id = $loadxls->getActiveSheet()->getCell('A'.$i)->getValue();
    				$farmsmodel->management_area = ManagementArea::find()->where(['areaname'=>$loadxls->getActiveSheet()->getCell('B'.$i)->getValue()])->one()['id'];
    				$farmsmodel->farmname =  $loadxls->getActiveSheet()->getCell('C'.$i)->getValue();
    				$farmsmodel->address =  $loadxls->getActiveSheet()->getCell('D'.$i)->getValue();
    				$farmsmodel->spyear =  $loadxls->getActiveSheet()->getCell('E'.$i)->getValue();
					$time = ($loadxls->getActiveSheet()->getCell('F'.$i)->getValue() - 25569)*24*60*60;
    				$farmsmodel->surveydate =  date('Y-m-d',$time);
    				//echo $farmsmodel->surveydate;
    				$farmsmodel->groundsign =  $loadxls->getActiveSheet()->getCell('G'.$i)->getValue();
    				$farmsmodel->investigator =  $loadxls->getActiveSheet()->getCell('H'.$i)->getValue();
    				$farmsmodel->farmersign =  $loadxls->getActiveSheet()->getCell('I'.$i)->getValue();
    				$farmsmodel->create_at = time();
    				$farmsmodel->update_at = time();
     				$farmsmodel->save();
//     				print_r($farmsmodel->getErrors());
    			}
    		}
    	}
    	Logs::writeLog('农场XLS批量导入');
    	return $this->render('farmsxls',[
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
    	$dataProvider = $searchModel->search(['management_area'=>$where]);
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
    	
    	$zongdiarr = explode(' ', $model->zongdi);
    	foreach($zongdiarr as $zongdi) {
    		$dataProvider[] = Parcel::find()->where(['unifiedserialnumber' => $zongdi])->one();
    	}
    	Logs::writeLog('查看农场信息',$id);
        return $this->render('farmsview', [
            'model' => $model,
        	'dataProvider' => $dataProvider,
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
