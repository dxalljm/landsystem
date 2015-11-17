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
    
//     public function beforeAction($action)
//     {
//     	$action = Yii::$app->controller->action->id;
//     	if(\Yii::$app->user->can($action)){
//     		return true;
//     	}else{
//     		throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
//     	}
//     }
    
	public function actionPermissioncreate($classname = null)
	{
		
		$model = new PermissionForm();
		//echo '<br><br><br><br><br><br><br>';
		$allClassInfo = PermissionForm::allClass();
		if($classname) {
			$actions = $this->getactions($classname);
			$model->rule_name = $classname;
		}
		else
			$actions = [];
		if ($model->load(Yii::$app->request->post()))
		{
			$itemPost = Yii::$app->request->post('itemPost');
			for($i=0;$i<count($itemPost['actionName']);$i++) {
				$permission = new Permission();
				$permission->name = $this->getActionName($itemPost['actionName'][$i]);
				$permission->description = $itemPost['description'][$i];
				$permission->ruleName = $model->rule_name;
				$permission->data = $model->data;
				$permission->type = $model->type;
				$permission->createdAt = time();
				$permission->updatedAt = time();
				//var_dump($permission);exit;
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
		$result = str_replace('action','',$action);
		return strtolower($result);
	}
	
	public function getactions($classname)
	{
		$allClassInfo = PermissionForm::allClass();
		$c = $allClassInfo[$classname]['path'].$allClassInfo[$classname]['classname'];
		//var_dump($classname);
		$actions = $c::actionName();
		return $actions;
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
