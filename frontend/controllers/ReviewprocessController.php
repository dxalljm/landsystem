<?php

namespace frontend\controllers;

use app\models\Backprocess;
use app\models\Collection;
use app\models\Farmer;
use app\models\Farmerinfo;
use app\models\Farmermembers;
use app\models\Fireprevention;
use app\models\Logs;
use app\models\Numberlock;
use app\models\Plantingstructure;
use app\models\Plantingstructureyearfarmsidplan;
use app\models\PlantPrice;
use app\models\Theyear;
use frontend\models\farmsSearch;
use frontend\models\loanSearch;
use Yii;
use app\models\Reviewprocess;
use frontend\models\ReviewprocessSearch;
use frontend\models\ReviewprocessfSearch;
use frontend\models\ReviewprocesspSearch;
use frontend\models\ReviewprocesslSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use app\models\Auditprocess;
use app\models\User;
use app\models\Projectapplication;
use app\models\Tempauditing;
use app\models\Loan;
use app\models\Ttpozongdi;
use app\models\Estate;
use app\models\Processname;
use app\models\Lockedinfo;
use app\models\Zongdioffarm;
/**
 * ReviewprocessController implements the CRUD actions for Reviewprocess model.
 */
class ReviewprocessController extends Controller
{
	public $cookies = null;

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

	private function setcookies()
	{
		$this->cookies = Yii::$app->response->cookies;
	}

	public function beforeAction($action)
	{
     	if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		} else {
			return true;
		}
	}
    /**
     * Lists all Reviewprocess models.
     * @return mixed
     */
    public function actionReviewprocessindex($begindate=null,$enddate=null)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
		$params = Yii::$app->request->queryParams;
		$dataFarmstransfer = [];
		$searchFarmstransfer = [];
		$searchProject = [];
		$dataProject = [];
		$dataLoan = [];
		$searchLoan = [];
		if(empty($begindate))
			$begindate = Theyear::getYeartime()[0];
		else {
			$begindate = strtotime($begindate.' 00:00:01');
//     		$params['collectionSearch']['state'] = 1;
		}
		if(empty($enddate))
			$enddate = Theyear::getYeartime()[1];
		else
			$enddate = strtotime($enddate.' 23:59:59');
//     	var_dump($begindate);exit;
		$params['begindate'] = $begindate;
		$params['enddate'] = $enddate;
    	$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
		if($temp) {
			$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
			$processname = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
			if(isset($_GET['ReviewprocessfSearch']['management_area']) and !empty($_GET['ReviewprocessfSearch']['management_area'])) {
				$whereArrayf = [$_GET['ReviewprocessfSearch']['management_area']];
			} else {
				$whereArrayf = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesslSearch']['management_area']) and !empty($_GET['ReviewprocesslSearch']['management_area'])) {
				$whereArrayl = [$_GET['ReviewprocesslSearch']['management_area']];
			} else {
				$whereArrayl = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesspSearch']['management_area']) and !empty($_GET['ReviewprocesspSearch']['management_area'])) {
				$whereArrayp = [$_GET['ReviewprocesspSearch']['management_area']];
			} else {
				$whereArrayp = Farms::getManagementArea()['id'];
			}

		} else {
			$processname = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
			if(isset($_GET['ReviewprocessfSearch']['management_area']) and !empty($_GET['ReviewprocessfSearch']['management_area'])) {
				$whereArrayf = [$_GET['ReviewprocessfSearch']['management_area']];
			} else {
				$whereArrayf = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesslSearch']['management_area']) and !empty($_GET['ReviewprocesslSearch']['management_area'])) {
				$whereArrayl = [$_GET['ReviewprocesslSearch']['management_area']];
			} else {
				$whereArrayl = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesspSearch']['management_area']) and !empty($_GET['ReviewprocesspSearch']['management_area'])) {
				$whereArrayp = [$_GET['ReviewprocesspSearch']['management_area']];
			} else {
				$whereArrayp = Farms::getManagementArea()['id'];
			}

		}
//		var_dump($processname);exit;
		if(Auditprocess::isAuditing('承包经营权转让')) {
			$searchFarmstransfer = new ReviewprocessfSearch();

			$params['ReviewprocessfSearch']['state'] = 4;
			$params['ReviewprocessfSearch']['management_area'] = $whereArrayf;
			$params['ReviewprocessfSearch']['actionname'] = 'farmstransfer';
			$audit = Processname::getAuditprocess('farmstransfer');
//			var_dump($processname);exit;
			$proc = array_intersect($processname,$audit);
//			var_dump($proc);exit;
			foreach ($proc as $proces) {
				$params['ReviewprocessfSearch'][$proces] = 2;
			}
			$dataFarmstransfer = $searchFarmstransfer->searchFarmstransfer($params);
		}
		if(Auditprocess::isAuditing('项目审核')) {
			$searchProject = new ReviewprocesspSearch();

			$params['ReviewprocesspSearch']['state'] = 4;
			$params['ReviewprocesspSearch']['management_area'] = $whereArrayp;
			$params['ReviewprocesspSearch']['actionname'] = 'projectapplication';
			$audit = Processname::getAuditprocess('projectapplication');
			$proc = array_intersect($processname,$audit);
			foreach ($proc as $proces) {
				$params['ReviewprocesspSearch'][$proces] = 2;
			}
			$dataProject = $searchProject->searchProject($params);
		}
		if(Auditprocess::isAuditing('贷款冻结审批')) {
			$searchLoan = new ReviewprocesslSearch();
			$params['ReviewprocesslSearch']['state'] = 4;
			$params['ReviewprocesslSearch']['management_area'] = $whereArrayl;
			$params['ReviewprocesslSearch']['actionname'] = 'loancreate';
			$audit = Processname::getAuditprocess('loancreate');
			$proc = array_intersect($processname,$audit);
//			var_dump($proc);exit;
			foreach ($proc as $proces) {
				$params['ReviewprocesslSearch'][$proces] = 2;
			}
//			var_dump($params);exit;
			$dataLoan = $searchLoan->searchLoan($params);
		}

       	$title = '审核任务';
		Logs::writeLogs('审核任务');
        return $this->render('reviewprocessindex', [
			'dataFarmstransfer' => $dataFarmstransfer,
			'searchFarmstransfer' => $searchFarmstransfer,
			'searchProject' => $searchProject,
			'dataProject' => $dataProject,
        	'dataLoan' => $dataLoan,
			'searchLoan' => $searchLoan,
			'title' => $title,
			'params' => $_POST,
			'begindate' => $params['begindate'],
            'enddate' => $params['enddate'],
        ]);
    }

	public function actionReviewprocessreturn($begindate=null,$enddate=null)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
		$params = Yii::$app->request->queryParams;

		$dataFarmstransfer = [];
		$searchFarmstransfer = [];
		$searchProject = [];
		$dataProject = [];
		$dataLoan = [];
		$searchLoan = [];
		if(empty($begindate))
			$begindate = Theyear::getYeartime()[0];
		else {
			$begindate = strtotime($begindate.' 00:00:01');
//     		$params['collectionSearch']['state'] = 1;
		}
		if(empty($enddate))
			$enddate = Theyear::getYeartime()[1];
		else
			$enddate = strtotime($enddate.' 23:59:59');
//     	var_dump($begindate);exit;
		$params['begindate'] = $begindate;
		$params['enddate'] = $enddate;
		$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
		if($temp) {
			$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
			$processname = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
			if(isset($_GET['ReviewprocessfSearch']['management_area']) and !empty($_GET['ReviewprocessfSearch']['management_area'])) {
				$whereArrayf = [$_GET['ReviewprocessfSearch']['management_area']];
			} else {
				$whereArrayf = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesslSearch']['management_area']) and !empty($_GET['ReviewprocesslSearch']['management_area'])) {
				$whereArrayl = [$_GET['ReviewprocesslSearch']['management_area']];
			} else {
				$whereArrayl = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesspSearch']['management_area']) and !empty($_GET['ReviewprocesspSearch']['management_area'])) {
				$whereArrayp = [$_GET['ReviewprocesspSearch']['management_area']];
			} else {
				$whereArrayp = Farms::getManagementArea()['id'];
			}

		} else {
			$processname = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
			if(isset($_GET['ReviewprocessfSearch']['management_area']) and !empty($_GET['ReviewprocessfSearch']['management_area'])) {
				$whereArrayf = [$_GET['ReviewprocessfSearch']['management_area']];
			} else {
				$whereArrayf = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesslSearch']['management_area']) and !empty($_GET['ReviewprocesslSearch']['management_area'])) {
				$whereArrayl = [$_GET['ReviewprocesslSearch']['management_area']];
			} else {
				$whereArrayl = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesspSearch']['management_area']) and !empty($_GET['ReviewprocesspSearch']['management_area'])) {
				$whereArrayp = [$_GET['ReviewprocesspSearch']['management_area']];
			} else {
				$whereArrayp = Farms::getManagementArea()['id'];
			}

		}
		if(Auditprocess::isAuditing('承包经营权转让')) {
			$searchFarmstransfer = new ReviewprocessfSearch();

			$params['ReviewprocessfSearch']['state'] = 8;
			$params['ReviewprocessfSearch']['management_area'] = $whereArrayf;
			$params['ReviewprocessfSearch']['actionname'] = 'farmstransfer';
			$audit = Processname::getAuditprocess('farmstransfer');
			$proc = array_intersect($processname,$audit);
			foreach ($proc as $proces) {
				$params['ReviewprocessfSearch'][$proces] = 8;
			}
			$dataFarmstransfer = $searchFarmstransfer->searchFarmstransfer($params);
		}
		if(Auditprocess::isAuditing('项目审核')) {
			$searchProject = new ReviewprocesspSearch();

			$params['ReviewprocesspSearch']['state'] = 8;
			$params['ReviewprocesspSearch']['management_area'] = $whereArrayp;
			$params['ReviewprocesspSearch']['actionname'] = 'projectapplication';
			$audit = Processname::getAuditprocess('projectapplication');
			$proc = array_intersect($processname,$audit);
			foreach ($proc as $proces) {
				$params['ReviewprocesspSearch'][$proces] = 8;
			}
			$dataProject = $searchProject->searchProject($params);
		}
		if(Auditprocess::isAuditing('贷款冻结审批')) {
			$searchLoan = new ReviewprocesslSearch();
			$params['ReviewprocesslSearch']['state'] = 8;
			$params['ReviewprocesslSearch']['management_area'] = $whereArrayl;
			$params['ReviewprocesslSearch']['actionname'] = 'loancreate';
			$audit = Processname::getAuditprocess('loancreate');
			$proc = array_intersect($processname,$audit);
			foreach ($proc as $proces) {
				$params['ReviewprocesslSearch'][$proces] = 8;
			}
			$dataLoan = $searchLoan->searchLoan($params);
		}
		$title = '退回任务';
		Logs::writeLogs($title);
		return $this->render('reviewprocessindex', [
			'dataFarmstransfer' => $dataFarmstransfer,
			'searchFarmstransfer' => $searchFarmstransfer,
			'searchProject' => $searchProject,
			'dataProject' => $dataProject,
			'dataLoan' => $dataLoan,
			'searchLoan' => $searchLoan,
			'title' => $title,
			'params' => $_POST,
			'begindate' => $params['begindate'],
            'enddate' => $params['enddate'],
		]);
	}

	public function actionReviewprocesswait()
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
		$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
		if($temp) {
			$whereArray = Farms::getUserManagementArea($temp['user_id']);
		} else {
			$whereArray = Farms::getManagementArea();
		}
		$ttpozongdi = Ttpozongdi::find()->where(['state'=>0,'management_area'=>$whereArray['id']])->all();
