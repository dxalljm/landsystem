<?php

namespace frontend\controllers;

use app\models\Farmerinfo;
use app\models\Logs;
use app\models\Machineapply;
use app\models\Machinesubsidy;
use app\models\User;
use Yii;
use app\models\Machineoffarm;
use frontend\models\MachineoffarmSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\MachineSearch;
use app\models\Machinetype;
use app\models\Machine;
use app\models\Farms;
use frontend\helpers\params;
/**
 * MachineoffarmController implements the CRUD actions for Machineoffarm model.
 */
class MachineoffarmController extends Controller {
	public function behaviors() {
		return [ 
				'verbs' => [ 
						'class' => VerbFilter::className (),
						'actions' => [ 
								'delete' => [ 
										'post' 
								] 
						] 
				] 
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
	/**
	 * Lists all Machineoffarm models.
	 * 
	 * @return mixed
	 */
	public function actionMachineoffarmindex($farms_id) {
//		var_dump($farmerCardID);exit;
		$cardid = Farms::find()->where(['id'=>$farms_id])->one()['cardid'];
		$searchModel = new MachineoffarmSearch ();
		$params = Yii::$app->request->queryParams;
		$params ['MachineoffarmSearch'] ['cardid'] = $cardid;
		$dataProvider = $searchModel->search ( $params );
		Logs::writeLog('农场农机器具列表',$farms_id);
		return $this->render ( 'machineoffarmindex', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'farms_id' => $farms_id,
		] );
	}


	public function actionMachineoffarmajaxindex($farms_id) {
//		var_dump($farmerCardID);exit;
		$cardid = Farms::find()->where(['id'=>$farms_id])->one()['cardid'];
		$searchModel = new MachineoffarmSearch ();
		$params = Yii::$app->request->queryParams;
		$params ['MachineoffarmSearch'] ['cardid'] = $cardid;
		$dataProvider = $searchModel->search ( $params );
		Logs::writeLog('农场农机器具列表',$farms_id);
		return $this->renderAjax( 'machineoffarmindex', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'farms_id' => $farms_id,
		] );
	}
	/**
	 * Displays a single Machineoffarm model.
	 * 
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionMachineoffarmview($id) {
		return $this->render ( 'machineoffarmview', [ 
				'model' => $this->findModel ( $id ) 
		] );
	}

	public function actionMachineapply($farms_id)
	{
		$farm = Farms::findOne($farms_id);
		$farmerinfo = Farmerinfo::find()->where(['cardid'=>$farm['cardid']])->one();
		return $this->render ( 'machineapply', [
			'farm' => $farm,
			'farmer' => $farmerinfo
		] );
	}

	public function actionMachineprint($farms_id)
	{
		$farm = Farms::findOne($farms_id);
		$farmerinfo = Farmerinfo::find()->where(['cardid'=>$farm['cardid']])->one();
		return $this->render ( 'machineprint', [
			'farm' => $farm,
			'farmer' => $farmerinfo
		] );
	}

	/**
	 * Creates a new Machineoffarm model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * 
	 * @return mixed
	 */
	public function actionMachineoffarmcreate($farms_id) {
		
		$model = new Machineoffarm ();
		$lastclass = null;
		$smallclass = null;
		$bigclass = null;
		$save = false;
		$searchModel = new MachineSearch ();
		$params = Yii::$app->request->queryParams;
//		$params['MachineSearch']['year'] = User::getYear();
// 		if ($machine_id !== 0) {
// 			$machinename = Machine::find ()->where ( [ 
// 					'id' => $machine_id 
// 			] )->one ();
// 			$model->farms_id = $farms_id;
// 			$model->machinename = $machinename ['productname'];
// 			$model->machine_id = $machine_id;
// 			$model->machinetype_id = $machinename ['machinetype_id'];
// 			$model->create_at = time ();
// 			$model->update_at = $model->create_at;
// 			$model->acquisitiontime = $acquisitiontime;
// 			$save = $model->save ();
// 			if ($save)
// 				return $this->redirect ( [ 
// 						'machineoffarmindex',
// 						'farms_id' => $model->farms_id 
// 				] );
// 		}
		
		$post = Yii::$app->request->post ();
// 		var_dump($post);exit;
		if ($post) {
// 			
			if (isset ( $post ['lastclass'] ))
				$params ['MachineSearch'] ['machinetype_id'] = $post ['lastclass'];
			if (isset ( $post ['MachineSearch'] ['productname'] ))
				$params ['MachineSearch'] ['productname'] = $post ['MachineSearch'] ['productname'];
			if (isset ( $post ['MachineSearch'] ['implementmodel'] ))
				$params ['MachineSearch'] ['implementmodel'] = $post ['MachineSearch'] ['implementmodel'];
			if (isset ( $post ['MachineSearch'] ['filename'] ))
				$params ['MachineSearch'] ['filename'] = $post ['MachineSearch'] ['filename'];
			if (isset ( $post ['MachineSearch'] ['enterprisename'] ))
				$params ['MachineSearch'] ['enterprisename'] = $post ['MachineSearch'] ['enterprisename'];
			if (isset ( $post ['lastclass'] ))
				$lastclass = $post ['lastclass'];
			$smallclass = Machinetype::find ()->where ( [ 
					'id' => $lastclass 
			] )->one ()['father_id'];
			$bigclass = Machinetype::find ()->where ( [ 
					'id' => $smallclass 
			] )->one ()['father_id'];
			if ($model->load ( Yii::$app->request->post () )) {
				if (Yii::$app->request->post ('tempState') == 'create') {
// 					var_dump($model);exit;
					$machine = Machine::find ()->where ( [ 
							'id' => $model->machine_id 
					] )->one ();
//					var_dump($farmerCardID);
					$model->cardid = Farms::find()->where(['id'=>$farms_id])->one()['cardid'];
					if($machine)
						$model->machinename = $machine ['productname'];
					else
						$_POST['Machineoffarm']['machinename'];
					if($lastclass)
						$model->machinetype_id = $lastclass;
					else
						$model->machinetype_id = $machine ['machinetype_id'];
					if ($model->machine_id)
						$model->machine_id = $model->machine_id;
					else
						$model->machine_id = 0;
					$model->create_at = time ();
					$model->update_at = $model->create_at;
					$model->acquisitiontime = $_POST['Machineoffarm']['acquisitiontime'];
					$save = $model->save ();
					Logs::writeLogs('新增农机器具',$model);
				}
				if ($save)
					return $this->redirect ( [ 
							'machineoffarmindex',
							'farms_id' => $farms_id,
					] );
			}
		}
		$dataProvider = $searchModel->search ( $params );
		
		return $this->render ( 'machineoffarmcreate', [ 
				'model' => $model,
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'lastclass' => $lastclass,
				'smallclass' => $smallclass,
				'bigclass' => $bigclass,
//				'farms_id' => $farms_id
		] );
	}

