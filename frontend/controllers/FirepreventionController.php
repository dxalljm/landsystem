<?php

namespace frontend\controllers;

use app\models\Picturelibrary;
use frontend\models\farmsSearch;
use Yii;
use app\models\Fireprevention;
use frontend\models\firepreventionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Employee;
use app\models\Lease;
use app\models\Firepreventionemployee;
use app\models\Logs;
use app\models\Farms;
use app\models\User;
/**
 * FirepreventionController implements the CRUD actions for Fireprevention model.
 */
class FirepreventionController extends Controller
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
     * Lists all Fireprevention models.
     * @return mixed
     */
    public function actionFirepreventionindex()
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
        $searchModel = new firepreventionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		Logs::writeLog('防火工作');
        return $this->render('firepreventionindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionFirepreventioninfo()
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
    	$searchModel = new firepreventionSearch();
    	$params = Yii::$app->request->queryParams;
//		$searchModel = new farmsSearch();
//		$params = Yii::$app->request->queryParams;
   		$whereArray = Farms::getManagementArea()['id'];
   		//var_dump($whereArray);exit;
    	if (empty($params['firepreventionSearch']['management_area'])) {
    		$params ['firepreventionSearch'] ['management_area'] = $whereArray;
    	}

    	//if (is_array($searchModel->management_area)) {
		//	$searchModel->management_area = null;
		//}
//		$params['firepreventionSearch']['farmstate'] = [1,2,3,4,5];
		$params['firepreventionSearch']['year'] = User::getYear();
		$dataProvider = $searchModel->searchindex( $params );
		
    	Logs::writeLog('首页板块-防火工作');
    	return $this->render('firepreventioninfo', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'params' => $params,
    	]);
    }
    
    /**
     * Displays a single Fireprevention model.
     * @param integer $id
     * @return mixed
     */
    public function actionFirepreventionview($farms_id,$id=null)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
		if(empty($id)) {
			$farm = Farms::findOne($farms_id);
			$model = new Fireprevention();
			$model->year = User::getYear();
			$model->farms_id = $farms_id;
			$model->farmstate = $farm->state;
			$model->management_area = $farm->management_area;
			$model->create_at = time();
			$model->update_at = $model->create_at;
			$model->finished = 0;
			$model->save();
		} else {
			$model = $this->findModel($id);
		}
		$picArray = [];
		$pics = Picturelibrary::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
		foreach($pics as $pic) {
			$picArray[$pic['field']][] = $pic['pic'];
		}
    	$employees[] = Employee::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
    	Logs::writeLog('查看防火工作',$farms_id);
        return $this->render('firepreventionview', [
            'model' => $model,
        	'employees' => $employees,
			'picArray' => $picArray,
        ]);
    }

	/*
	 * 基础数据调查三项防火宣传
	 * */
	public function actionFirepreventionajax($farms_id)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
		$firecontract = 0;
		$safecontract = 0;
		$environmental_agreement = 0;
		$fire = Fireprevention::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
		if($fire) {
			$firecontract = $fire['firecontract'];
			$safecontract = $fire['safecontract'];
			$environmental_agreement = $fire['environmental_agreement'];
		}
		return $this->renderAjax('firepreventionajax',[
			'farms_id' => $farms_id,
			'firecontract' => $firecontract,
			'safecontract' => $safecontract,
			'environmental_agreement' => $environmental_agreement,
		]);
	}
    /**
     * Creates a new Fireprevention model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionFirepreventioncreate($farms_id)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
    	$oldAttr = '';
    	if($this->findFarmsModel($farms_id)) {
    		$model = $this->findFarmsModel($farms_id);
    		$model->update_at = time();
    		$oldAttr = $model->attributes;
    	}
    	else {
    		$model = new Fireprevention();
    		$model->create_at = time();
    		$model->update_at = time();
    		$model->management_area = Farms::getFarmsAreaID($farms_id);
    	}

        //$lease = Lease::find()->where(['farms_id'=>$farms_id])->all();
		$employees[] = Employee::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();

		
        if ($model->load(Yii::$app->request->post())) {
			$model->year = User::getYear();
			$fieldpermit = Picturelibrary::find()->where(['farms_id'=>$farms_id,'field'=>'fieldpermit','year'=>User::getYear()])->count();
			if($fieldpermit) {
				$model->fieldpermit = "1";
			} else {
				$model->fieldpermit = "0";
			}
			$leaflets = Picturelibrary::find()->where(['farms_id'=>$farms_id,'field'=>'leaflets','year'=>User::getYear()])->count();
			if($leaflets) {
				$model->leaflets = "1";
			} else {
				$model->leaflets = "0";
			}
			$rectification_record = Picturelibrary::find()->where(['farms_id'=>$farms_id,'field'=>'rectification_record','year'=>User::getYear()])->count();
			if($rectification_record) {
				$model->rectification_record = "1";
			} else {
				$model->rectification_record = "0";
			}
//			var_dump($model);
        	$model->save();
			$percent = Fireprevention::getPercent($model);
			$model->percent = $percent;
			if($percent > 60) {
				$model->finished = 1;
			}
			if($percent > 0 and $percent <= 60) {
				$model->finished = 2;
			}
			if($percent == 0 or empty($percent)) {
				$model->finished = 0;
			}
			$model->save();
//			var_dump($model);exit;
        	Logs::writeLogs('添加防火信息',$model);
			$ArrEmployeesFire = Yii::$app->request->post('ArrEmployeesFire');
			$row = count($ArrEmployeesFire['id']);
			$old = '';
			for($i=0;$i<$row;$i++) {
				if($this->findFirepreventionemployeeModel($ArrEmployeesFire['id'][$i])) {
					$fireemployeeModel = $this->findFirepreventionemployeeModel($ArrEmployeesFire['id'][$i]);
					$old = $fireemployeeModel->attributes;
					$fireemployeeModel->update_at = time();
					$message = '更新雇工防火信息';
				}
				else {
					$fireemployeeModel = new Firepreventionemployee();
					$fireemployeeModel->create_at = time();
					$fireemployeeModel->update_at = time();
					$message = '创建雇工防火信息';
				}
				$fireemployeeModel->employee_id = $ArrEmployeesFire['employee_id'][$i];
				$fireemployeeModel->is_smoking = $ArrEmployeesFire['is_smoking'][$i];
				$fireemployeeModel->is_retarded = $ArrEmployeesFire['is_retarded'][$i];
	//         	$fireemployeeModel->management_area = Farms::getFarmsAreaID($farms_id);
				$fireemployeeModel->save();
				$new = $fireemployeeModel->attributes;
				Logs::writeLogs($message,$fireemployeeModel,'firepreventionemployee');

			}
            return $this->redirect(['firepreventionview', 'id' => $model->id,'farms_id'=>$farms_id]);
        } else {
            return $this->render('firepreventioncreate', [
                'model' => $model,
            	'employees' => $employees,
            	//'fireemployeeModel' => $fireemployeeModel,
            ]);
        }
    }

	public function actionFirepreventioncreateajax($farms_id)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
		$oldAttr = '';
		if($this->findFarmsModel($farms_id)) {
			$model = $this->findFarmsModel($farms_id);
			$model->update_at = time();
			$oldAttr = $model->attributes;
		}
		else {
			$model = new Fireprevention();
			$model->create_at = time();
			$model->update_at = time();
			$model->management_area = Farms::getFarmsAreaID($farms_id);
		}

		//$lease = Lease::find()->where(['farms_id'=>$farms_id])->all();
		$employees[] = Employee::find()->where(['farms_id'=>$farms_id])->all();


		if ($model->load(Yii::$app->request->post())) {
			$model->save();
			Logs::writeLogs('添加防火信息',$model);
			$ArrEmployeesFire = Yii::$app->request->post('ArrEmployeesFire');
			$row = count($ArrEmployeesFire['id']);
			$old = '';
			for($i=0;$i<$row;$i++) {
				if($this->findFirepreventionemployeeModel($ArrEmployeesFire['id'][$i])) {
					$fireemployeeModel = $this->findFirepreventionemployeeModel($ArrEmployeesFire['id'][$i]);
					$old = $fireemployeeModel->attributes;
					$fireemployeeModel->update_at = time();
					$message = '更新雇工防火信息';
				}
				else {
					$fireemployeeModel = new Firepreventionemployee();
					$fireemployeeModel->create_at = time();
					$fireemployeeModel->update_at = time();
					$message = '创建雇工防火信息';
				}
				$fireemployeeModel->employee_id = $ArrEmployeesFire['employee_id'][$i];
				$fireemployeeModel->is_smoking = $ArrEmployeesFire['is_smoking'][$i];
				$fireemployeeModel->is_retarded = $ArrEmployeesFire['is_retarded'][$i];
//         	$fireemployeeModel->management_area = Farms::getFarmsAreaID($farms_id);
				$fireemployeeModel->save();
				$new = $fireemployeeModel->attributes;
				Logs::writeLogs($message,$fireemployeeModel,'firepreventionemployee');

			}
			return $this->redirect(['/sixcheck/sixcheckindex', 'farms_id'=>$farms_id]);
		} else {
			return $this->renderAjax('firepreventioncreate', [
				'model' => $model,
				'employees' => $employees,
				//'fireemployeeModel' => $fireemployeeModel,
			]);
		}
	}

    /**
     * Updates an existing Fireprevention model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
//     public function actionFirepreventionupdate($id)
//     {
//         $model = $this->findModel($id);

//         if ($model->load(Yii::$app->request->post()) && $model->save()) {
//             return $this->redirect(['firepreventionview', 'id' => $model->id]);
//         } else {
//             return $this->render('firepreventionupdate', [
//                 'model' => $model,
//             ]);
//         }
//     }

    /**
     * Deletes an existing Fireprevention model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
//     public function actionFirepreventiondelete($id)
//     {
//         $this->findModel($id)->delete();

//         return $this->redirect(['firepreventionindex']);
//     }

    public function actionFirepreventionsearch($begindate,$enddate)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
    	if(isset($_GET['tab']) and $_GET['tab'] !== \Yii::$app->controller->id) {
    		if($_GET['tab'] == 'yields')
    			$class = 'plantingstructureSearch';
    		else
    			$class = $_GET['tab'].'Search';
    		return $this->redirect ([$_GET['tab'].'/'.$_GET['tab'].'search',
    				'tab' => $_GET['tab'],
    				'begindate' => strtotime($_GET['begindate']),
    				'enddate' => strtotime($_GET['enddate']),
// 					$class =>['management_area' =>  $_GET['management_area']],
    		]);
    	} 
    	$searchModel = new firepreventionSearch();
    	if(!is_numeric($_GET['begindate']))
    		$_GET['begindate'] = strtotime($_GET['begindate']);
    	if(!is_numeric($_GET['enddate']))
    		$_GET['enddate'] = strtotime($_GET['enddate']);
    
    	$dataProvider = $searchModel->searchIndex ( $_GET );
		Logs::writeLogs('综合查询-防火工作');
    	return $this->render('firepreventionsearch',[
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'tab' => $_GET['tab'],
    			'begindate' => $_GET['begindate'],
    			'enddate' => $_GET['enddate'],
    			'params' => $_GET,
    	]);
    }
    /**
     * Finds the Fireprevention model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Fireprevention the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fireprevention::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    //判断是否存在farms_id的数据
    protected function findFarmsModel($farms_id)
    {
    	if (($model = Fireprevention::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one()) !== null) {
    		return $model;
    	} else {
    		return false;
    	}
    }
    
    protected function findFirepreventionemployeeModel($id)
    {
    	if (($model = Firepreventionemployee::findOne($id)) !== null) {
    		//print_r($model);
    		return $model;
    	} else {
    		return false;
    	}
    }
}