//		var_dump($whereArray['id']);exit;
		$projectapplication = Reviewprocess::find()->where(['management_area'=>$whereArray['id'],'actionname'=>'projectapplication'])->all();

		$loan = Reviewprocess::find()->where(['management_area' => $whereArray['id'],'actionname'=>'loancreate'])->all();
		$title = '待办任务';
		Logs::writeLogs($title);
		return $this->render('reviewprocesswait', [
			'ttpozongdi' => $ttpozongdi,
			'projectapplication' => $projectapplication,
			'loan' => $loan,
			'title' => $title,
		]);
	}

	public function actionReviewprocessing($begindate=null,$enddate=null)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
		$params = Yii::$app->request->queryParams;
		$dataFarmstransfer = [];
		$searchFarmstransfer = [];
		$searchProject = [];
		$dataProject = [];
		$dataLoan = [];
		$searchLoan = [];
		if(empty($begindate))
			$begindate = Theyear::getYeartime()[0];
		else {
			$begindate = strtotime($begindate.' 00:00:01');
//     		$params['collectionSearch']['state'] = 1;
		}
		if(empty($enddate))
			$enddate = Theyear::getYeartime()[1];
		else
			$enddate = strtotime($enddate.' 23:59:59');
//     	var_dump($begindate);exit;
		$params['begindate'] = $begindate;
		$params['enddate'] = $enddate;
		$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
		if($temp) {
			$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
			$processname = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
			if(isset($_GET['ReviewprocessfSearch']['management_area']) and !empty($_GET['ReviewprocessfSearch']['management_area'])) {
				$whereArrayf = [$_GET['ReviewprocessfSearch']['management_area']];
			} else {
				$whereArrayf = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesslSearch']['management_area']) and !empty($_GET['ReviewprocesslSearch']['management_area'])) {
				$whereArrayl = [$_GET['ReviewprocesslSearch']['management_area']];
			} else {
				$whereArrayl = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesspSearch']['management_area']) and !empty($_GET['ReviewprocesspSearch']['management_area'])) {
				$whereArrayp = [$_GET['ReviewprocesspSearch']['management_area']];
			} else {
				$whereArrayp = Farms::getManagementArea()['id'];
			}

		} else {
//			$processname = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
			if(isset($_GET['ReviewprocessfSearch']['management_area']) and !empty($_GET['ReviewprocessfSearch']['management_area'])) {
				$whereArrayf = [$_GET['ReviewprocessfSearch']['management_area']];
			} else {
				$whereArrayf = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesslSearch']['management_area']) and !empty($_GET['ReviewprocesslSearch']['management_area'])) {
				$whereArrayl = [$_GET['ReviewprocesslSearch']['management_area']];
			} else {
				$whereArrayl = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesspSearch']['management_area']) and !empty($_GET['ReviewprocesspSearch']['management_area'])) {
				$whereArrayp = [$_GET['ReviewprocesspSearch']['management_area']];
			} else {
				$whereArrayp = Farms::getManagementArea()['id'];
			}
		}
//		var_dump($_GET);exit;
		if(Auditprocess::isAuditing('承包经营权转让')) {
			$searchFarmstransfer = new ReviewprocessfSearch();
			$params['ReviewprocessfSearch']['state'] = 4;
			$params['ReviewprocessfSearch']['management_area'] = $whereArrayf;
			$params['ReviewprocessfSearch']['actionname'] = 'farmstransfer';
			$dataFarmstransfer = $searchFarmstransfer->searchFarmstransfer($params);
		}
		if(Auditprocess::isAuditing('项目审核')) {
			$searchProject = new ReviewprocesspSearch();
			$params['ReviewprocesspSearch']['state'] = 4;
			$params['ReviewprocesspSearch']['management_area'] = $whereArrayp;
			$params['ReviewprocesspSearch']['actionname'] = 'projectapplication';
			$dataProject = $searchProject->searchProject($params);
		}
		if(Auditprocess::isAuditing('贷款冻结审批')) {
			$searchLoan = new ReviewprocesslSearch();
			$params['ReviewprocesslSearch']['state'] = 4;
			$params['ReviewprocesslSearch']['management_area'] = $whereArrayl;
			$params['ReviewprocesslSearch']['actionname'] = 'loancreate';
			$dataLoan = $searchLoan->searchLoan($params);
		};
		$title = '正在办理';
		Logs::writeLogs($title);
		return $this->render('reviewprocessindex', [
			'dataFarmstransfer' => $dataFarmstransfer,
        	'searchProject' => $searchProject,
			'dataProject' => $dataProject,
			'searchFarmstransfer' => $searchFarmstransfer,
        	'dataLoan' => $dataLoan,
			'searchLoan' => $searchLoan,
			'title' => $title,
			'params' => $_POST,
			'begindate' => $params['begindate'],
            'enddate' => $params['enddate'],
		]);
	}

	public function actionReviewprocessfinished($begindate=null,$enddate=null)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
		$params = Yii::$app->request->queryParams;
		$dataFarmstransfer = [];
		$searchFarmstransfer = [];
		$searchProject = [];
		$dataProject = [];
		$dataLoan = [];
		$searchLoan = [];
		if(empty($begindate))
			$begindate = Theyear::getYeartime()[0];
		else {
			$begindate = strtotime($begindate.' 00:00:01');
//     		$params['collectionSearch']['state'] = 1;
		}
		if(empty($enddate))
			$enddate = Theyear::getYeartime()[1];
		else
			$enddate = strtotime($enddate.' 23:59:59');
//     	var_dump($begindate);exit;
		$params['begindate'] = $begindate;
		$params['enddate'] = $enddate;
		$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
		if($temp) {
			$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
			$processname = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
			if(isset($_GET['ReviewprocessfSearch']['management_area']) and !empty($_GET['ReviewprocessfSearch']['management_area'])) {
				$whereArrayf = [$_GET['ReviewprocessfSearch']['management_area']];
			} else {
				$whereArrayf = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesslSearch']['management_area']) and !empty($_GET['ReviewprocesslSearch']['management_area'])) {
				$whereArrayl = [$_GET['ReviewprocesslSearch']['management_area']];
			} else {
				$whereArrayl = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesspSearch']['management_area']) and !empty($_GET['ReviewprocesspSearch']['management_area'])) {
				$whereArrayp = [$_GET['ReviewprocesspSearch']['management_area']];
			} else {
				$whereArrayp = Farms::getManagementArea()['id'];
			}

		} else {
			$processname = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
//			var_dump(Farms::getManagementArea());exit;
			if(isset($_GET['ReviewprocessfSearch']['management_area']) and !empty($_GET['ReviewprocessfSearch']['management_area'])) {
				$whereArrayf = [$_GET['ReviewprocessfSearch']['management_area']];
			} else {
				$whereArrayf = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesslSearch']['management_area']) and !empty($_GET['ReviewprocesslSearch']['management_area'])) {
				$whereArrayl = [$_GET['ReviewprocesslSearch']['management_area']];
			} else {
				$whereArrayl = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesspSearch']['management_area']) and !empty($_GET['ReviewprocesspSearch']['management_area'])) {
				$whereArrayp = [$_GET['ReviewprocesspSearch']['management_area']];
			} else {
				$whereArrayp = Farms::getManagementArea()['id'];
			}
		}
//		var_dump($whereArray);exit;
		if(Auditprocess::isAuditing('承包经营权转让')) {
			$searchFarmstransfer = new ReviewprocessfSearch();

			$params['ReviewprocessfSearch']['state'] = [6,7];
			$params['ReviewprocessfSearch']['management_area'] = $whereArrayf;
			$params['ReviewprocessfSearch']['actionname'] = 'farmstransfer';
			$dataFarmstransfer = $searchFarmstransfer->searchFarmstransfer($params);
		}
		if(Auditprocess::isAuditing('项目审核')) {
			$searchProject = new ReviewprocesspSearch();

			$params['ReviewprocesspSearch']['state'] = 7;
			$params['ReviewprocesspSearch']['management_area'] = $whereArrayp;
			$params['ReviewprocesspSearch']['actionname'] = 'projectapplication';
			$dataProject = $searchProject->searchProject($params);
		}
		if(Auditprocess::isAuditing('贷款冻结审批')) {
			$searchLoan = new ReviewprocesslSearch();
			$params['ReviewprocesslSearch']['state'] = 7;
// 			var_dump($_GET);exit;
			$params['ReviewprocesslSearch']['management_area'] = $whereArrayl;
			$params['ReviewprocesslSearch']['actionname'] = 'loancreate';
			$dataLoan = $searchLoan->searchLoan($params);
		}
		$title = '已完成任务';
		Logs::writeLogs($title);
		return $this->render('reviewprocessindex', [
			'dataFarmstransfer' => $dataFarmstransfer,
			'searchProject' => $searchProject,
			'dataProject' => $dataProject,
			'searchFarmstransfer' => $searchFarmstransfer,
			'dataLoan' => $dataLoan,
			'searchLoan' => $searchLoan,
			'title' => $title,
			'params' => $_POST,
			'begindate' => $params['begindate'],
            'enddate' => $params['enddate'],
		]);
	}

	public function actionReviewprocesscacle($begindate = null,$enddate = null)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
		$params = Yii::$app->request->queryParams;
		$dataFarmstransfer = [];
		$searchFarmstransfer = [];
		$searchProject = [];
		$dataProject = [];
		$dataLoan = [];
		if(empty($begindate))
			$begindate = Theyear::getYeartime()[0];
		else {
			$begindate = strtotime($begindate.' 00:00:01');
//     		$params['collectionSearch']['state'] = 1;
		}
		if(empty($enddate))
			$enddate = Theyear::getYeartime()[1];
		else
			$enddate = strtotime($enddate.' 23:59:59');