	public function actionMachineoffarmadd($farms_id,$id) {

		$model = new Machineoffarm ();
		$lastclass = null;
		$smallclass = null;
		$bigclass = null;
		$save = false;
		$searchModel = new MachineSearch ();
		$params = Yii::$app->request->queryParams;
		$params['MachineSearch']['year'] = User::getYear();

		$post = Yii::$app->request->post ();
		if ($post) {
//
			if (isset ( $post ['lastclass'] ))
				$params ['MachineSearch'] ['machinetype_id'] = $post ['lastclass'];
			if (isset ( $post ['MachineSearch'] ['productname'] ))
				$params ['MachineSearch'] ['productname'] = $post ['MachineSearch'] ['productname'];
			if (isset ( $post ['MachineSearch'] ['implementmodel'] ))
				$params ['MachineSearch'] ['implementmodel'] = $post ['MachineSearch'] ['implementmodel'];
			if (isset ( $post ['MachineSearch'] ['filename'] ))
				$params ['MachineSearch'] ['filename'] = $post ['MachineSearch'] ['filename'];
			if (isset ( $post ['MachineSearch'] ['enterprisename'] ))
				$params ['MachineSearch'] ['enterprisename'] = $post ['MachineSearch'] ['enterprisename'];
			if (isset ( $post ['lastclass'] ))
				$lastclass = $post ['lastclass'];
			$smallclass = Machinetype::find ()->where ( [
				'id' => $lastclass
			] )->one ()['father_id'];
			$bigclass = Machinetype::find ()->where ( [
				'id' => $smallclass
			] )->one ()['father_id'];
			if ($model->load ( Yii::$app->request->post () )) {
				if (Yii::$app->request->post ('tempState') == 'create') {
// 					var_dump($model);exit;
					$machine = Machine::find ()->where ( [
						'id' => $model->machine_id
					] )->one ();
//					var_dump($farmerCardID);
					$model->cardid = Farms::find()->where(['id'=>$farms_id])->one()['cardid'];
					if($machine)
						$model->machinename = $machine ['productname'];
					else
						$_POST['Machineoffarm']['machinename'];
					if($lastclass)
						$model->machinetype_id = $lastclass;
					else
						$model->machinetype_id = $machine ['machinetype_id'];
					if ($model->machine_id)
						$model->machine_id = $model->machine_id;
					else
						$model->machine_id = 0;
					$model->create_at = time ();
					$model->update_at = $model->create_at;
					$model->acquisitiontime = $_POST['Machineoffarm']['acquisitiontime'];
					$save = $model->save ();
					Logs::writeLogs('确认农机补贴器具',$model);
				}
				if ($save)
					return $this->redirect ( ['machineoffarmcomparison',
						'apply_id' => $id,
						'machineoffarm_id' => $model->id,
					] );
			}
		}
		$dataProvider = $searchModel->search ( $params );

		return $this->render ( 'machineoffarmadd', [
			'model' => $model,
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'lastclass' => $lastclass,
			'smallclass' => $smallclass,
			'bigclass' => $bigclass,
//				'farms_id' => $farms_id
		] );
	}

