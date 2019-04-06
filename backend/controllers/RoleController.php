<?php

namespace backend\controllers;

use Yii;
use app\models\ItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Migration;
use yii\filters\AccessControl;
use yii\rbac\Role;
use yii\rbac\DbManager;
use app\models\Item;
use app\models\RoleForm;
use app\models\RoleSearch;
use app\models\ItemChild;
use app\models\PermissionForm;
/**
 * TablesController implements the CRUD actions for tables model.
 */
class RoleController extends Controller
{
    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['rolecreate', 'roleupdate', 'roleview', 'roleindex', 'roledelete','roleaddchild'],
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
	public function actionRolecreate()
	{
		$role = new Role();
		$model = new RoleForm();
		//$db = new DbManager();
		if ($model->load(Yii::$app->request->post()))
		{
		    $role->name = $model->name;
		    $role->type = $model->type;
		    //这里将权限添加到auth_item中
		    $bool = yii::$app->authManager->add($role);
		    if($bool)
		    	return $this->redirect(['roleindex']);    
	    	
	    } else {
	    	return $this->render('rolecreate', [
	    			'model' => $model,
	    	]);
	    }
	}
	
	public function actionRoleindex()
	{
		
		$searchModel = new RoleSearch();
				//print_r($data);
		$dataProvider = $searchModel->search(yii::$app->requestedParams);
		return $this->render('roleindex',[
				'dataProvider' => $dataProvider,
				'searchModel' => $searchModel,
		]);
	}
    
	public function actionRoleaddchild($id)
	{
		$model = new ItemChild();
		$item = $this->findModel($id);
		$model->parent = $item->name;
		$allController = PermissionForm::allClass();
// 		echo '<br><br><br><br><br><br>';
// 		var_dump(Yii::$app->request->post());
		//exit;
		$childpost = Yii::$app->request->post('childPost');
		if ($childpost)
		{
// 			var_dump($childpost);
// 			exit;
			$this->childaction($id, $item, $childpost);
				$searchModel = new RoleSearch();
				$dataProvider = $searchModel->search(yii::$app->requestedParams);
				return $this->render('roleindex',[
					'dataProvider' => $dataProvider,
					'searchModel' => $searchModel,
				]);
		} else {
			return $this->render('roleaddchild', [
					'model' => $model,
					'allController' => $allController,
			]);
		}
	}
	
	private function childaction($id,$item,$model)
	{
		$diff = [];
		$same = [];
		$del = [];
		$arroldchild = [];
		$arrnewchild = $model['child'];
		$oldchild = ItemChild::find()->where(['parent'=>$item->name])->all();
		if($oldchild) {
			foreach($oldchild as $val) {
				$arroldchild[] = $val['child'];
			}
			$diff = array_diff($arrnewchild,$arroldchild);
			$same = array_intersect($arrnewchild,$arroldchild);
			$del = array_diff($arroldchild,$same);
			
			if($diff) {
				 foreach($diff as $val) {
					 $parent = yii::$app->authManager->createRole($item->name);
					 $child = yii::$app->authManager->createPermission($val);
					 $bool = yii::$app->authManager->addChild($parent, $child);
				 }
			}
			if($del) {
				foreach($del as $val) {
					//$model = $this->findModel($id);
					$parent = yii::$app->authManager->createRole($item->name);
					$child = yii::$app->authManager->createPermission($val);
					yii::$app->authManager->removeChild($parent, $child);
				}
			}
		} else {
			foreach($arrnewchild as $val) {
				$parent = yii::$app->authManager->createRole($item->name);
				$child = yii::$app->authManager->createPermission($val);
				$bool = yii::$app->authManager->addChild($parent, $child);
			} 
		}
	} 
	
	public function actionRoleupdate($id)
	{
		
		$model = $this->findModel($id);
		$role = new Role();
		//$db = new DbManager();
		//print_r($permission);
		if ($model->load(Yii::$app->request->post()))
		{
		    $role->name = $model->name;
		    //$permission->type = $model->type;
		    //$permission->description = $model->description;
		   // $permission->rule_name = $model->rule_name;
		   // $permission->data = $model->data;
		    //这里将权限添加到auth_item中
		    $bool = yii::$app->authManager->update($id, $role);
		    if($bool)
		    	return $this->redirect(['roleview', 'id' => $model->name]);    
	    	
	    } else {
	    	return $this->render('roleupdate', [
	    			'model' => $model,
	    	]);
	    }
	}
	
	public function actionRoleview($id)
	{
		$model = $this->findModel($id);
		$child = ItemChild::find()->where(['parent'=>$model->name])->all();
		$arr = [];
		foreach($child as $val)
		{
			$arr[] = $val->child;
		}
		$childstr = implode(',', $arr);
		return $this->render('roleview', [
				'model' => $model,
				'childstr' => $childstr,
		]);
	}
	
	public function actionRoledelete($id)
	{
		$model = $this->findModel($id);
		$role = new Role();
		$role->name = $model->name;
		yii::$app->authManager->remove($role);
		
		return $this->redirect(['roleindex']);
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