//     	var_dump($begindate);exit;
		$params['begindate'] = $begindate;
		$params['enddate'] = $enddate;
		$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
		if($temp) {
			$userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
			$processname = Processname::getProcessname($userinfo['department_id'],$userinfo['level']);
			if(isset($_GET['ReviewprocessfSearch']['management_area']) and !empty($_GET['ReviewprocessfSearch']['management_area'])) {
				$whereArrayf = [$_GET['ReviewprocessfSearch']['management_area']];
			} else {
				$whereArrayf = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesslSearch']['management_area']) and !empty($_GET['ReviewprocesslSearch']['management_area'])) {
				$whereArrayl = [$_GET['ReviewprocesslSearch']['management_area']];
			} else {
				$whereArrayl = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesspSearch']['management_area']) and !empty($_GET['ReviewprocesspSearch']['management_area'])) {
				$whereArrayp = [$_GET['ReviewprocesspSearch']['management_area']];
			} else {
				$whereArrayp = Farms::getManagementArea()['id'];
			}

		} else {
			$processname = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
			if(isset($_GET['ReviewprocessfSearch']['management_area']) and !empty($_GET['ReviewprocessfSearch']['management_area'])) {
				$whereArrayf = [$_GET['ReviewprocessfSearch']['management_area']];
			} else {
				$whereArrayf = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesslSearch']['management_area']) and !empty($_GET['ReviewprocesslSearch']['management_area'])) {
				$whereArrayl = [$_GET['ReviewprocesslSearch']['management_area']];
			} else {
				$whereArrayl = Farms::getManagementArea()['id'];
			}
			if(isset($_GET['ReviewprocesspSearch']['management_area']) and !empty($_GET['ReviewprocesspSearch']['management_area'])) {
				$whereArrayp = [$_GET['ReviewprocesspSearch']['management_area']];
			} else {
				$whereArrayp = Farms::getManagementArea()['id'];
			}

		}
		if(Auditprocess::isAuditing('承包经营权转让')) {
			$searchFarmstransfer = new ReviewprocessfSearch();

			$params['ReviewprocessfSearch']['state'] = 9;
			$params['ReviewprocessfSearch']['management_area'] = $whereArrayf;
			$params['ReviewprocessfSearch']['actionname'] = 'farmstransfer';
//			foreach ($processname as $proces) {
//				$params['ReviewprocessSearch'][$proces] = 0;
//			}
			$dataFarmstransfer = $searchFarmstransfer->searchFarmstransfer($params);
		}
		if(Auditprocess::isAuditing('项目审核')) {
			$searchProject = new ReviewprocesspSearch();

			$params['ReviewprocesspSearch']['state'] = 9;
			$params['ReviewprocesspSearch']['management_area'] = $whereArrayp;
			$params['ReviewprocesspSearch']['actionname'] = 'projectapplication';
//			foreach ($processname as $proces) {
//				$params['ReviewprocessSearch'][$proces] = 0;
//			}
			$dataProject = $searchProject->searchProject($params);
		}
		if(Auditprocess::isAuditing('贷款冻结审批')) {
			$searchLoan = new ReviewprocesslSearch();
			$params['ReviewprocesslSearch']['state'] = 9;
			$params['ReviewprocesslSearch']['management_area'] = $whereArrayl;
			$params['ReviewprocesslSearch']['actionname'] = 'loancreate';
//			foreach ($processname as $proces) {
//				$params['ReviewprocessSearch'][$proces] = 0;
//			}
			$dataLoan = $searchLoan->searchLoan($params);
		}
		$title = '被撤消任务';
		Logs::writeLogs($title);
		return $this->render('reviewprocessindex', [
			'dataFarmstransfer' => $dataFarmstransfer,
			'searchProject' => $searchProject,
			'dataProject' => $dataProject,
			'searchFarmstransfer' => $searchFarmstransfer,
			'dataLoan' => $dataLoan,
			'searchLoan' => $searchLoan,
			'title' => $title,
			'params' => $_POST,
			'begindate' => $params['begindate'],
			'enddate' => $params['enddate'],
		]);
	}

	public function actionReviewprocesscontractclaim()
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
		$searchModel = new ReviewprocessSearch();
		$params = Yii::$app->request->queryParams;
		$whereArray = Farms::getManagementArea();
		if (empty($params['ReviewprocessSearch']['management_area'])) {
			$params ['ReviewprocessSearch'] ['management_area'] = $whereArray['id'];
		}
        $params['ReviewprocessSearch']['state'] = [6,7];
		$params['ReviewprocessSearch']['actionname'] = 'farmstransfer';
//		$params['ReviewprocessSearch']['state'] = 6;
		$dataProvider = $searchModel->search($params);

//		$Identification = Processname::find()->where(['rolename'=>User::getItemname()])->one();
//		$farmstransfer = Reviewprocess::find()->where(['management_area'=>$whereArray['id'],'actionname'=>'farmstransfer','state'=>6])->all();
		Logs::writeLogs('合同领取');
		return $this->render('reviewprocesscontractclaim', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionReviewprocessclaim($id,$time)
	{
		$model = $this->findModel($id);
		$model->state = 7;
		if($time == date('Y-m-d'))
			$model->receivetime = time();
		else
			$model->receivetime = strtotime($time);
		if($model->save()) {
			$farmModel = Farms::findOne($model->newfarms_id);
			$farmModel->surveydate = $model->receivetime;
			$farmModel->save();
			Logs::writeLogs('合同领取完成',$farmModel);
		}
// 		var_dump($model->getErrors());exit;
		return $this->render('reviewprocesscontractclaim');
	}

    /**
     * Displays a single Reviewprocess model.
     * @param integer $id
     * @return mixed
     */
  
    public function actionReviewprocessfarmstransfer($oldfarmsid,$newfarmsid,$reviewprocessid)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
//     	var_dump($reviewprocessid);
    	$model = new Reviewprocess();
    	$newfarm = Farms::find()->where(['id'=>$newfarmsid])->one();
    	$oldfarm = Farms::find()->where(['id'=>$oldfarmsid])->one();
    	$newttpozongdi = Ttpozongdi::find()->where(['newfarms_id'=>$newfarmsid])->one();
    	$oldttpozongdi = Ttpozongdi::find()->where(['oldfarms_id'=>$oldfarmsid])->one();
    	$reviewprocess = Reviewprocess::find()->where(['id'=>$reviewprocessid])->one();
    	$process = Auditprocess::find()->where(['id'=>$reviewprocess['operation_id']])->one()['process'];
//     	var_dump($process);exit;
		Logs::writeLogs('宜农林地承包经营权转让审批表',$model);
    	return $this->render ( 'reviewprocessfarmstransfer', [
    			'oldfarm' => $oldfarm,
    			'newfarm' => $newfarm,
    			'oldttpozongdi' => $oldttpozongdi,
    			'newttpozongdi' => $newttpozongdi,
    			'process' => explode('>', $process),
    			'model' => $model,
    			'reviewprocessid' => $reviewprocessid
    	] );
    }

    public function actionReviewprocessloancreate($farms_id,$reviewprocessid)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
    	//     	var_dump($reviewprocessid);
    	$model = new Reviewprocess();
    	$newfarm = Farms::find()->where(['id'=>$model->newfarmsid])->one();
    	$oldfarm = Farms::find()->where(['id'=>$model->oldfarmsid])->one();
    	$newttpozongdi = Ttpozongdi::find()->where(['newfarms_id'=>$model->newfarmsid])->one();
    	$oldttpozongdi = Ttpozongdi::find()->where(['oldfarms_id'=>$model->oldfarmsid])->one();
    	$reviewprocess = Reviewprocess::find()->where(['id'=>$reviewprocessid])->one();
    	$process = Auditprocess::find()->where(['id'=>$reviewprocess['operation_id']])->one()['process'];
    	//     	var_dump($process);exit;
    	return $this->render ( 'reviewprocessfarmstransfer', [
    			'oldfarm' => $oldfarm,
    			'newfarm' => $newfarm,
    			'oldttpozongdi' => $oldttpozongdi,
    			'newttpozongdi' => $newttpozongdi,
    			'process' => explode('>', $process),
    			'model' => $model,
    			'reviewprocessid' => $reviewprocessid
    	] );
    }
    
    public function actionReviewprocessfarmssplit($reviewprocessid)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
    	$model = Reviewprocess::findOne($reviewprocessid);
    	$ttpoModel = Ttpozongdi::find()->where(['id'=>$model->ttpozongdi_id])->one();
    	$process = Auditprocess::find()->where(['id'=>$model->operation_id])->one()['process'];
    	$oldfarm = Farms::find()->where(['id'=>$model->oldfarms_id])->one();
    	$newfarm = Farms::find()->where(['id'=>$model->newfarms_id])->one();
		Logs::writeLogs('打印宜农林地承包经营权转让审批表',$ttpoModel);
    	return $this->render ( 'reviewprocessfarmssplit', [
    			'oldfarm' => $oldfarm,
    			'newfarm' => $newfarm,
    			'ttpoModel' => $ttpoModel,
    			'process' => explode('>', $process),
     			'model' => $model,
    	] );
    }
    
    public function actionReviewprocessfarmstozongdi($newfarmsid,$oldfarmsid,$reviewprocessid)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
    	$model = new Reviewprocess();
    	$newfarm = Farms::find()->where(['id'=>$newfarmsid])->one();
    	$oldfarm = Farms::find()->where(['id'=>$oldfarmsid])->one();
    	$newttpozongdi = Ttpozongdi::find()->where(['newfarms_id'=>$newfarmsid])->one();
    	$oldttpozongdi = Ttpozongdi::find()->where(['oldfarms_id'=>$oldfarmsid])->one();
    	$reviewprocess = Reviewprocess::find()->where(['id'=>$reviewprocessid])->one();
    	$process = Auditprocess::find()->where(['id'=>$reviewprocess['operation_id']])->one()['process'];
//     	var_dump($newfarm);
    	return $this->render ( 'reviewprocessfarmstozongdi', [
    			'oldfarm' => $oldfarm,
    			'newfarm' => $newfarm,
    			'oldttpozongdi' => $oldttpozongdi,
    			'newttpozongdi' => $newttpozongdi,
    			'process' => explode('>', $process),
    			'model' => $model,
    	] );
    }
    
    public function actionReviewprocessinspections($id,$class)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
		$state = '';
    	$model = $this->findModel($id);
