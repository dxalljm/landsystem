<?php

namespace frontend\controllers;

use app\models\Ttpo;
use Yii;
use app\models\Reviewprocess;
use frontend\models\ReviewprocessSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use app\models\Auditprocess;
use app\models\User;
use app\models\Projectapplication;
use app\models\Tempauditing;
use app\models\Loan;
use app\models\Ttpozongdi;
use app\models\Estate;
use app\models\Processname;
use app\models\Lockedinfo;
use app\models\Zongdioffarm;
/**
 * ReviewprocessController implements the CRUD actions for Reviewprocess model.
 */
class ReviewprocessController extends Controller
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
     * Lists all Reviewprocess models.
     * @return mixed
     */
    public function actionReviewprocessindex()
    {
    	
    	$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
    	if($temp) {
    		$whereArray = Farms::getUserManagementArea($temp['user_id']);
    	} else {
    		$whereArray = Farms::getManagementArea();
    	}
		$Identification = Processname::find()->where(['rolename'=>User::getItemname()])->one()['Identification'];
        $farmstransfer = Reviewprocess::find()->where(['management_area'=>$whereArray['id'],'actionname'=>'farmstransfer',$Identification=>2])->all();
//		var_dump($farmstransfer);exit;

		$projectapplication = Reviewprocess::find()->where(['management_area'=>$whereArray['id'],'actionname'=>'projectapplication'])->all();
       	
		$loan = Reviewprocess::find()->where(['management_area' => $whereArray['id'],'actionname'=>'loancreate'])->all();
       	
        return $this->render('reviewprocessindex', [
			'farmstransfer' => $farmstransfer,
        	'projectapplication' => $projectapplication,
        	'loan' => $loan,
        ]);
    }

	public function actionReviewprocesswait()
	{

		$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
		if($temp) {
			$whereArray = Farms::getUserManagementArea($temp['user_id']);
		} else {
			$whereArray = Farms::getManagementArea();
		}
		$ttpozongdi = Ttpozongdi::find()->where(['state'=>0])->all();

		$projectapplication = Reviewprocess::find()->where(['management_area'=>$whereArray['id'],'actionname'=>'projectapplication'])->all();

		$loan = Reviewprocess::find()->where(['management_area' => $whereArray['id'],'actionname'=>'loancreate'])->all();

		return $this->render('reviewprocesswait', [
			'ttpozongdi' => $ttpozongdi,
			'projectapplication' => $projectapplication,
			'loan' => $loan,
		]);
	}

	public function actionReviewprocessing()
	{

		$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
		if($temp) {
			$whereArray = Farms::getUserManagementArea($temp['user_id']);
		} else {
			$whereArray = Farms::getManagementArea();
		}
		$Identification = Processname::find()->where(['rolename'=>User::getItemname()])->one()['Identification'];
		$farmstransfer = Reviewprocess::find()->where(['management_area'=>$whereArray['id'],'actionname'=>'farmstransfer',$Identification=>1,'state'=>4])->all();
//		var_dump($farmstransfer);exit;

		$projectapplication = Reviewprocess::find()->where(['management_area'=>$whereArray['id'],'actionname'=>'projectapplication'])->all();

		$loan = Reviewprocess::find()->where(['management_area' => $whereArray['id'],'actionname'=>'loancreate'])->all();

		return $this->render('reviewprocessindex', [
			'farmstransfer' => $farmstransfer,
			'projectapplication' => $projectapplication,
			'loan' => $loan,
		]);
	}

	public function actionReviewprocessfinished()
	{

		$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
		if($temp) {
			$whereArray = Farms::getUserManagementArea($temp['user_id']);
		} else {
			$whereArray = Farms::getManagementArea();
		}
//		$Identification = Processname::find()->where(['rolename'=>User::getItemname()])->one()['Identification'];
		$farmstransfer = Reviewprocess::find()->where(['management_area'=>$whereArray['id'],'actionname'=>'farmstransfer','state'=>7])->all();
//		var_dump($farmstransfer);exit;

		$projectapplication = Reviewprocess::find()->where(['management_area'=>$whereArray['id'],'actionname'=>'projectapplication'])->all();

		$loan = Reviewprocess::find()->where(['management_area' => $whereArray['id'],'actionname'=>'loancreate'])->all();

		return $this->render('reviewprocessindex', [
			'farmstransfer' => $farmstransfer,
			'projectapplication' => $projectapplication,
			'loan' => $loan,
		]);
	}
    /**
     * Displays a single Reviewprocess model.
     * @param integer $id
     * @return mixed
     */
  
    public function actionReviewprocessfarmstransfer($oldfarmsid,$newfarmsid,$reviewprocessid)
    {
//     	var_dump($reviewprocessid);
    	$model = new Reviewprocess();
    	$newfarm = Farms::find()->where(['id'=>$newfarmsid])->one();
    	$oldfarm = Farms::find()->where(['id'=>$oldfarmsid])->one();
    	$newttpozongdi = Ttpozongdi::find()->where(['newfarms_id'=>$newfarmsid])->one();
    	$oldttpozongdi = Ttpozongdi::find()->where(['oldfarms_id'=>$oldfarmsid])->one();
    	$reviewprocess = Reviewprocess::find()->where(['id'=>$reviewprocessid])->one();
    	$process = Auditprocess::find()->where(['id'=>$reviewprocess['operation_id']])->one()['process'];
//     	var_dump($process);exit;
    	return $this->render ( 'reviewprocessfarmstransfer', [
    			'oldfarm' => $oldfarm,
    			'newfarm' => $newfarm,
    			'oldttpozongdi' => $oldttpozongdi,
    			'newttpozongdi' => $newttpozongdi,
    			'process' => explode('>', $process),
    			'model' => $model,
    			'reviewprocessid' => $reviewprocessid
    	] );
    }

    public function actionReviewprocessloancreate($farms_id,$reviewprocessid)
    {
    	//     	var_dump($reviewprocessid);
    	$model = new Reviewprocess();
    	$newfarm = Farms::find()->where(['id'=>$newfarmsid])->one();
    	$oldfarm = Farms::find()->where(['id'=>$oldfarmsid])->one();
    	$newttpozongdi = Ttpozongdi::find()->where(['newfarms_id'=>$newfarmsid])->one();
    	$oldttpozongdi = Ttpozongdi::find()->where(['oldfarms_id'=>$oldfarmsid])->one();
    	$reviewprocess = Reviewprocess::find()->where(['id'=>$reviewprocessid])->one();
    	$process = Auditprocess::find()->where(['id'=>$reviewprocess['operation_id']])->one()['process'];
    	//     	var_dump($process);exit;
    	return $this->render ( 'reviewprocessfarmstransfer', [
    			'oldfarm' => $oldfarm,
    			'newfarm' => $newfarm,
    			'oldttpozongdi' => $oldttpozongdi,
    			'newttpozongdi' => $newttpozongdi,
    			'process' => explode('>', $process),
    			'model' => $model,
    			'reviewprocessid' => $reviewprocessid
    	] );
    }
    
    public function actionReviewprocessfarmssplit($newfarmsid,$oldfarmsid,$reviewprocessid)
    {
    	$model = new Reviewprocess();
    	$newfarm = Farms::find()->where(['id'=>$newfarmsid])->one();
    	$oldfarm = Farms::find()->where(['id'=>$oldfarmsid])->one();
    	$newttpozongdi = Ttpozongdi::find()->where(['newfarms_id'=>$newfarmsid])->one();
    	$oldttpozongdi = Ttpozongdi::find()->where(['oldfarms_id'=>$oldfarmsid])->one();
    	$reviewprocess = Reviewprocess::find()->where(['id'=>$reviewprocessid])->one();
    	$process = Auditprocess::find()->where(['id'=>$reviewprocess['operation_id']])->one()['process'];
//     	 var_dump($reviewprocessid);exit;
    	return $this->render ( 'reviewprocessfarmssplit', [
    			'oldfarm' => $oldfarm,
    			'newfarm' => $newfarm,
    			'oldttpozongdi' => $oldttpozongdi,
    			'newttpozongdi' => $newttpozongdi,
    			'process' => explode('>', $process),
    			'model' => $model,
    	] );
    }
    
    public function actionReviewprocessfarmstozongdi($newfarmsid,$oldfarmsid,$reviewprocessid)
    {
    	$model = new Reviewprocess();
    	$newfarm = Farms::find()->where(['id'=>$newfarmsid])->one();
    	$oldfarm = Farms::find()->where(['id'=>$oldfarmsid])->one();
    	$newttpozongdi = Ttpozongdi::find()->where(['newfarms_id'=>$newfarmsid])->one();
    	$oldttpozongdi = Ttpozongdi::find()->where(['oldfarms_id'=>$oldfarmsid])->one();
    	$reviewprocess = Reviewprocess::find()->where(['id'=>$reviewprocessid])->one();
    	$process = Auditprocess::find()->where(['id'=>$reviewprocess['operation_id']])->one()['process'];
//     	var_dump($newfarm);
    	return $this->render ( 'reviewprocessfarmstozongdi', [
    			'oldfarm' => $oldfarm,
    			'newfarm' => $newfarm,
    			'oldttpozongdi' => $oldttpozongdi,
    			'newttpozongdi' => $newttpozongdi,
    			'process' => explode('>', $process),
    			'model' => $model,
    	] );
    }
    
    public function actionReviewprocessinspections($id,$class)
    {
    	$model = $this->findModel($id);
    	$ttpo = Ttpozongdi::find()->where(['reviewprocess_id'=>$id])->one();
    	$ttpoModel = Ttpozongdi::findOne($ttpo['id']);
    	$process = Auditprocess::find()->where(['actionname'=>$model->actionname])->one()['process'];
    	$oldttpozongdi = Ttpozongdi::find()->where(['oldfarms_id'=>$model->oldfarms_id])->one();
    	$newttpozongdi = Ttpozongdi::find()->where(['newfarms_id'=>$model->newfarms_id])->one();
//     	var_dump(Yii::$app->request->post());exit;
    	if($class == 'farmstransfer') {
    		
	    	$oldfarm = Farms::find()->where(['id'=>$model->oldfarms_id])->one();
	    	$newfarm = Farms::find()->where(['id'=>$model->newfarms_id])->one();
	    	if($model->load(Yii::$app->request->post())) {

	    		$model->save();
// 	    		var_dump($_POST);exit;
	    		$state = Reviewprocess::isNextProcess($model->id);
// 	    		exit;
	    		$modelname = Reviewprocess::getProcessIdentification();
	    		foreach ($modelname as $name) {
		    		$classname = 'app\\models\\'.ucfirst($name);
// 		    		var_dump($classname);exit;
		    		$estateID= $classname::find()->where(['reviewprocess_id'=>$id])->one()['id'];
		    		if($estateID)
		    			$estateModel = $classname::findOne($estateID);
		    		else 
		    			$estateModel = new $classname();
		    		foreach ($classname::attributesList() as $key => $value) {
		    			$estateModel->$key = Yii::$app->request->post($key);
		    			$contentName = $key.'content';
		    			$estateModel->$contentName = Yii::$app->request->post($contentName);
		    		}
		    		$estateModel->reviewprocess_id = $id;
		    		$estateModel->save();
// 		    		var_dump($estateModel);exit;
	    		}
// 	    		var_dump($id);
	    		if($state) {
// 	    			var_dump($model->oldfarms_id);exit;
	    			$oldfarmsModel = Farms::findOne($ttpoModel->oldfarms_id);	    			
	    			$oldfarmsModel->update_at = time();
	    			$oldfarmsModel->zongdi = $ttpoModel->oldchangezongdi;
	    			$oldfarmsModel->measure = $ttpoModel->oldchangemeasure;
	    			$oldfarmsModel->notclear = $ttpoModel->oldchangenotclear;
	    			$oldfarmsModel->notstate = $ttpoModel->oldchangenotstate;
	    			$oldfarmsModel->contractarea = Farms::getContractnumberArea($ttpoModel->oldchangecontractnumber);
	    			$oldfarmsModel->contractnumber = $ttpoModel->oldchangecontractnumber;
	    			if($ttpoModel->actionname == 'farmstransfer') {
	    				$oldfarmsModel->state = 0;
	    			} else 
	    				$oldfarmsModel->state = 1;
	    			
	    			$oldfarmsModel->locked = 0;
	    			$oldfarmsModel->save();
	    			
	    			$newfarmModel = Farms::findOne($ttpoModel->newfarms_id);	    			
	    			$newfarmModel->update_at = time();
	    			$newfarmModel->zongdi = $ttpoModel->newchangezongdi;
	    			$newfarmModel->measure = $ttpoModel->newchangemeasure;
	    			$newfarmModel->notclear = $ttpoModel->newchangenotclear;
	    			$newfarmModel->notstate = $ttpoModel->newchangenotstate;
	    			$newfarmModel->contractarea = Farms::getContractnumberArea($ttpoModel->newchangecontractnumber);
	    			$newfarmModel->contractnumber = $ttpoModel->newchangecontractnumber;
	    			$newfarmModel->state = 1;
	    			$newfarmModel->locked = 0;
	    			$newfarmModel->save();
	    			
	    			
	    		}
	    		if(user::getItemname() == '地产科科长') {
		    		return $this->redirect(['reviewprocess/reviewprocessfarmssplit',
		    				'oldfarmsid' => $ttpoModel->oldfarms_id,
		    				'newfarmsid' => $ttpoModel->newfarms_id,
		    				'reviewprocessid' => $model->id,
		    		]);
	    		} else {
	    			return $this->redirect(['reviewprocess/reviewprocessindex']);
	    		}
	    	}
	    	return $this->render ( 'reviewprocessinspections', [
	    			'model' => $model,
	    			'oldfarm' => $oldfarm,
	    			'newfarm' => $newfarm,
	    			'oldttpozongdi' => $oldttpozongdi,
	    			'newttpozongdi' => $newttpozongdi,
	    			'process' => explode('>', $process),
	    			'class' => $class,
	    	] );
    	}
    	if($class == 'projectapplication') {
    		$farm = Farms::find()->where(['id'=>$model->oldfarms_id])->one();
//     		$whereArray = Farms::getManagementArea();
    		$project = Projectapplication::find()->where(['reviewprocess_id'=>$id])->one();
    		if($model->load(Yii::$app->request->post())) {
    			
    			if($model->leader == 0)
    				$model->state = 5;
    			if($model->leader == 1)
    				$model->state = 7;
//     			var_dump($model);
    			$model->save();
    			$state = Reviewprocess::isNextProcess($model->id); 
    			
    			if($state) {
//     				$project = Projectapplication::find()->where(['reviewprocess_id'=>$model->id])->one();
    				$projectModel = Projectapplication::findOne($project->id);
    				$projectModel->state = 1;
    				$projectModel->save();
    				return $this->redirect(['reviewprocess/reviewprocessindex']);
    			}
    		}
    		return $this->render ( 'reviewprocessinspections', [
    				'model' => $model,
    				'farm' => $farm,
    				'process' => explode('>', $process),
    				'class' => $class,
    				'project'=>$project,
    		] );
    	}
    	if($class == 'loan') {
    		$farm = Farms::find()->where(['id'=>$model->oldfarms_id])->one();
    		//     		$whereArray = Farms::getManagementArea();
    		$loan = Loan::find()->where(['reviewprocess_id'=>$id])->one();
    		if($model->load(Yii::$app->request->post())) {
    			 
    			if($model->leader == 0)
    				$model->state = 5;
    			if($model->leader == 1)
    				$model->state = 7;
    			//     			var_dump($model);
    			$model->save();
    			$state = Reviewprocess::isNextProcess($model->id);
    			 
    			if($state) {
    				
    				$loanModel = Loan::findOne($loan->id);
    				$loanModel->state = 1;
    				if($loanModel->save())
    					return $this->redirect(['reviewprocess/reviewprocessindex']);
    			}
    		}
    		return $this->render ( 'reviewprocessinspections', [
    				'model' => $model,
    				'farm' => $farm,
    				'process' => explode('>', $process),
    				'class' => $class,
    				'loan'=>$loan,
    		] );
    	}
    }
    
    public function actionReviewprocessview($id,$class)
    {
    	$model = $this->findModel($id);
    	 
    	$process = Auditprocess::find()->where(['actionname'=>$model->actionname])->one()['process'];
    	$oldttpozongdi = Ttpozongdi::find()->where(['oldfarms_id'=>$model->oldfarms_id])->one();
    	$newttpozongdi = Ttpozongdi::find()->where(['newfarms_id'=>$model->newfarms_id])->one();
    	if($class == 'farmstransfer') {
    		$oldfarm = Farms::find()->where(['id'=>$model->oldfarms_id])->one();
    		$newfarm = Farms::find()->where(['id'=>$model->newfarms_id])->one();
    		if($model->load(Yii::$app->request->post())) {
    			$model->save();
    			// 	    		var_dump($_POST);exit;
    			$state = Reviewprocess::isNextProcess($model->id);
    			$estateID= Estate::find()->where(['reviewprocess_id'=>$id])->one()['id'];
    			$estateModel = Estate::findOne($estateID);
    			foreach (Estate::attributesList() as $key => $value) {
    				$estateModel->$key = Yii::$app->request->post($key);
    				$contentName = $key.'content';
    				$estateModel->$contentName = Yii::$app->request->post($contentName);
    			}
    			$estateModel->save();
    			// 	    		var_dump($estateModel);exit;
    			if($state) {
    
    				$oldfarmsModel = Farms::findOne($model->oldfarms_id);
    
    				$oldfarmsModel->update_at = time();
    				$oldfarmsModel->state = 0;
    				$oldfarmsModel->locked = 0;
    				$oldfarmsModel->save();
    				$newfarmModel = Farms::findOne($model->newfarms_id);
    
    				$newfarmModel->update_at = $oldfarmsModel->update_at;
    				$newfarmModel->state = 1;
    				$newfarmModel->locked = 0;
    				$newfarmModel->save();
    				$projectID = Projectapplication::find()->where(['farms_id'=>$oldfarmsModel->id,'reviewprocess_id'=>$id])->one()['id'];
    				$projectModel = Projectapplication::findOne($projectID);
    				$projectModel->farms_id = $newfarmModel->id;
    				$projectModel->save();
    
    			}
    			return $this->redirect(['reviewprocess/reviewprocessindex']);
    		}
    		return $this->render ( 'reviewprocessview', [
    				'model' => $model,
    				'oldfarm' => $oldfarm,
    				'newfarm' => $newfarm,
    				'oldttpozongdi' => $oldttpozongdi,
    				'newttpozongdi' => $newttpozongdi,
    				'process' => explode('>', $process),
    				'class' => $class,
    		] );
    	}
    	if($class == 'projectapplication') {
    		$farm = Farms::find()->where(['id'=>$model->oldfarms_id])->one();
    		//     		$whereArray = Farms::getManagementArea();
    		$project = Projectapplication::find()->where(['reviewprocess_id'=>$id])->one();
    		if($model->load(Yii::$app->request->post())) {
    			 
    			if($model->leader == 0)
    				$model->state = 5;
    			if($model->leader == 1)
    				$model->state = 7;
    			//     			var_dump($model);
    			$model->save();
    			$state = Reviewprocess::isNextProcess($model->id);
    			 
    			if($state) {
    				//     				$project = Projectapplication::find()->where(['reviewprocess_id'=>$model->id])->one();
    				$projectModel = Projectapplication::findOne($project->id);
    				$projectModel->state = 1;
    				$projectModel->save();
    				return $this->redirect(['reviewprocess/reviewprocessindex']);
    			}
    		}
    		return $this->render ( 'reviewprocessview', [
    				'model' => $model,
    				'farm' => $farm,
    				'process' => explode('>', $process),
    				'class' => $class,
    				'project'=>$project,
    		] );
    	}
    	if($class == 'loan') {
    		$farm = Farms::find()->where(['id'=>$model->oldfarms_id])->one();
    		//     		$whereArray = Farms::getManagementArea();
    		$loan = Loan::find()->where(['reviewprocess_id'=>$id])->one();
    		if($model->load(Yii::$app->request->post())) {
    
    			if($model->leader == 0)
    				$model->state = 5;
    			if($model->leader == 1)
    				$model->state = 7;
    			//     			var_dump($model);
    			$model->save();
    			$state = Reviewprocess::isNextProcess($model->id);
    
    			if($state) {
    
    				$loanModel = Loan::findOne($loan->id);
    				$loanModel->state = 1;
    				if($loanModel->save())
    					return $this->redirect(['reviewprocess/reviewprocessindex']);
    			}
    		}
    		return $this->render ( 'reviewprocessview', [
    				'model' => $model,
    				'farm' => $farm,
    				'process' => explode('>', $process),
    				'class' => $class,
    				'loan'=>$loan,
    		] );
    	}
    }
    
    /**
     * Finds the Reviewprocess model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reviewprocess the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reviewprocess::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