	public function actionMachineoffarmcomparison($apply_id,$machineoffarm_id)
	{
		$machineoffarm = Machineoffarm::findOne($machineoffarm_id);
//		var_dump($machineoffarm);exit;
		$machine = Machine::findOne($machineoffarm->machine_id);
//		var_dump($machine);exit;
		$machineapply = Machineapply::findOne($apply_id);
		$machineapply->machineoffarm_id = $machineoffarm_id;
		$machineapply->save();
		$machinesubsidys = Machinesubsidy::find()->where(['machinetype_id'=>$machineoffarm->machinetype_id])->all();
		Logs::writeLogs('确认补贴金额页面',$machineapply);
		return $this->render ( 'machineoffarmcomparison', [
			'apply_id' => $apply_id,
			'machieoffarm' => $machineoffarm,
			'machine' => $machine,
			'machinesubsidys' => $machinesubsidys
		] );
	}

	public function actionMachineoffarmsix($farms_id) {

		$model = new Machineoffarm ();
		$lastclass = null;
		$smallclass = null;
		$bigclass = null;
		$save = false;
		$searchModel = new MachineSearch ();
		$params = Yii::$app->request->queryParams;

		$post = Yii::$app->request->post ();
		if ($post) {
//
			if (isset ( $post ['lastclass'] ))
				$params ['MachineSearch'] ['machinetype_id'] = $post ['lastclass'];
			if (isset ( $post ['MachineSearch'] ['productname'] ))
				$params ['MachineSearch'] ['productname'] = $post ['MachineSearch'] ['productname'];
			if (isset ( $post ['MachineSearch'] ['implementmodel'] ))
				$params ['MachineSearch'] ['implementmodel'] = $post ['MachineSearch'] ['implementmodel'];
			if (isset ( $post ['MachineSearch'] ['filename'] ))
				$params ['MachineSearch'] ['filename'] = $post ['MachineSearch'] ['filename'];
			if (isset ( $post ['MachineSearch'] ['enterprisename'] ))
				$params ['MachineSearch'] ['enterprisename'] = $post ['MachineSearch'] ['enterprisename'];
			if (isset ( $post ['lastclass'] ))
				$lastclass = $post ['lastclass'];
			$smallclass = Machinetype::find ()->where ( [
				'id' => $lastclass
			] )->one ()['father_id'];
			$bigclass = Machinetype::find ()->where ( [
				'id' => $smallclass
			] )->one ()['father_id'];
			if ($model->load ( Yii::$app->request->post () )) {
				if (Yii::$app->request->post ('tempState') == 'create') {
// 					var_dump($model);exit;
					$machine = Machine::find ()->where ( [
						'id' => $model->machine_id
					] )->one ();
//					var_dump($farmerCardID);
					$model->cardid = Farms::find()->where(['id'=>$farms_id])->one()['cardid'];
					if($machine)
						$model->machinename = $machine ['productname'];
					else
						$_POST['Machineoffarm']['machinename'];
					if($lastclass)
						$model->machinetype_id = $lastclass;
					else
						$model->machinetype_id = $machine ['machinetype_id'];
					if ($model->machine_id)
						$model->machine_id = $model->machine_id;
					else
						$model->machine_id = 0;
					$model->create_at = time ();
					$model->update_at = $model->create_at;
					$model->acquisitiontime = $_POST['Machineoffarm']['acquisitiontime'];
					$save = $model->save ();
				}
				if ($save)
					return $this->redirect ( [
						'sixcheck/sixcheckindex',
						'farms_id' => $farms_id,
					] );
			}
		}
		$dataProvider = $searchModel->search ( $params );

		return $this->renderAjax( 'machineoffarmsix', [
			'model' => $model,
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'lastclass' => $lastclass,
			'smallclass' => $smallclass,
			'bigclass' => $bigclass,
//				'farms_id' => $farms_id
		] );
	}