//		var_dump($model);exit;
		$oldmodel = $model->attributes;
		$nowundo = $oldmodel['undo'];
		$fromundo = $oldmodel['fromundo'];
    	$ttpo = Ttpozongdi::find()->where(['reviewprocess_id'=>$id])->one();
    	$ttpoModel = Ttpozongdi::findOne($ttpo['id']);
    	$process = Auditprocess::find()->where(['actionname'=>$model->actionname])->one()['process'];
		$post = Yii::$app->request->post();
    	if($class == 'farmstransfer') {
			Logs::writeLogs('过户转让');
	    	$oldfarm = Farms::find()->where(['id'=>$ttpo->oldfarms_id])->one();
	    	$newfarm = Farms::find()->where(['id'=>$ttpo->newnewfarms_id])->one();

	    	if($post) {
// 	    		exit;
	    		$modelname = Reviewprocess::getProcessIdentification('farmstransfer');
	    		
	    		foreach ($modelname as $name) {
					$state = Reviewprocess::isNextProcess($model->id,$name,$post);
		    		$classname = 'app\\models\\'.ucfirst($name);
					$agreefield = $name.'isAgree';
		    		$estateID= $classname::find()->where(['reviewprocess_id'=>$id])->one()['id'];
		    		if($estateID)
		    			$estateModel = $classname::findOne($estateID);
		    		else
		    			$estateModel = new $classname();
					if($name == 'leader' or $name == 'project') {
						$estateModel->$agreefield = Yii::$app->request->post($agreefield);
						$agreefieldcontent = $agreefield . 'content';
						if (Yii::$app->request->post($agreefield))
							$estateModel->$agreefieldcontent = Yii::$app->request->post($agreefieldcontent);
					} else {
						foreach ($classname::attributesList() as $key => $value) {
							$estateModel->$key = Yii::$app->request->post($key);
							$contentName = $key . 'content';
							$estateModel->$contentName = Yii::$app->request->post($contentName);
							$estateModel->$agreefield = Yii::$app->request->post($agreefield);
							$agreefieldcontent = $agreefield . 'content';
							if (Yii::$app->request->post($agreefield))
								$estateModel->$agreefieldcontent = Yii::$app->request->post($agreefieldcontent);
						}
					}
		    		$estateModel->reviewprocess_id = $id;
					if(!$estateModel->$agreefield) {
						$undo = Yii::$app->request->post($name.'undo');
//						$model->$undo = 8;
//						$model->state = 8;
//						$model->undo = $undo;
//						$model->fromundo = $name;
//						$model->save();
						$field = $name.'undo';
						$estateModel->$field = $undo;
						$content = $agreefield.'content';
						$estateModel->$content = Yii::$app->request->post($content);
					}
		    		$estateModel->save();
	    		}

// 	    		var_dump($state);exit;
	    		if($state == 6) {
// 	    			var_dump($model->oldfarms_id);exit;
					if($ttpoModel->oldfarms_id) {
//						var_dump($ttpoModel->oldfarms_id);exit;
						$oldfarmsModel = Farms::findOne($ttpoModel->oldfarms_id);
						$oldfarmsModel->update_at = time();
// 						$oldfarmsModel->zongdi = $ttpoModel->oldchangezongdi;
// 						$oldfarmsModel->measure = $ttpoModel->oldchangemeasure;
// 						$oldfarmsModel->notclear = $ttpoModel->oldchangenotclear;
// 						$oldfarmsModel->notstate = $ttpoModel->oldchangenotstate;
// 						$oldfarmsModel->contractarea = Farms::getContractnumberArea($ttpoModel->oldchangecontractnumber);
// 						$oldfarmsModel->contractnumber = $ttpoModel->oldchangecontractnumber;
						if(Reviewprocess::scanSameFarmsid($model->samefarms_id,$model->year)) {
							$oldfarmsModel->state = 0;
							$oldfarmsModel->locked = 0;
						}

						if(date('Y',$oldfarmsModel->create_at) == date('Y')) {
							$oldfarmsModel->nowyearstate = 1;
						} else {
							$oldfarmsModel->nowyearstate = -1;
						}
						if(Ttpozongdi::isSplit($id)) {
							if($id == Ttpozongdi::getSameFirstID($id))
								$oldfarmsModel->otherstate = $state;
							else
								$oldfarmsModel->otherstate = 0;
						} else
							$oldfarmsModel->otherstate = $state;

						$oldfarmsModel->save();
						Logs::writeLogs('注销'.$oldfarmsModel->farmname.'农场',$oldfarmsModel);
						$lockedinfo = Lockedinfo::find()->where(['farms_id' => $ttpoModel->oldfarms_id])->one();
						if ($lockedinfo) {
							$lockedinfoModel = Lockedinfo::findOne($lockedinfo['id']);
							$lockedinfoModel->delete();
						}
						Numberlock::deleteLock($ttpoModel->oldfarms_id);
//						$collections = Collection::find()->where(['farms_id'=>$ttpoModel->oldfarms_id])->all();
//						foreach ($collections as $collection) {
//							if ($collection['state'] == 1) {
//								$collectionModel = new Collection();
//								$collectionModel->create_at = time();
//								$collectionModel->update_at = $collectionModel->create_at;
//								$collectionModel->payyear = $collection['payyear'];
//								$collectionModel->farms_id = $ttpoModel->oldfarms_id;
//								$collectionModel->amounts_receivable = 0.0;
//								$collectionModel->real_income_amount = 0.0;
//								$collectionModel->ypayarea = 0.0;
//								$collectionModel->ypaymoney = 0.0;
//								$collectionModel->owe = 0.0;
//								$collectionModel->measure = 0.0;
//								$collectionModel->ypayyear = $collection['ypayyear'];
//								$collectionModel->dckpay = 1;
//								$collectionModel->state = 1;
//								$collectionModel->management_area = $collection['management_area'];
//								$collectionModel->save();
//							} else {
//								$collectionModel = new Collection();
//								$collectionModel->create_at = time();
//								$collectionModel->update_at = $collection->create_at;
//								$collectionModel->payyear = $collection['payyear'];
//								$collectionModel->farms_id = $oldfarmsModel->id;
//								$collectionModel->amounts_receivable = $collection['amounts_receivable'];
//								$collectionModel->real_income_amount = $collection['real_income_amount'];
//								$collectionModel->ypayarea = $collection['ypayarea'];
//								$collectionModel->ypaymoney = $collection['ypaymoney'];
//								$collectionModel->owe = $collection['owe'];
//								$collectionModel->measure = $collection['measure'];
//								$collectionModel->ypayyear = $collection['payyear'];
//								$collectionModel->dckpay = $collection['dckpay'];
//								$collectionModel->state = $collection['state'];
//								$collectionModel->management_area = $collection['management_area'];
//								$collectionModel->save();
//							}
//						}
					}
					if($ttpoModel->newfarms_id) {
//						var_dump($ttpoModel->newfarms_id);exit;
						$newfarmModel = Farms::findOne($ttpoModel->newfarms_id);
						$newfarmModel->update_at = time();
// 						$newfarmModel->zongdi = $ttpoModel->newchangezongdi;
// 						$newfarmModel->measure = $ttpoModel->newchangemeasure;
// 						$newfarmModel->notclear = $ttpoModel->newchangenotclear;
// 						$newfarmModel->notstate = $ttpoModel->newchangenotstate;
// 						$newfarmModel->contractarea = Farms::getContractnumberArea($ttpoModel->newchangecontractnumber);
// 						$newfarmModel->contractnumber = $ttpoModel->newchangecontractnumber;

						if(Reviewprocess::scanSameFarmsid($model->samefarms_id,$model->year)) {
							$newfarmModel->state = 0;
							$newfarmModel->locked = 0;
						}
						if(date('Y',$newfarmModel->create_at) == date('Y')) {
							$newfarmModel->nowyearstate = 0;
						}
//						if($ttpoModel->newfarms_id == Ttpozongdi::getSameFirstID($id)) {
						$newfarmModel->otherstate = $state;
//						}
						$newfarmModel->save();
						Logs::writeLogs('注销'.$newfarmModel->farmname.'农场',$newfarmModel);
//						var_dump($newfarmModel->getErrors());exit;
//						if($ttpoModel->oldfarms_id) {
//							$oldfarmsModel = Farms::findOne($ttpoModel->oldfarms_id);
//							$oldfarmsModel->update_at = time();
//							$oldfarmsModel->state = 0;
//							$oldfarmsModel->locked = 0;
//							if(Ttpozongdi::isSplit($id)) {
//								if($id == Ttpozongdi::getSameFirstID($id))
//									$oldfarmsModel->otherstate = $state;
//								else
//									$oldfarmsModel->otherstate = 0;
//							} else
//								$oldfarmsModel->otherstate = $state;
//
//							$oldfarmsModel->save();
//						}
						$lockedinfo = Lockedinfo::find()->where(['farms_id'=>$ttpoModel->newfarms_id])->one();
						if($lockedinfo) {
							$lockedinfoModel = Lockedinfo::findOne($lockedinfo['id']);
							$lockedinfoModel->delete();
						}
//						$collections = Collection::find()->where(['farms_id'=>$newfarmModel->id])->all();
//						foreach ($collections as $collection) {
//							if ($collection['state'] == 1) {
//								$collectionModel = new Collection();
//								$collectionModel->create_at = time();
//								$collectionModel->update_at = $collectionModel->create_at;
//								$collectionModel->payyear = $collection['payyear'];
//								$collectionModel->farms_id = $newfarmModel->id;
//								$collectionModel->amounts_receivable = 0.0;
//								$collectionModel->real_income_amount = 0.0;
//								$collectionModel->ypayarea = 0.0;
//								$collectionModel->ypaymoney = 0.0;
//								$collectionModel->owe = 0.0;
//								$collectionModel->measure = 0.0;
//								$collectionModel->ypayyear = $collection['ypayyear'];
//								$collectionModel->dckpay = 1;
//								$collectionModel->state = 1;
//								$collectionModel->management_area = $collection['management_area'];
//								$collectionModel->save();
//							} else {
//								$collectionModel = new Collection();
//								$collectionModel->create_at = time();
//								$collectionModel->update_at = $collection->create_at;
//								$collectionModel->payyear = $collection['payyear'];
//								$collectionModel->farms_id = $newfarmModel->id;
//								$collectionModel->amounts_receivable = $collection['amounts_receivable'];
//								$collectionModel->real_income_amount = $collection['real_income_amount'];
//								$collectionModel->ypayarea = $collection['ypayarea'];
//								$collectionModel->ypaymoney = $collection['ypaymoney'];
//								$collectionModel->owe = $collection['owe'];
//								$collectionModel->measure = $collection['measure'];
//								$collectionModel->ypayyear = $collection['payyear'];
//								$collectionModel->dckpay = $collection['dckpay'];
//								$collectionModel->state = $collection['state'];
//								$collectionModel->management_area = $collection['management_area'];
//								$collectionModel->save();
//							}
//						}
					}

					if($ttpoModel->oldnewfarms_id) {
						
						$farms = Farms::findOne($ttpoModel->oldnewfarms_id);
							$farms->update_at = (int)time();
							if($farms->tempdata) {
								$farms->state = 0;
							} else {
								if(Reviewprocess::scanSameFarmsid($model->samefarms_id,$model->year)) {
									$farms->state = 1;
								}
							}
						if(Reviewprocess::scanSameFarmsid($model->samefarms_id,$model->year)) {
							$farms->locked = 0;
						}
							$farms->otherstate = 6;
							if(date('Y',$farms->create_at) == date('Y')) {
								$farms->nowyearstate = 1;
							}
//						var_dump($farms);exit;
							if ($farms->save()) {
//								var_dump($farms->getErrors());exit;
								Logs::writeLogs($farms->farmname.'农场更新数据',$farms);
								$farminfo = Farmerinfo::find()->where(['cardid'=>$farms['cardid']])->count();
								if(!$farminfo) {
									$farmerinfoModel = new Farmerinfo();
									$farmerinfoModel->cardid = $farms['cardid'];
									$farmerinfoModel->create_at = time();
									$farmerinfoModel->update_at = $farmerinfoModel->create_at;
									$farmerinfoModel->save();
									Logs::writeLogs('新建法人信息表数据',$farmerinfoModel);
								}
								Zongdioffarm::zongdiUpdate($ttpoModel->oldfarms_id, $ttpoModel->oldnewfarms_id, $ttpoModel->oldchangezongdi);
//								$collection = Collection::find()->where(['farms_id' => $ttpoModel->oldfarms_id,'payyear'=>date('Y')])->one();
//								foreach ($collections as $collection) {
//										if ($collection['state'] == 1) {
//											$collectionModel = new Collection();
//											$collectionModel->create_at = time();
//											$collectionModel->update_at = $collectionModel->create_at;
//											$collectionModel->payyear = $collection['payyear'];
//											$collectionModel->farms_id = $ttpoModel->oldnewfarms_id;
//											$collectionModel->amounts_receivable = 0.0;
//											$collectionModel->real_income_amount = 0.0;
//											$collectionModel->ypayarea = 0.0;
//											$collectionModel->ypaymoney = 0.0;
//											$collectionModel->owe = 0.0;
//											$collectionModel->measure = 0.0;
//											$collectionModel->ypayyear = $collection['ypayyear'];
//											$collectionModel->dckpay = 1;
//											$collectionModel->state = 1;
//											$collectionModel->management_area = $collection['management_area'];
//											$collectionModel->save();
////										var_dump($collectionModel->getErrors());exit;
//										} else {
//											$collectionModel = new Collection();
//											$collectionModel->create_at = time();
//											$collectionModel->update_at = $collection->create_at;
//											$collectionModel->payyear = $collection['payyear'];
//											$collectionModel->farms_id = $ttpoModel->oldnewfarms_id;
//											$collectionModel->amounts_receivable = $collectionModel->getAR(date('Y'), $farms['id']);;
//											$collectionModel->real_income_amount = 0.0;
//											$collectionModel->ypayarea = $farms['contractarea'];
//											$collectionModel->ypaymoney = bcmul($farms['contractarea'],$prices = PlantPrice::find()->where(['years'=>date('Y')])->one()['price'], 2);
//											$collectionModel->owe = 0.0;
//											$collectionModel->measure = 0.0;
//											$collectionModel->dckpay = 0;
//											$collectionModel->state = 0;
//											$collectionModel->save();
//										}
									}
//								}
//								if ($collection['state'] == 1) {
//									$collectionModel = new Collection();
//									$collectionModel->create_at = time();
//									$collectionModel->update_at = $collectionModel->create_at;
//									$collectionModel->payyear = date('Y');
//									$collectionModel->farms_id = $farms->id;
//									$collectionModel->amounts_receivable = 0.0;
//									$collectionModel->ypayarea = 0.0;
//									$collectionModel->ypaymoney = 0.0;
//									$collectionModel->owe = 0.0;
//									$collectionModel->dckpay = 0;
//									$collectionModel->state = 1;
//									$collectionModel->management_area = $farms->management_area;
//									$collectionModel->save();
//								} else {
//									$collectionModel = new Collection();
//									$collectionModel->create_at = time();
//									$collectionModel->update_at = $collectionModel->create_at;
//									$collectionModel->payyear = date('Y');
//									$collectionModel->farms_id = $farms->id;
//									$collectionModel->amounts_receivable = $collectionModel->getAR(date('Y'), $farms->id);
//									$collectionModel->ypayarea = $farms->contractarea;
//									$collectionModel->ypaymoney = $collectionModel->amounts_receivable;
//									$collectionModel->owe = 0.0;
//									$collectionModel->dckpay = 0;
//									$collectionModel->state = 0;
//									$collectionModel->management_area = $farms->management_area;
//									$collectionModel->save();
//								}
							}
//						}
//						$farmer = Farmerinfo::find()->where(['cardid'=>$farms->cardid])->one();
//						$farmerID = Farmer::find()->where(['farms_id'=>$ttpoModel->oldnewfarms_id])->one()['id'];
//						var_dump($farmer);exit;
//						$farmerMember = Farmermembers::find()->where(['farmer_id'=>$farmerID])->all();
//						$farmerModel = new Farmer();
//						$farmerModel->farms_id = $ttpoModel->oldnewfarms_id;
//						$farmerModel->isupdate = 1;
//						$farmerModel->farmerbeforename = $farmer['farmerbeforename'];
//						$farmerModel->nickname = $farmer['nickname'];
//						$farmerModel->gender = $farmer['gender'];
//						$farmerModel->nation = $farmer['nation'];
//						$farmerModel->political_outlook = $farmer['political_outlook'];
//						$farmerModel->cultural_degree = $farmer['cultural_degree'];
//						$farmerModel->domicile = $farmer['domicile'];
//						$farmerModel->nowlive = $farmer['nowlive'];
//						$farmerModel->living_room = $farmer['living_room'];
//						$farmerModel->photo = $farmer['photo'];
//						$farmerModel->cardpic = $farmer['cardpic'];
//						$farmerModel->years = date('Y');
//						$farmerModel->create_at = (string)time();
//						$farmerModel->update_at = $farmerModel->create_at;
//						$farmerModel->cardpicback = $farmer['cardpicback'];
//						$farmerModel->state = 1;
//						$farmerModel->save();
//						foreach ($farmerMember as $value) {
//							$farmerMemberModel = new Farmermembers();
//							$farmerMemberModel->farmer_id = $farmerModel->id;
//							$farmerMemberModel->relationship = $value['relationship'];
//							$farmerMemberModel->membername = $value['membername'];
//							$farmerMemberModel->cardid = $value['cardid'];
//							$farmerMemberModel->remarks = $value['remarks'];
//							$farmerMemberModel->isupdate = 1;
//							$farmerMemberModel->create_at = (string)time();
//							$farmerMemberModel->update_at = $farmerMemberModel->create_at;
//							$farmerMemberModel->save();
//						}


	    			if($ttpoModel->newnewfarms_id) {
						$farms = Farms::findOne($ttpoModel->newnewfarms_id);
//						var_dump($farms);exit;
						$farms->update_at = (int)time();
						if($farms->tempdata) {
							$farms->state = 0;
						} else {
							if (Reviewprocess::scanSameFarmsid($model->samefarms_id, $model->year)) {
								$farms->state = 1;
							}
						}
						if (Reviewprocess::scanSameFarmsid($model->samefarms_id, $model->year)) {
							$farms->locked = 0;
						}
						$farms->otherstate = 6;
						if(date('Y',$farms->create_at) == date('Y')) {
							$farms->nowyearstate = 1;
						}
						if($farms->save()) {
//							var_dump($farms->getErrors());exit;
							Logs::writeLogs('新创建农场-'.$farms->farmname.'-更新状态',$farms);
							$farminfo = Farmerinfo::find()->where(['cardid'=>$farms['cardid']])->count();
							if(!$farminfo) {
								$farmerinfoModel = new Farmerinfo();
								$farmerinfoModel->cardid = $farms['cardid'];
								$farmerinfoModel->create_at = time();
								$farmerinfoModel->update_at = $farmerinfoModel->create_at;
								$farmerinfoModel->save();
								Logs::writeLogs('新建法人信息数据',$farmerinfoModel);
							}
							Zongdioffarm::zongdiUpdate($ttpoModel->newfarms_id, $ttpoModel->newnewfarms_id, $ttpoModel->ttpozongdi);

//							$oldcollection = Collection::find()->where(['farms_id' => $ttpoModel->oldfarms_id,'payyear'=>date('Y')])->one();
							switch ($ttpoModel->actionname) {
								case 'farmstransfer':	//整体过户
									$old = Collection::find()->where(['farms_id'=>$ttpoModel->oldfarms_id,'payyear'=>date('Y')])->one();
									if($old) {
										if ($old['state'] == 1) {
											$collModel = Collection::findOne($old['id']);
											Collection::copy($ttpoModel->newnewfarms_id, $collModel, true);
										} else {
											$collModel = Collection::findOne($old['id']);
											Collection::copy($ttpoModel->newnewfarms_id, $collModel);
											$collModel->delete();
										}
									} else {
										Collection::newCollection($ttpoModel->newnewfarms_id);
									}
									$fireOld = Fireprevention::find()->where(['farms_id'=>$ttpoModel->oldfarms_id,'year'=>date('Y')])->one();
									if(empty($fireOld['percent'])) {
										Fireprevention::newFire($ttpoModel->newnewfarms_id);
										if($fireOld)
											$fireOld->delete();
									} else {
										$fireOld->farmstate = 0;
										$fireOld->save();
									}
									$plan = Collection::find()->where(['farms_id'=>$ttpoModel->oldfarms_id,'year'=>User::getYear()])->one();
									Plantingstructure::deletePlanting($ttpoModel->oldfarms_id);
									if($plan['isfinished'] == 0) {
										if($plan)
											$plan->delete();
										Plantingstructureyearfarmsidplan::newPlan($ttpoModel->newnewfarms_id);
									}
									break;
								case 'farmssplit':	//部分新建
									$old = Collection::find()->where(['farms_id'=>$ttpoModel->oldfarms_id,'payyear'=>date('Y')])->one();
									if($old['state'] == 1) {
										$collModel = Collection::findOne($old['id']);
										Collection::copy($ttpoModel->oldnewfarms_id,$collModel,true);
										Collection::copy($ttpoModel->newnewfarms_id,$collModel,true);
									} else {
										//如果没交费就新建交费数据
										Collection::newCollection($ttpoModel->oldnewfarms_id);
										Collection::newCollection($ttpoModel->newnewfarms_id);
									}
									$fireOld = Fireprevention::find()->where(['farms_id'=>$ttpoModel->oldfarms_id,'year'=>date('Y')])->one();
									if(empty($fireOld['percent'])) {
										Fireprevention::newFire($ttpoModel->oldnewfarms_id);
										$fireOld->delete();
										Fireprevention::newFire($ttpoModel->newnewfarms_id);
									} else {
										Fireprevention::copy($fireOld['id'],$ttpoModel->oldnewfarms_id);
										$fireOld->farmstate = 0;
										$fireOld->save();
									}
									Plantingstructure::deletePlanting($ttpoModel->oldfarms_id);
									$plan = Plantingstructureyearfarmsidplan::find()->where(['farms_id'=>$ttpoModel->oldfarms_id,'year'=>User::getYear()])->one();
									if($plan['isfinished'] == 0) {
										$plan->delete();
										Plantingstructureyearfarmsidplan::newPlan($ttpoModel->oldnewfarms_id);
										Plantingstructureyearfarmsidplan::newPlan($ttpoModel->newnewfarms_id);
									} else {
										Plantingstructureyearfarmsidplan::newPlan($ttpoModel->oldnewfarms_id,1);
										Plantingstructureyearfarmsidplan::newPlan($ttpoModel->newnewfarms_id);
									}
									break;
								case 'farmstransfermergecontract':	//整体合并
									$old = Collection::find()->where(['farms_id'=>$ttpoModel->oldfarms_id,'payyear'=>date('Y')])->one();
									$new = Collection::find()->where(['farms_id'=>$ttpoModel->newfarms_id,'payyear'=>date('Y')])->one();
									Collection::newCollection2($ttpoModel->newnewfarms_id,$old,$new);

									$fireOld = Fireprevention::find()->where(['farms_id'=>$ttpoModel->oldfarms_id,'year'=>date('Y')])->one();
									$fireNew = Fireprevention::find()->where(['farms_id'=>$ttpoModel->newfarms_id,'year'=>date('Y')])->one();
									if(empty($fireOld['percent']) and empty($fireNew['percent'])) {
										Fireprevention::newFire($ttpoModel->newnewfarms_id);
										$fireOld->delete();
									}
									if(!empty($fireOld['percent']) and empty($fireNew['percent'])) {
										Fireprevention::copy($fireOld['id'],$ttpoModel->newnewfarms_id);
										$fireOld->farmstate = 0;
										$fireOld->save();
										$fireNew->delete();
									}
									if(empty($fireOld['percent'] and empty($fireNew['percent']))) {
										Fireprevention::copy($fireNew['id'],$ttpoModel->newnewfarms_id);
										$fireOld->delete();
										$fireNew->farmstate = 0;
										$fireNew->save();
									}
									if(!empty($fireOld['percent']) and !empty($fireNew['percent'])) {
										Fireprevention::copy($fireNew['id'],$ttpoModel->newnewfarms_id);
										$fireOld->farmstate = 0;
										$fireOld->save();
										$fireNew->farmstate = 0;
										$fireNew->save();
									}
									Plantingstructure::deletePlanting($ttpoModel->oldfarms_id);
									$planold = Plantingstructureyearfarmsidplan::find()->where(['farms_id'=>$ttpoModel->oldfarms_id,'year'=>User::getYear()])->one();
									$plannew = Plantingstructureyearfarmsidplan::find()->where(['farms_id'=>$ttpoModel->newfarms_id,'year'=>User::getYear()])->one();
									if($planold['isfinished'] == 0) {
										$old = 1;
										$planold->delete();
									}
									if($plannew['isfinished'] == 0) {
										$new = 1;
										$plannew->delete();
									}
									if($old and $new) {
										Plantingstructureyearfarmsidplan::newPlan($ttpoModel->newnewfarms_id);
									} else {
										Plantingstructureyearfarmsidplan::newPlan($ttpoModel->newnewfarms_id,1);
									}
									break;
								case 'farmstozongdi':	//部分合并
									if(Collection::iscq($ttpoModel->oldfarms_id,date('Y'))) {
										$old = Collection::find()->where(['farms_id'=>$ttpoModel->oldfarms_id,'payyear'=>date('Y')])->one();
										$model = Collection::findOne($old['id']);
										$model->delete();
									} else {
										$old = false;
									}
									if(Collection::iscq($ttpoModel->newfarms_id,date('Y'))) {
										$new = Collection::find()->where(['farms_id'=>$ttpoModel->newfarms_id,'payyear'=>date('Y')])->one();
										$model = Collection::findOne($new['id']);
										$model->delete();
									} else {
										$new = false;
									}
									if($old or $new) {
										Collection::newCollection2($ttpoModel->newnewfarms_id, $old, $new);
									}

									$fireOld = Fireprevention::find()->where(['farms_id'=>$ttpoModel->oldfarms_id,'year'=>date('Y')])->one();
									Fireprevention::copy($fireOld['id'],$ttpoModel->oldnewfarms_id);
									$fireOld->farmstate = 0;
									$fireOld->save();
									$fireOld2 = Fireprevention::find()->where(['farms_id'=>$ttpoModel->newfarms_id,'year'=>date('Y')])->one();
									Fireprevention::copy($fireOld2['id'],$ttpoModel->newnewfarms_id);
									$fireOld2->farmstate = 0;
									$fireOld2->save();
									Plantingstructure::deletePlanting($ttpoModel->oldfarms_id);
									$planold = Plantingstructureyearfarmsidplan::find()->where(['farms_id'=>$ttpoModel->oldfarms_id,'year'=>User::getYear()])->one();
									$plannew = Plantingstructureyearfarmsidplan::find()->where(['farms_id'=>$ttpoModel->newfarms_id,'year'=>User::getYear()])->one();
									if($planold['isfinished'] == 0) {
										$planold->delete();
										Plantingstructureyearfarmsidplan::newPlan($ttpoModel->oldnewfarms_id);
									} else {
										Plantingstructureyearfarmsidplan::newPlan($ttpoModel->oldnewfarms_id,1);
									}
									if($plannew['isfinished'] == 0) {
										$plannew->delete();
										Plantingstructureyearfarmsidplan::newPlan($ttpoModel->newnewfarms_id);
									} else {
										Plantingstructureyearfarmsidplan::newPlan($ttpoModel->newnewfarms_id,1);
									}
									break;
								case 'farmssplitcontinue':	//分户
									$farm = Farms::findOne($ttpoModel->samefarms_id);
									$newfarm = Farms::findOne($ttpoModel->newnewfarms_id);
//									$ttpos = Ttpozongdi::find()->where(['samefarms_id'=>$ttpoModel->samefarms_id])->all();
									$same = Collection::find()->where(['farms_id'=>$ttpoModel->samefarms_id,'payyear'=>date('Y')])->one();
//									foreach ($ttpos as $ttpo) {
										if($same['state']) {
											Collection::newCollection($ttpoModel->newnewfarms_id,true);
										} else {
//											$old2 = Collection::find()->where(['farms_id'=>$ttpo['oldfarms_id'],'payyear'=>date('Y')])->one();
//											$new = Collection::find()->where(['farms_id'=>$ttpo['newnewfarms_id'],'payyear'=>date('Y')])->one();
											Collection::newCollection($ttpoModel->newnewfarms_id);
											$same->delete();
										}
//									}
									if(Farms::getContractserialnumber($ttpoModel->samefarms_id) == Farms::getContractserialnumber($ttpoModel->newnewfarms_id)) {
										$fireOld = Fireprevention::find()->where(['farms_id'=>$ttpoModel->samefarms_id,'year'=>date('Y')])->one();
										if(empty($fireOld['percent'])) {
											Fireprevention::newFire( $ttpoModel->newnewfarms_id);
											$fireOld->delete();
										} else {
											Fireprevention::copy($fireOld['id'],$ttpoModel->newnewfarms_id);
											$fireOld->farmstate = 0;
											$fireOld->save();
										}
										$planold = Plantingstructureyearfarmsidplan::find()->where(['farms_id'=>$ttpoModel->samefarms_id,'year'=>User::getYear()])->one();
										if(!empty($planold)) {
											if ($planold['isfinished'] == 0) {
												$planold->delete();
												Plantingstructureyearfarmsidplan::newPlan($ttpoModel->newnewfarms_id);
											} else {
												Plantingstructureyearfarmsidplan::newPlan($ttpoModel->newnewfarms_id,1);
											}
										} else {
											Plantingstructureyearfarmsidplan::newPlan($ttpoModel->newnewfarms_id);
										}
										Plantingstructure::deletePlanting($ttpoModel->oldfarms_id);
									} else {
										$fireOld = Fireprevention::find()->where(['farms_id'=>$ttpoModel->samefarms_id,'year'=>date('Y')])->one();
										if(empty($fireOld['percent'])) {
											Fireprevention::newFire($ttpoModel->newnewfarms_id);
										}
										$planold = Plantingstructureyearfarmsidplan::find()->where(['farms_id'=>$ttpoModel->samefarms_id,'year'=>User::getYear()])->one();
										if(!empty($planold)) {
											if ($planold['isfinished'] == 0) {
												$planold->delete();
												Plantingstructureyearfarmsidplan::newPlan($ttpoModel->newnewfarms_id);
											}
										} else {
											Plantingstructureyearfarmsidplan::newPlan($ttpoModel->newnewfarms_id);
										}
										Plantingstructure::deletePlanting($ttpoModel->oldfarms_id);
									}

									break;
							}
//							var_dump($collections);
//							foreach ($collections as $collection) {
//								if($collection['payyear'] < date('Y')) {
//									if($collection['state'] == 1) {
//										$collectionModel = new Collection();
//										$collectionModel->create_at = time();
//										$collectionModel->update_at = $collection->create_at;
//										$collectionModel->payyear = $collection['payyear'];
//										$collectionModel->farms_id = $ttpoModel->newnewfarms_id;
//										$collectionModel->amounts_receivable = 0.0;
//										$collectionModel->real_income_amount = 0.0;
//										$collectionModel->ypayarea = 0.0;
//										$collectionModel->ypaymoney = 0.0;
//										$collectionModel->owe = 0.0;
//										$collectionModel->measure = 0.0;
//										$collectionModel->ypayyear = $collection['payyear'];
//										$collectionModel->dckpay = 1;
//										$collectionModel->state = 1;
//										$collectionModel->management_area = $farms['management_area'];
//										$collectionModel->save();
//									} else {
//										$collectionModel = new Collection();
//										$collectionModel->create_at = time();
//										$collectionModel->update_at = $collection->create_at;
//										$collectionModel->payyear = $collection['payyear'];
//										$collectionModel->farms_id = $ttpoModel->newnewfarms_id;
//										$collectionModel->management_area = $collection['management_area'];
//										$collectionModel->amounts_receivable = $collection['amounts_receivable'];
//										$collectionModel->real_income_amount = $collection['real_income_amount'];
//										$collectionModel->ypayarea = $collection['ypayarea'];
//										$collectionModel->ypaymoney = $collection['ypaymoney'];
//										$collectionModel->owe = $collection['owe'];
//										$collectionModel->measure = $collection['measure'];
//										$collectionModel->ypayyear = $collection['payyear'];
//										$collectionModel->dckpay = $collection['dckpay'];
//										$collectionModel->state = $collection['state'];
//										$collectionModel->save();
//									}
//								} else {
//									if($oldcollection['state'] == 1) {
//										$collectionModel = new Collection();
//										$collectionModel->create_at = time();
//										$collectionModel->update_at = $collection->create_at;
//										$collectionModel->payyear = date('Y');
//										$collectionModel->farms_id = $ttpoModel->newnewfarms_id;
//										$collectionModel->amounts_receivable = $collectionModel->getAR(date('Y'), $farms['id']);;
//										$collectionModel->real_income_amount = $oldcollection['real_income_amount'];
//										$collectionModel->ypayarea = bcsub($farms['contractarea'],$oldcollection['measure'],2);
//										$collectionModel->ypaymoney = bcsub($collectionModel->amounts_receivable,$collectionModel->real_income_amount,2);
//										$collectionModel->owe = $collectionModel->ypaymoney;
//										$collectionModel->measure = $oldcollection['measure'];
//										$collectionModel->ypayyear = date('Y');
//										$collectionModel->dckpay = 1;
//										$collectionModel->state = 2;
//										$collectionModel->management_area = $farms['management_area'];
//										$collectionModel->save();
//									} else {
//										$collectionModel = new Collection();
//										$collectionModel->create_at = time();
//										$collectionModel->update_at = $collection->create_at;
//										$collectionModel->payyear = $collection['payyear'];
//										$collectionModel->farms_id = $ttpoModel->newnewfarms_id;
//										$collectionModel->management_area = $collection['management_area'];
//										$collectionModel->amounts_receivable = $collectionModel->getAR(date('Y'), $farms['id']);;
//										$collectionModel->real_income_amount = 0.0;
//										$collectionModel->ypayarea = $farms['contractarea'];
//										$collectionModel->ypaymoney = bcmul($farms['contractarea'], $prices = PlantPrice::find()->where(['years' => date('Y')])->one()['price'], 2);
//										$collectionModel->owe = 0.0;
//										$collectionModel->measure = 0.0;
//										$collectionModel->dckpay = 0;
//										$collectionModel->state = 0;
//										$collectionModel->save();
////									var_dump($collectionModel->getErrors());
//									}
//								}
//
//							}

//							if($collection['state'] == 1) {
//								$collectionModel = new Collection();
//								$collectionModel->create_at = time();
//								$collectionModel->update_at = $collectionModel->create_at;
//								$collectionModel->payyear = date('Y');
//								$collectionModel->farms_id = $farms->id;
//								$collectionModel->amounts_receivable = 0.0;
//								$collectionModel->ypayarea = 0.0;
//								$collectionModel->ypaymoney = 0.0;
//								$collectionModel->owe = 0.0;
//								$collectionModel->dckpay = 1;
//								$collectionModel->state = 1;
//								$collectionModel->management_area = $farms->management_area;
//								$collectionModel->save();
//							} else {
//								if($ttpoModel->actionname == 'farmstransfermergecontract') {
//									$inherit = Collection::find()->where(['farms_id'=>$ttpoModel->newfarms_id])->all();
//									foreach ($inherit as $coll) {
//										$collectionModel = new Collection();
//										$collectionModel->create_at = time();
//										$collectionModel->update_at = $collectionModel->create_at;
//										$collectionModel->payyear = 2016;
//										$collectionModel->farms_id = $farm['id'];
//										$collectionModel->amounts_receivable = $collectionModel->getAR(2016, $farm['id']);
//										$collectionModel->real_income_amount = $collectionModel->amounts_receivable;
//										$collectionModel->ypayarea = $farm['contractarea'];
//										$collectionModel->ypaymoney = $collectionModel->amounts_receivable;
//										$collectionModel->owe = $collectionModel->amounts_receivable;
//										$collectionModel->measure = 0.0;
//										$collectionModel->ypayyear = 2016;
//										$collectionModel->dckpay = 0;
//										$collectionModel->state = 0;
//										$collectionModel->management_area = $farm['management_area'];
//										$collectionModel->save();
//
////										$collectionModel = Collection::findOne($coll['id']);
////										$collectionModel->update_at = time();
////										$collectionModel->farms_id = $ttpoModel->newfarms_id;
////										$collectionModel->save();
//									}
//								} else {
//									$collectionModel = new Collection();
//									$collectionModel->create_at = time();
//									$collectionModel->update_at = $collectionModel->create_at;
//									$collectionModel->payyear = date('Y');
//									$collectionModel->farms_id = $farms->id;
//									$collectionModel->amounts_receivable = $collectionModel->getAR(date('Y'), $farms->id);
//									$collectionModel->ypayarea = $farms->contractarea;
//									$collectionModel->ypaymoney = $collectionModel->amounts_receivable;
//									$collectionModel->owe = 0.0;
//									$collectionModel->dckpay = 0;
//									$collectionModel->state = 0;
//									$collectionModel->management_area = $farms->management_area;
//									$collectionModel->save();
//								}
//							}
							
						}
//						$findFarms = Farms::find()->where(['farmername'=>$farms->farmername,'cardid'=>$farms->cardid])->one();
//						$farmer = Farmer::find()->where(['farms_id'=>$findFarms['id']])->one();
//						$farmerMember = Farmermembers::find()->where(['farmer_id'=>$farmer['id']])->all();
//						$farmerModel = new Farmer();
//						$farmerModel->farms_id = $ttpoModel->newnewfarms_id;
//						$farmerModel->isupdate = 1;
//						$farmerModel->farmerbeforename = $farmer['farmerbeforename'];
//						$farmerModel->nickname = $farmer['nickname'];
//						$farmerModel->gender = $farmer['gender'];
//						$farmerModel->nation = $farmer['nation'];
//						$farmerModel->political_outlook = $farmer['political_outlook'];
//						$farmerModel->cultural_degree = $farmer['cultural_degree'];
//						$farmerModel->domicile = $farmer['domicile'];
//						$farmerModel->nowlive = $farmer['nowlive'];
//						$farmerModel->living_room = $farmer['living_room'];
//						$farmerModel->photo = $farmer['photo'];
//						$farmerModel->cardpic = $farmer['cardpic'];
//						$farmerModel->years = date('Y');
//						$farmerModel->create_at = (string)time();
//						$farmerModel->update_at = $farmerModel->create_at;
//						$farmerModel->cardpicback = $farmer['cardpicback'];
//						$farmerModel->state = 1;
//						$farmerModel->save();
//						foreach ($farmerMember as $value) {
//							$farmerMemberModel = new Farmermembers();
//							$farmerMemberModel->farmer_id = $farmerModel->id;
//							$farmerMemberModel->relationship = $value['relationship'];
//							$farmerMemberModel->membername = $value['membername'];
//							$farmerMemberModel->cardid = $value['cardid'];
//							$farmerMemberModel->remarks = $value['remarks'];
//							$farmerMemberModel->isupdate = 1;
//							$farmerMemberModel->create_at = (string)time();
//							$farmerMemberModel->update_at = $farmerMemberModel->create_at;
//							$farmerMemberModel->save();
//						}
					}

	    		}
//				exit;
				if($state == 9) {
					$ttpoModel->state = 9;
					$ttpoModel->save();
					Logs::writeLogs('撤消操作',$ttpoModel);
					if($ttpoModel->oldfarms_id) {
						$oldfarmsModel = Farms::findOne($ttpoModel->oldfarms_id);
						$oldfarmsModel->update_at = time();
						$oldfarmsModel->state = 1;
						$oldfarmsModel->locked = 0;
						$oldfarmsModel->otherstate = 6;
						$oldfarmsModel->save();
						Logs::writeLogs('还原出让方'.$oldfarmsModel->farmname.'信息',$oldfarmsModel);
// 						var_dump($expression)
//						var_dump($oldfarmsModel->getErrors());exit;
						Zongdioffarm::zongdiUpdate($ttpoModel->oldnewfarms_id, $ttpoModel->oldfarms_id, $ttpoModel->oldzongdi);
						Lockedinfo::deleteLockinfo($ttpoModel->oldfarms_id);
					}
					
					if($ttpoModel->newfarms_id) {
						$newfarmModel = Farms::findOne($ttpoModel->newfarms_id);
						$newfarmModel->update_at = time();
						$newfarmModel->state = 1;
						$newfarmModel->locked = 0;
						$newfarmModel->otherstate = 6;
						$newfarmModel->save();
						Logs::writeLogs('还原受让方'.$newfarmModel->farmname.'信息',$newfarmModel);
						Zongdioffarm::zongdiUpdate($ttpoModel->newnewfarms_id, $ttpoModel->newfarms_id, $ttpoModel->newzongdi);
						Lockedinfo::deleteLockinfo($ttpoModel->newfarms_id);
					}
					if($ttpoModel->oldnewfarms_id) {
						$oldnewfarms = Farms::findOne($ttpoModel->oldnewfarms_id);
						$oldnewfarms->state = -1;
						$oldnewfarms->locked = 0;
						$oldnewfarms->otherstate = $state;
						$oldnewfarms->save();
//						$oldnewfarms->delete();
						Logs::writeLogs('出让方'.$oldnewfarms->farmname.'变更后信息',$oldnewfarms);
						Zongdioffarm::zongdiDelete($ttpoModel->oldnewfarms_id, $oldnewfarms->zongdi);
					}
					if($ttpoModel->newnewfarms_id) {
						$newnewfarms = Farms::findOne($ttpoModel->newnewfarms_id);
						$newnewfarms->state = -1;
						$newnewfarms->locked = 0;
						$newnewfarms->otherstate = $state;
						$newnewfarms->save();
//						$newnewfarms->delete();
						Logs::writeLogs('新增受让方'.$newnewfarms->farmname.'信息',$newnewfarms);
						Zongdioffarm::zongdiDelete($ttpoModel->newnewfarms_id, $newnewfarms->zongdi);
					}
				}
				$model = $this->findModel($id);
	    		if(user::getItemname('地产科') and $model->estate == 1) {
		    		return $this->redirect(['reviewprocess/reviewprocessfarmssplit',
		    				'reviewprocessid' => $model->id,
		    		]);
	    		}
	    		if($state == 6 or $state == 7) {
	    			return $this->redirect(['reviewprocess/reviewprocessfinished']);
	    		}
				if($state == 9) {
					return $this->redirect(['reviewprocess/reviewprocesscacle']);
				}
				if($state == 8) {
					return $this->redirect(['reviewprocess/reviewprocessview','id'=>$model->id,'class'=>$model->actionname]);
				}
				if($state == 4) {
					if(User::getItemname('主任') or User::getItemname('副主任') or User::getItemname('地产科'))
						return $this->redirect(['reviewprocess/reviewprocessing']);
					else
						return $this->redirect(['reviewprocess/reviewprocessindex']);
				}
	    	}

	    	return $this->render ( 'reviewprocessinspections', [
	    			'model' => $model,
	    			'oldfarm' => $oldfarm,
	    			'newfarm' => $newfarm,
					'ttpoModel' => $ttpoModel,
	    			'process' => explode('>', $process),
	    			'class' => $class,
	    	] );
    	}
    	if($class == 'projectapplication') {
    		$farm = Farms::find()->where(['id'=>$model->oldfarms_id])->one();
//     		$whereArray = Farms::getManagementArea();
    		$project = Projectapplication::find()->where(['reviewprocess_id'=>$id])->one();
    		if($post) {
				$state = Reviewprocess::isNextProcess($model->id,'project',$post);
	    		if($state == 7) {
	    			$projectModel = Projectapplication::findOne($project->id);
	    			$projectModel->state = 1;
	    			$projectModel->save();
					Logs::writeLogs('审核通过-变更项目状态',$projectModel);
	    			return $this->redirect(['reviewprocess/reviewprocessindex']);
	    		}
    		}
    		return $this->render ( 'reviewprocessinspections', [
    				'model' => $model,
    				'farm' => $farm,
    				'process' => explode('>', $process),
    				'class' => $class,
    				'project'=>$project,
    		] );
    	}
    	if($class == 'loancreate') {
    		$farm = Farms::findOne($model->oldfarms_id);
    		//     		$whereArray = Farms::getManagementArea();
    		$loan = Loan::find()->where(['reviewprocess_id'=>$id])->one();
			if($post) {
				$modelname = Reviewprocess::getProcessIdentification('loancreate');
//				var_dump($modelname);exit;
				foreach ($modelname as $name) {
					$state = Reviewprocess::isNextLoanProcess($model->id,$name,$post);
					$classname = 'app\\models\\'.ucfirst($name);
					$agreefield = $name.'isAgree';
					$estateID= $classname::find()->where(['reviewprocess_id'=>$id])->one()['id'];
					if($estateID)
						$estateModel = $classname::findOne($estateID);
					else
						$estateModel = new $classname();
					if($name == 'leader' or $name == 'project') {
						$estateModel->$agreefield = Yii::$app->request->post($agreefield);
						$agreefieldcontent = $agreefield . 'content';
						if (Yii::$app->request->post($agreefield))
							$estateModel->$agreefieldcontent = Yii::$app->request->post($agreefieldcontent);
					} else {
						foreach ($classname::loanAttributesList() as $key => $value) {
							$estateModel->$key = Yii::$app->request->post($key);
							$contentName = $key . 'content';
							$estateModel->$contentName = Yii::$app->request->post($contentName);
							$estateModel->$agreefield = Yii::$app->request->post($agreefield);
							$agreefieldcontent = $agreefield . 'content';
							if (Yii::$app->request->post($agreefield))
								$estateModel->$agreefieldcontent = Yii::$app->request->post($agreefieldcontent);
						}
					}
					$estateModel->reviewprocess_id = $id;
					if(!$estateModel->$agreefield) {
						$undo = Yii::$app->request->post($name.'undo');
//						$model->$undo = 8;
//						$model->state = 8;
//						$model->undo = $undo;
//						$model->fromundo = $name;
//						$model->save();
						$field = $name.'undo';
						$estateModel->$field = $undo;
						$content = $agreefield.'content';
						$estateModel->$content = Yii::$app->request->post($content);
					}
					$estateModel->save();
				}
//    			if($model->leader == 0)
//    				$model->state = 5;
//    			if($model->leader == 1)
//    				$model->state = 7;
    			//     			var_dump($model);
    			$model->save();

				if($state == 7) {
    				$loanModel = Loan::findOne($loan->id);
    				$loanModel->state = 1;
    				$loanModel->lock = 1;
    				$loanModel->save();
					Logs::writeLogs('审核通过-变更贷款信息',$loanModel);
    				$farm->locked = 1;
    				$farm->save();
					Logs::writeLogs('锁定农场',$farm);
    			}
    			if($state == 8) {
    				$loanModel = Loan::findOne($loan->id);
    				$loanModel->state = 0;
    				$loanModel->lock = 1;
    				$loanModel->save();
    				$farm->locked = 1;
    				$farm->save();
    			}
    			if($state == 9) {
    				$loanModel = Loan::findOne($loan->id);
    				$loanModel->state = 0;
    				$loanModel->lock = 0;
    				$loanModel->save();
					Logs::writeLogs('审核没通过-撤消贷款',$loanModel);
    				$farm->locked = 0;
    				$farm->save();
					Logs::writeLogs('解锁农场',$farm);
    			}
				return $this->redirect(['reviewprocess/reviewprocessindex']);
    		}
    		return $this->render ( 'reviewprocessinspections', [
    				'model' => $model,
    				'farm' => $farm,
    				'process' => explode('>', $process),
    				'class' => $class,
    				'loan'=>$loan,
    		] );
    	}
    }
    
    public function actionReviewprocessview($id,$class)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
    	$model = $this->findModel($id);
    	 
    	$process = Auditprocess::find()->where(['actionname'=>$model->actionname])->one()['process'];
        $ttpoModel = Ttpozongdi::find()->where(['id'=>$model->ttpozongdi_id])->one();


