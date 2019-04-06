<?php

namespace backend\controllers;

use Yii;
use app\models\assignmentForm;
use backend\models\assignmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\userSearch;
use app\models\User;
/**
 * AssignmentController implements the CRUD actions for assignmentForm model.
 */
class AssignmentController extends Controller
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
     * Lists all assignmentForm models.
     * @return mixed
     */
    public function actionAssignmentindex($id)
    {
   		$model = new AssignmentForm();
    	if ($model->load(Yii::$app->request->post())) {
    		$auth =  yii::$app->authManager;
    		if($_POST['assignment-button'] == 'create') {
    			if($auth->getAssignment($model->item_name, $id)) {
    				return $this->render('error', [
    						'message' => '该角色已存在，不能重复提交！',
    				]);
    			} else {
		    		$getrole = $auth->getRole($model->item_name);
		    		$role = $auth->createRole($model->item_name);
		    		$auth->assign($role, $id);
    			}	
    		}
    		if($_POST['assignment-button'] == 'delete') {
    			if($auth->getAssignment($model->item_name, $id)) {
	    			$role = $auth->getRole($model->item_name);
	    			$auth->revoke($role, $id);
    			}
    			else {
    				return $this->render('error', [
    						'message' => '该角色不存在，无法操作！',
    				]);
    			}
    		}
    		return $this->redirect(['user/userindex']); 
    	}
    	else {
			return $this->renderAjax('assignmentindex', [
					'user_id' => $id,
					'model' => $model,
			]);
    	}
    }

    public function error()
	{
		$errors = ['该角色已存在，不能重复提交！'];
		return $this->redirect(['assignerror']);
	}
	
    protected function findModel($item_name, $user_id)
    {
        if (($model = assignmentForm::findOne(['item_name' => $item_name, 'user_id' => $user_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