	/**
	 * Updates an existing Machineoffarm model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * 
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionMachineoffarmupdate($id) {
		$model = $this->findModel ( $id );
		if($model->machine_id) {
			$lastclass = Machine::find ()->where ( [ 
					'id' => $model->machine_id 
			] )->one ()['machinetype_id'];
		} else {
			$lastclass = $model->machinetype_id;
		}
		// var_dump($lastclass);exit;
		$smallclass = Machinetype::find ()->where ( [ 
				'id' => $lastclass 
		] )->one ()['father_id'];
		$bigclass = Machinetype::find ()->where ( [ 
				'id' => $smallclass 
		] )->one ()['father_id'];
		$searchModel = new MachineSearch ();
		$params = Yii::$app->request->queryParams;
// 		if ($machine_id !== 0) {
// 			// var_dump($farms_id);exit;
// 			$machinename = Machine::find ()->where ( [
// 					'id' => $machine_id
// 			] )->one ();
// 			$model->farms_id = $farms_id;
// 			$model->machinename = $machinename ['productname'];
// 			$model->machine_id = $machine_id;
// 			$model->machinetype_id = $machinename ['machinetype_id'];
// 			$model->create_at = time ();
// 			$model->update_at = $model->create_at;
// 			$save = $model->save ();
// 			if ($save)
// 				return $this->redirect ( [
// 						'machineoffarmindex',
// 						'farms_id' => $model->farms_id
// 				] );
// 		}
		
		$post = Yii::$app->request->post ();
		if ($post) {
// 			var_dump($post);exit;
			if (isset ( $post ['lastclass'] ))
				$params ['MachineSearch'] ['machinetype_id'] = $post ['lastclass'];
			if (isset ( $post ['MachineSearch'] ['productname'] ))
				$params ['MachineSearch'] ['productname'] = $post ['MachineSearch'] ['productname'];
			if (isset ( $post ['MachineSearch'] ['implementmodel'] ))
				$params ['MachineSearch'] ['implementmodel'] = $post ['MachineSearch'] ['implementmodel'];
			if (isset ( $post ['MachineSearch'] ['filename'] ))
				$params ['MachineSearch'] ['filename'] = $post ['MachineSearch'] ['filename'];
			if (isset ( $post ['MachineSearch'] ['enterprisename'] ))
				$params ['MachineSearch'] ['enterprisename'] = $post ['MachineSearch'] ['enterprisename'];
			if (isset ( $post ['lastclass'] ))
				$lastclass = $post ['lastclass'];
			$smallclass = Machinetype::find ()->where ( [ 
					'id' => $lastclass 
			] )->one ()['father_id'];
			$bigclass = Machinetype::find ()->where ( [ 
					'id' => $smallclass 
			] )->one ()['father_id'];
			if ($model->load ( Yii::$app->request->post () )) {
				$save = false;
// 				var_dump(Yii::$app->request->post ());
// 				var_dump($_POST['Machineoffarm']['acquisitiontime']);exit;
				if (Yii::$app->request->post ('tempState') == 'update') {
					// var_dump($model);exit;
					$model->farms_id = $model->farms_id;
					$model->machinename = $model->machinename;
					$model->machinetype_id = $lastclass;
					$model->machine_id = $model->machine_id;
					$model->update_at = time ();
// 					$model->update_at = $model->create_at;
					$model->acquisitiontime = $_POST['Machineoffarm']['acquisitiontime'];
					$save = $model->save ();
					Logs::writeLogs('更新农场农机器具信息',$model);
// 					var_dump($model);exit;
				}
				if ($save)
					return $this->redirect ( [ 
							'machineoffarmindex',
							'farms_id' => $model->farms_id 
					] );
			}
		}
		$dataProvider = $searchModel->search ( $params );
		return $this->render ( 'machineoffarmupdate', [ 
				'model' => $model,
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'lastclass' => $lastclass,
				'smallclass' => $smallclass,
				'bigclass' => $bigclass,
				'farms_id' => $model->farms_id 
		] );
	}
	
	/**
	 * Deletes an existing Machineoffarm model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * 
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionMachineoffarmdelete($id,$farms_id) {
		$model = $this->findModel ( $id );
		$model->delete ();
		Logs::writeLogs('删除农场农机器具',$model);
		return $this->redirect ( [ 
				'machineoffarmindex',
				'farms_id' => $farms_id,
		] );
	}


	public function actionMachineoffarmsixdelete($id,$farms_id) {
		$model = $this->findModel ( $id );
		$model->delete ();
		Logs::writeLogs('删除农场农机器具',$model);
		return $this->redirect ( [
			'sixcheck/sixcheckindex',
			'farms_id' => $farms_id,
		] );
	}
	/**
	 * Finds the Machineoffarm model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * 
	 * @param integer $id        	
	 * @return Machineoffarm the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Machineoffarm::findOne ( $id )) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException ( 'The requested page does not exist.' );
		}
	}
}
