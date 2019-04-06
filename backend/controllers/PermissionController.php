<?php

namespace backend\controllers;

use Yii;
use app\models\PermissionForm;
use app\models\ItemSearch;
use app\models\PermissionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Migration;
use yii\filters\AccessControl;
use yii\rbac\Permission;
use yii\rbac\DbManager;
use app\models\Item;
use app\models\Tables;
/**
 * TablesController implements the CRUD actions for tables model.
 */
class PermissionController extends Controller
{
	
    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        //'actions' => ['permissioncreate', 'permissionupdate', 'permissionview', 'permissionindex', 'permissiondelete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
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
    
	public function actionPermissioncreate($classname = null)
	{
		
		$model = new PermissionForm();
		//echo '<br><br><br><br><br><br><br>';
		$allClassInfo = PermissionForm::allClass();
		if($classname) {
			$actionname = $this->getactions($classname);
			$actions = $this->setActionInfo($actionname, $classname);
			$model->cname = $classname;
			$model->classdescription = Tables::find()->where(['tablename'=>str_replace('Controller','',$classname)])->one()['Ctablename'];
		}
		else
			$actions = [];
		if ($model->load(Yii::$app->request->post()))
		{
//			var_dump(Tables::find()->where(['tablename'=>str_replace('Controller','',$classname)])->one()['Ctablename']);exit;
			$itemPost = Yii::$app->request->post('itemPost');
			for($i=0;$i<count($itemPost['actionName']);$i++) {
				$permission = new Permission();
				$permission->name = $this->getActionName($itemPost['actionName'][$i]);
				$permission->description = $itemPost['description'][$i];
				$permission->cname = $classname;
				$permission->classdescription = Tables::find()->where(['tablename'=>str_replace('Controller','',$classname)])->one()['Ctablename'];
				$permission->type = $model->type;
				$permission->createdAt = time();
				$permission->updatedAt = time();
				if(!empty($itemPost['description'][$i]))
					yii::$app->authManager->add($permission);
			}
		    
		    //这里将权限添加到auth_item中
		   
		    	return $this->redirect(['permissionindex']);  
	    	
	    } else {
	    	return $this->render('permissioncreate', [
	    			'model' => $model,
	    			'controllerAllDir' => $allClassInfo,
	    			'actions' => $actions,
	    			
	    	]);
	    }
	}
	
	public function getActionName($action)
	{
		if(is_array($action)) {
			foreach ($action as $value) {
				$str = str_replace('action','',$value);
				$result[] = strtolower($str);
			}
		} else {
			$str = str_replace('action','',$action);
			$result = strtolower($str);
		}
		return $result;
	}
	
	public function setActionInfo($action,$classname)
	{
		$tablename = str_replace('Controller','',$classname);
		$tabledesc = Tables::find()->where(['tablename'=>$tablename])->one()['Ctablename'];
		$actions = $this->getActionName($action); 
		foreach ($actions as $value) {
			$str = str_replace(strtolower($tablename),'',$value);
			//var_dump($str);
			switch ($str)
			{
				case 'index';
					$description = 	$tabledesc.'列表';
					break;
				case 'create';
					$description = 	'新增'.$tabledesc;
					break;
				case 'view';
					$description = 	'查看'.$tabledesc;
					break;
				case 'update';
					$description = 	'更新'.$tabledesc;
					break;
				case 'delete';
					$description = 	'删除'.$tabledesc;
					break;
				default:
					$description = '';
			}
			$result[] = [
					'action'=>$value,
					'description' => $description,
			];		
		}
		return $result;
	}
	
	public function getactions($classname)
	{
		$allClassInfo = PermissionForm::allClass();
		$c = $allClassInfo[$classname]['path'].$allClassInfo[$classname]['classname'];
		$actions = get_class_methods($c);
		return $actions;
	}
	
	public function actionJsonaname($classname)
	{
		$actions = $this->getactions($classname);
		foreach ($actions as $value) {
			$result[] = $this->getActionName($value);
		}
		echo json_encode(['status'=>1,'actions'=>$result]);
	}
	
	public function actionPermissionindex()
	{
		$searchModel = new PermissionSearch();
		
		$dataProvider = $searchModel->search(\Yii::$app->requestedParams);
		return $this->render('permissionindex',[
				'dataProvider' => $dataProvider,
				'searchModel' => $searchModel,
		]);
	}
    
	public function actionPermissionupdate($id)
	{
		
		$model = $this->findModel($id);
		$permission = new Permission();
		//$db = new DbManager();
		//print_r($permission);
		if ($model->load(Yii::$app->request->post()))
		{
		    $permission->name = $model->name;
		    //$permission->type = $model->type;
		    //$permission->description = $model->description;
		   // $permission->rule_name = $model->rule_name;
		   // $permission->data = $model->data;
		    //这里将权限添加到auth_item中
		    $bool = yii::$app->authManager->update($id, $permission);
		    if($bool)
		    	return $this->redirect(['permissionview', 'id' => $model->name]);    
	    	
	    } else {
	    	return $this->render('permissionupdate', [
	    			'model' => $model,
	    	]);
	    }
	}
	
	public function actionPermissionview($id)
	{
		return $this->render('permissionview', [
				'model' => $this->findModel($id),
		]);
	}
	
	public function actionPermissiondelete($id)
	{
		$model = $this->findModel($id);
		$permission = new Permission();
		$permission->name = $model->name;
		yii::$app->authManager->remove($permission);
		
		return $this->redirect(['permissionindex']);
	}
	
	
	protected function findModel($name)
	{
		if (($model = Item::findOne($name)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