//        var_dump($ttpoModel);exit;
    	if($class == 'farmstransfer') {
    		$oldfarm = Farms::find()->where(['id'=>$model->oldfarms_id])->one();
    		$newfarm = Farms::find()->where(['id'=>$model->newfarms_id])->one();
//    		if($model->load(Yii::$app->request->post())) {
//    			$model->save();
//    			// 	    		var_dump($_POST);exit;
//    			$state = Reviewprocess::isNextProcess($model->id);
//    			$estateID= Estate::find()->where(['reviewprocess_id'=>$id])->one()['id'];
//    			$estateModel = Estate::findOne($estateID);
//    			foreach (Estate::attributesList() as $key => $value) {
//    				$estateModel->$key = Yii::$app->request->post($key);
//    				$contentName = $key.'content';
//    				$estateModel->$contentName = Yii::$app->request->post($contentName);
//    			}
//    			$estateModel->save();
//    			// 	    		var_dump($estateModel);exit;
//    			if($state) {
//
//    				$oldfarmsModel = Farms::findOne($model->oldfarms_id);
//
//    				$oldfarmsModel->update_at = time();
//    				$oldfarmsModel->state = 0;
//    				$oldfarmsModel->locked = 0;
//    				$oldfarmsModel->save();
//    				$newfarmModel = Farms::findOne($model->newfarms_id);
//
//    				$newfarmModel->update_at = $oldfarmsModel->update_at;
//    				$newfarmModel->state = 1;
//    				$newfarmModel->locked = 0;
//    				$newfarmModel->save();
//    				$projectID = Projectapplication::find()->where(['farms_id'=>$oldfarmsModel->id,'reviewprocess_id'=>$id])->one()['id'];
//    				$projectModel = Projectapplication::findOne($projectID);
//    				$projectModel->farms_id = $newfarmModel->id;
//    				$projectModel->save();
//
//    			}
//    			return $this->redirect(['reviewprocess/reviewprocessindex']);
//    		}
			Logs::writeLogs('查看过户审核信息',$model);
    		return $this->render ( 'reviewprocessview', [
    				'model' => $model,
    				'oldfarm' => $oldfarm,
    				'newfarm' => $newfarm,
    				'ttpoModel' => $ttpoModel,
    				'process' => explode('>', $process),
    				'class' => $class,
    		] );
    	}
    	if($class == 'projectapplication') {
    		$farm = Farms::find()->where(['id'=>$model->oldfarms_id])->one();
    		//     		$whereArray = Farms::getManagementArea();
    		$project = Projectapplication::find()->where(['reviewprocess_id'=>$id])->one();
//    		if($model->load(Yii::$app->request->post())) {
//
//    			if($model->leader == 0)
//    				$model->state = 5;
//    			if($model->leader == 1)
//    				$model->state = 7;
//    			//     			var_dump($model);
//    			$model->save();
//    			$state = Reviewprocess::isNextProcess($model->id);
//
//    			if($state) {
//    				//     				$project = Projectapplication::find()->where(['reviewprocess_id'=>$model->id])->one();
//    				$projectModel = Projectapplication::findOne($project->id);
//    				$projectModel->state = 1;
//    				$projectModel->save();
//    				return $this->redirect(['reviewprocess/reviewprocessindex']);
//    			}
//    		}
			Logs::writeLogs('查看项目审核信息',$project);
    		return $this->render ( 'reviewprocessview', [
    				'model' => $model,
    				'farm' => $farm,
    				'process' => explode('>', $process),
    				'class' => $class,
    				'project'=>$project,
    		] );
    	}
    	if($class == 'loancreate') {
    		$farm = Farms::find()->where(['id'=>$model->oldfarms_id])->one();
    		//     		$whereArray = Farms::getManagementArea();
    		$loan = Loan::find()->where(['reviewprocess_id'=>$id])->one();
//    		if($model->load(Yii::$app->request->post())) {
//
//    			if($model->leader == 0)
//    				$model->state = 5;
//    			if($model->leader == 1)
//    				$model->state = 7;
//    			//     			var_dump($model);
//    			$model->save();
//    			$state = Reviewprocess::isNextProcess($model->id);
//
//    			if($state) {
//
//    				$loanModel = Loan::findOne($loan->id);
//    				$loanModel->state = 1;
//    				if($loanModel->save())
//    					return $this->redirect(['reviewprocess/reviewprocessindex']);
//    			}
//    		}
			Logs::writeLogs('查看贷款审核信息',$loan);
    		return $this->render ( 'reviewprocessview', [
    				'model' => $model,
    				'farm' => $farm,
    				'process' => explode('>', $process),
    				'class' => $class,
    				'loan'=>$loan,
    		] );
    	}
    }
    
    /**
     * Finds the Reviewprocess model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reviewprocess the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reviewprocess::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	public function actionReviewprocessaddotherstate()
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
		$reviewprocess = Reviewprocess::find()->all();
		foreach ($reviewprocess as $reviewproces) {
//			var_dump($reviewproces['state']);
			$ttpoModel = Ttpozongdi::findOne($reviewproces['ttpozongdi_id']);
//			var_dump($ttpoModel->oldnewfarms_id);
//			var_dump($ttpoModel->newnewfarms_id);
//			var_dump($ttpoModel->oldfarms_id);
			var_dump(Ttpozongdi::isSplit($reviewproces['id']));
			if($ttpoModel->oldfarms_id) {
				$oldfarmsModel = Farms::findOne($ttpoModel->oldfarms_id);
				if(Ttpozongdi::isSplit($reviewproces['id'])) {
					if($reviewproces['id'] == Ttpozongdi::getSameFirstID($reviewproces['id']))
						$oldfarmsModel->otherstate = $reviewproces['state'];
					else
						$oldfarmsModel->otherstate = 0;
				} else {
					var_dump('else');
					$oldfarmsModel->otherstate = $reviewproces['state'];
				}
				if(empty($oldfarmsModel->address))
					$oldfarmsModel->address = 'address';
				if(empty($oldfarmsModel->longitude))
					$oldfarmsModel->longitude = 'E';
				if(empty($oldfarmsModel->latitude))
					$oldfarmsModel->latitude = 'N';
				if(empty($oldfarmsModel->careid))
					$oldfarmsModel->cardid = 'X';
				$oldfarmsModel->save();
// 						var_dump($expression)
						var_dump($oldfarmsModel->getErrors());
			}

			if($ttpoModel->newfarms_id) {
				$newfarmModel = Farms::findOne($ttpoModel->newfarms_id);
				if(empty($newfarmModel->address))
					$newfarmModel->address = 'address';
				if(empty($newfarmModel->longitude))
					$newfarmModel->longitude = 'E';
				if(empty($newfarmModel->latitude))
					$newfarmModel->latitude = 'N';
				if(empty($newfarmModel->careid))
					$newfarmModel->cardid = 'X';
//				if($ttpoModel->newfarms_id == Ttpozongdi::getSameFirstID($reviewproces['id'])) {
					$newfarmModel->otherstate = $reviewproces['state'];
//				} else
//					$newfarmModel->otherstate = 0;
				$newfarmModel->otherstate = $reviewproces['state'];
				$newfarmModel->save();
			}
			if($ttpoModel->oldnewfarms_id) {
				$oldnewfarms = Farms::findOne($ttpoModel->oldnewfarms_id);
				$oldnewfarms->otherstate = $reviewproces['state'];
				if(empty($oldnewfarms->address))
					$oldnewfarms->address = 'address';
				if(empty($oldnewfarms->longitude))
					$oldnewfarms->longitude = 'E';
				if(empty($oldnewfarms->latitude))
					$oldnewfarms->latitude = 'N';
				if(empty($oldnewfarms->careid))
					$oldnewfarms->cardid = 'X';
				$oldnewfarms->save();
			}
			if($ttpoModel->newnewfarms_id) {
				$newnewfarms = Farms::findOne($ttpoModel->newnewfarms_id);
				$newnewfarms->otherstate = $reviewproces['state'];
				if(empty($newnewfarms->cardid))
					$newnewfarms->cardid = 'X';
				if(empty($newnewfarms->longitude))
					$newnewfarms->longitude = 'E';
				if(empty($newnewfarms->latitude))
					$newnewfarms->latitude = 'N';
				if(empty($newnewfarms->address))
					$newnewfarms->address = 'address';
				$newnewfarms->save();
				var_dump($newnewfarms->getErrors());
			}
		}
		echo 'finished';
	}

	public function actionReviewprocessunlock()
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
		$params = Yii::$app->request->queryParams;

		$whereArray = Farms::getManagementArea();

		$searchLoan = new loanSearch();

		$params['loanSearch']['lock'] = 1;
		$params['loanSearch']['state'] = [1,2];
//		$params['ReviewprocessSearch']['management_area'] = $whereArray['id'];

		if (empty($params['loanSearch']['management_area'])) {
			$params ['loanSearch'] ['management_area'] = $whereArray['id'];
		}
		$dataLoan = $searchLoan->searchUnlock($params);
		$title = '贷款解冻';
		Logs::writeLogs('贷款解冻');
		return $this->render('reviewprocessunlock', [
			'dataLoan' => $dataLoan,
			'searchLoan' => $searchLoan,
			'title' => $title,
//			'begindate' => $params['begindate'],
//			'enddate' => $params['enddate'],
		]);
	}

	public function actionReviewprocessaddttpozongdiid()
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
		$reviewprocess = Reviewprocess::find()->where(['actionname'=>'loancreate'])->all();
		foreach ($reviewprocess as $reviewproces) {
			$model = Reviewprocess::findOne($reviewproces['id']);
			$loan = Loan::find()->where(['farms_id'=>$model->oldfarms_id])->one();
			$model->ttpozongdi_id = $loan['id'];
			$model->save();
		}
		echo 'finished';
	}


}
