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
    
	public function actionPermissioncreate()
	{
		$permission = new Permission();
		$model = new PermissionForm();
		//echo '<br><br><br><br><br><br><br>';
		$allClassInfo = $this->allClass();
// 		$classname = 'backend\controllers\GroupsController';
// 		$actions = $classname::actionName();
		if ($model->load(Yii::$app->request->post()))
		{
		    $permission->name = $model->name;
		    $permission->type = $model->type;
		    //这里将权限添加到auth_item中
		    $bool = yii::$app->authManager->add($permission);
		    if($bool)
		    	return $this->redirect(['permissionview', 'id' => $model->name]);    
	    	
	    } else {
	    	return $this->render('permissioncreate', [
	    			'model' => $model,
	    			'controllerAllDir' => $allClassInfo,
	    	]);
	    }
	}
	
	public function getFile($dir,$path) {
		$fileArray[]=NULL;
		if (false != ($handle = opendir ( $dir ))) {
			$i=0;
			while ( false !== ($file = readdir ( $handle )) ) {
				//去掉"“.”、“..”以及带“.xxx”后缀的文件
				if ($file != "." && $file != ".."&&strpos($file,".")) {
					$fileArray[$i]="./imageroot/current/".$file;
					if($i==100){
						break;
					}
					$i++;
				}
			}
			//关闭句柄
			closedir ( $handle );
		}
		$i=0;
		foreach ($fileArray as $value) {
			$filenameArray = explode('/', $value);
			$strArray = explode('.',  $filenameArray[3]);
			$result[$strArray[0]] = [
					'classname' => $strArray[0],
					'path' => $path.'\\controllers\\',
			];
			$i++;
		}
		return $result;
	}
	
	public function allClass()
	{
		$backendControllerDir = $this->getFile('../controllers/','backend');
		$frontendControllerDir = $this->getFile('../../frontend/controllers/','frontend');
		$allClassInfo = array_merge($frontendControllerDir,$backendControllerDir);
		return $allClassInfo;
	}
	
	public function actionGetactions($id)
	{
		$allClassInfo = $this->allClass();
		$classname = $allClassInfo[$id]['path'].$allClassInfo[$id]['classname'];
		//var_dump($classname);
		$actions = $classname::actionName();
		echo json_encode(['data' => $actions]);
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
