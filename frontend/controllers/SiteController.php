<?php
namespace frontend\controllers;

use app\models\Lockstate;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use frontend\models\SignupForm;
use app\models\Logs;
use app\models\User;
use app\models\Department;
use app\models\Theyear;
use app\models\ManagementArea;
use app\models\Farms;
use frontend\models\collectionSearch;
use frontend\models\tempprintbillSearch;

use app\models\Tempauditing;
use frontend\helpers\MacAddress;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'signup'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }
//    public function beforeAction($action)
//    {
////        var_dump(Yii::$app->user->isGuest);
//        if(Yii::$app->user->isGuest) {
//            return $this->redirect(['site/login']);
//        }
//    }
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionMainiframe()
    {
    	return $this->render('mainiframe');
    }

    public function actionIndex()
    {

        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        }
//        var_dump(Lockstate::getSystemLockState());exit;
        if(Lockstate::getSystemLockState()) {
            $this->layout='@app/views/layouts/main2.php';
            return $this->render('locked');
        }
    	Tempauditing::tempauditingIsExpire();
	    Logs::writeLogs('访问首页');
	    $user = User::find()->where(['id'=>yii::$app->getUser()->id])->one();

	    $dep_id = $user['department_id'];
	    $departmentData = Department::find()->where(['id'=>$dep_id])->one();
	    $arrayDepartment = explode(',',$departmentData['membership']);
	    $management = ManagementArea::find()->where(['id' =>$arrayDepartment])->all();
//        var_dump($management);
		if(is_array($management)) {
               $arrayResult = [];
			foreach($management as $value) {
				$arrayResult[] = $value['areaname'];
			}
			$result = implode(',',$arrayResult);
		}
		else
			$result = $management;
        $searchModel = new collectionSearch();
        $params = Yii::$app->request->queryParams;
        $whereArray = Farms::getManagementArea()['id'];
        if (empty($params['collectionSearch']['management_area'])) {
            $params ['collectionSearch'] ['management_area'] = $whereArray;
        }
        $params['collectionSearch']['payyear'] = Theyear::getYear();
        $dataProvider = $searchModel->searchIndex ( $params );

        if (is_array($searchModel->management_area)) {
            $searchModel->management_area = null;
        }

	    return $this->render('index',[
	       'areaname' => $result,
	      	'user' => $user,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
	    ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            Logs::writeLogs('登录失败');
            return $this->goHome();
        }

        $model = new LoginForm();
        $mac = new MacAddress();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
        	$userModel = User::findOne(\Yii::$app->getUser()->id);
//        	if(\Yii::$app->getUser()->getIdentity()['autoyear']) {
        		if($userModel->year !== date('Y')) {
        			$userModel->year = date('Y');        			
        			
        		}        		
//        	}
        	if($userModel->ip == '') {
        		$userModel->ip = Yii::$app->getRequest()->getUserIP();
        	}
        	if($userModel->mac == '') {
        		$userModel->mac = $mac->getMac();
        	}
//         	var_dump($userModel);exit;
        	$userModel->save();
        	if($model->username == 'admin')
        		throw new \yii\web\UnauthorizedHttpException('对不起，此用户不能在前台页面登录。');
        	Logs::writeLogs(Yii::$app->user->identity->realname.'登录成功');
            	return $this->goBack();
        } else {
            $this->layout='@app/views/layouts/main2.php';
            Logs::writeLogs('用户登录');
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    
    
    public function actionLogout()
    {
    	Logs::writeLog('用户退出');
        Yii::$app->user->logout();

        return $this->goHome();
    }
	
	public function actionHome()
    {

        return $this->render('home');
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }
        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
