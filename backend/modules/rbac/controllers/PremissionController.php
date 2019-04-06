<?php

namespace app\modules\rbac\controllers;

use yii\web\Controller;
use yii\rbac\Role;

class PermissionController extends Controller
{
	public $manager;
	public function init()
	{
		parent::init();
		$this->manager = \Yii::$app->authManager;
	}
	
    public function actionIndex()
    {
        return $this->render('index');
    }
	
	public function actionCreate()
	{
		$role = new Role();
		$this->manager->add($role);
		return $this->render('create');
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
	}
				
	
}
