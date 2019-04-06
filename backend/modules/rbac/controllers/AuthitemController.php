<?php 

namespace app\modules\rbac\controllers;

use app\models\AuthItem;

use yii\web\Controller;
use yii\rbac\Role;
use backend\models\authItemSearch;

class AuthitemController extends Controller
{
	public $manager;
	public function init()
	{
		parent::init();
		$this->manager = \Yii::$app->authManager;
	}
	
    public function actionIndex()
    {
    	$searchModel = new authItemSearch();
    	$dataProvider = $searchModel->search(\Yii::$app->requestedParams);
        return $this->render('index',[
        		'dataProvider' => $dataProvider,
        		'searchModel' => $searchModel,
        ]);
    }
	
	public function actionCreate()
	{
	
		$model = new Role();
		
		if ($model->load(\Yii::$app->requestedParams)) {
			$this->manager->add($model);
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
					'model' => $model,
			]);
		}
	}
	
	public function actionUpdate()
	{
		$role = new Role();
		$rolename = '';
		$this->manager->update($rolename, $role);
		
		$model = new yii\rbac\Item();
		
		if($model->load(Yii::$app->request->post())) {
			if($model->validate()) {
				
				return;
			}
		}
		return $this->render('role/update',[
			'model' => $model,
		]);
	}
	
	public function actionDelete()
	{
		$role = new Role();
		$this->manager->remove($role);
		//return $this->render('delete');
	}
				
	
}
