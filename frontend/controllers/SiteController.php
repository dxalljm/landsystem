<?php
namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use frontend\models\SignupForm;
use app\models\Logs;
use app\models\User;
use app\models\Department;
use app\models\Parcel;
use app\models\ManagementArea;
use app\models\Farms;
use app\models\Collection;
use frontend\models\tempprintbillSearch;
use frontend\helpers\Pinyin;


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

    public function actionIndex()
    {
//       echo Pinyin::encode('杨淑华');

//       exit;
    	if(\Yii::$app->user->identity->username == 'cwk01') {
    		Logs::writeLog('票据打印');
    		$searchModel = new tempprintbillSearch();
    		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    		return $this->redirect(['tempprintbill/tempprintbillindex', 'searchModel' => $searchModel,'dataProvider'=>$dataProvider]);
    		
    	} else {
	    	Logs::writeLog('访问首页');
	    	$user = User::find()->where(['id'=>yii::$app->getUser()->id])->one();
	    	$dep_id = $user['department_id'];
	    	$departmentData = Department::find()->where(['id'=>$dep_id])->one();
	    	$arrayDepartment = explode(',',$departmentData['membership']);
	    	$management = ManagementArea::find()->where(['id' =>$arrayDepartment])->all();
// 	    	var_dump($management);
// 	    	exit;
			if(is_array($management)) {
                $arrayResult = [];
				foreach($management as $value) {
					$arrayResult[] = $value['areaname'];
				}
				$result = implode(',',$arrayResult);	
			}				
			else 
				$result = $management;
	        $areaname = [];
	        return $this->render('index',[
	            'areaname' => $result,
	        	'user' => $user,
	        ]);
    	}
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
        	if($model->username == 'admin')
        		throw new \yii\web\UnauthorizedHttpException('对不起，此用户不能在前台页面登录。');
        	
            	return $this->goBack();
        } else {
            $this->layout='@app/views/layouts/main2.php';
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
