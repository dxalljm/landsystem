<?php

namespace backend\controllers;

use app\models\Department;
use app\models\Mainmenu;
use app\models\Userlevel;
use Yii;
use app\models\User;
use backend\models\SignupForm;
use backend\models\userSearch;
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

//    public function beforeAction($action)
//    {
//    	$action = Yii::$app->controller->action->id;
//    	if(\Yii::$app->user->can($action)){
//    		return true;
//    	}else{
//    		throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
//    	}
//    }
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

    /**
     * Updates an existing user model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUserupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $department = Department::findOne($model->department_id);
//            var_dump($model->department_id);
            $menu = Mainmenu::getLevelMenu($model->level);
            $role = explode(',',$department->menulist);
//            var_dump($menu);var_dump($role);
            $userMenu = array_intersect($role,$menu);
//            var_dump($userMenu);
            $plate = Yii::$app->request->post('plate');
            if($plate) {
                foreach ($plate as $item) {
                    $value = explode('-',$item);
                    $class = Mainmenu::find()->where(['id'=>$value[0]])->one()['class'];
                    $classarray = explode(',',$class);
                    foreach ($classarray as $classname) {
                        $tempmenu[$classname][] = $value[1];
                    }
                    foreach ($classarray as $classname) {
                        $tempPlate[$classname] = $classname.'-'.$value[0].'-'.implode(',',$tempmenu[$classname]);
                    }
                }

            }
            if(is_array($model->auditinguser)) {
                $model->auditinguser = implode(',',$model->auditinguser);
            }
//            var_dump($tempPlate);
            $model->plate = implode('/',$tempPlate);
            //如果没有审核权限则删除掉任务菜单
            if(empty($model->auditinguser)) {
                $k = false;
                foreach ($userMenu as $key => $menu) {
                    if($menu == 66 or $menu == 52)
                        $k[] = $key;
                }
                if($k) {
                    foreach ($k  as $v) {
                        unset($userMenu[$v]);
                    }
                }
            }
            //如果分配了业务菜单则增加业务办理菜单
            if($model->level > 2) {
	            if($department->businessmenu) {
	                if(!in_array(6,$userMenu))
	                    array_splice($userMenu,1,0,[6]);
	            }
            }
            if($model->level == 6) {
            	if(!in_array(66,$userMenu))
            		array_splice($userMenu,1,0,[66]);
            }
//            var_dump($userMenu);exit;
            $model->mainmenu = implode(',', $userMenu);
//            var_dump($model);exit;
            if($model->save())
                return $this->redirect(['userview', 'id' => $model->id]);
        } else {
            return $this->render('userupdate', [
                'model' => $model,
            ]);
        }
    }
    
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


    //用户配置向导
    public function actionUserconfigure($user_id)
    {
        $this->layout='@app/views/layouts/nomain.php';
        $model = User::findOne($user_id);
        $level = Userlevel::findOne($model->level);
        $department = Department::findOne($model->department_id);
//        var_dump($department);
        $result = [];
        $menu = Mainmenu::getLevelMenu($model->level);
        $role = Department::getControllerID($department->menulist);
        $userMenu = array_intersect($role,$menu);
        var_dump($userMenu);
        $plate = Yii::$app->request->post('plate');
        if($plate) {
            $menuarray = [];
            foreach ($plate as $item) {
                $value = explode('-',$item);
                $class = Mainmenu::find()->where(['id'=>$value[0]])->one()['class'];
                $classarray = explode(',',$class);
                foreach ($classarray as $classname) {
                    $tempmenu[$classname][] = $value[1];
                }
                foreach ($classarray as $classname) {
                    $model->plate = $classname.'-'.implode(',',$tempmenu[$classname]);
                }
            }
            $model->mainmenu = implode(',',$userMenu);
            $model->save();
            var_dump($model->getErrors());
        }
//        if ($model->load(Yii::$app->request->post())) {
//            var_dump('111');
//            var_dump(Yii::$app->request->post());
//        }
        return $this->render('userconfigure', [
            'model' => $model,
        ]);
    }

    public function actionUserconfigure1($user_id)
    {

    }

    public function actionUserconfigure2($user_id)
    {

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
