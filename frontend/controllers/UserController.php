<?php

namespace frontend\controllers;

use app\models\Indexecharts;
use app\models\Logs;
use Yii;
use app\models\User;
use frontend\models\SignupForm;
use frontend\models\userSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\RoleForm;
use app\models\AssignmentForm;

/**
 * UserController implements the CRUD actions for user model.
 */
class UserController extends Controller
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
     * Lists all user models.
     * @return mixed
     */
    public function actionUserindex()
    {
        $searchModel = new userSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('userindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single user model.
     * @param integer $id
     * @return mixed
     */
    public function actionUserview($id)
    {
    	//print_r($_GET['t']);
        return $this->render('userview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new user model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUsercreate()
    {
      	$model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                return $this->redirect(['userindex']); 
            }
        }

        return $this->render('usercreate', [
            'model' => $model,
        ]);
    }

    public function actionUseryear()
    {
    	$model = User::findOne(Yii::$app->getUser()->id);
    	if ($model->load(Yii::$app->request->post())) {
    		if($model->autoyear)
    			$model->year = date('Y');
    		$model->year = (string)$model->year;
    		$model->save();
//     		var_dump($model);
    	} 
	    return $this->render('useryear', [
	    		'model' => $model,
	    ]);
    }
    
    /**
     * Updates an existing user model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUserupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['userview', 'id' => $model->id]);
        } else {
            return $this->render('userupdate', [
                'model' => $model,
            ]);
        }
    }
    //为用户分配 角色
    public function actionUserassign($id)
    {
    	$assign = new AssignmentForm();
		return $this->renderAjax('userassign', [
				'user_id' => $id,
				'assign' => $assign,
		]);
    }

    /**
     * Deletes an existing user model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUserdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['userindex']);
    }

    public function actionModfiyuserinfo()
    {
    	$model = new SignupForm();
    	
    	if ($model->load(Yii::$app->request->post())) {
	    	if ($user = $model->signmod()) {
	            return $this->redirect(['site/index']); 
	        }
    	} else {
    		return $this->render('modfiyuserinfo', [
    				'model' => $model,
    		]);
    	}
    }

    public function actionUserindexecharts($user_id)
    {
        $indexecharts = Indexecharts::find()->where(['user_id'=>$user_id])->one();
        if($indexecharts) {
            $model = Indexecharts::findOne($indexecharts['id']);
        } else {
            $model = new Indexecharts();
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = $user_id;
            $model->save();
            Logs::writeLogs('更新用户首页图表',$model);
            return $this->redirect(['site/index']);
        }
    }

    public function actionModfiypassword($password)
    {
    	$model = new SignupForm();
		$model->signmodpass($password);
    }
    /**
     * Finds the user model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return user the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = user::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
