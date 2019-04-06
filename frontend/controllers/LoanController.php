<?php

namespace frontend\controllers;

use app\models\Auditprocess;
use app\models\Estate;
use app\models\Mortgage;
use Yii;
use app\models\Loan;
use frontend\models\loanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use app\models\Lockedinfo;
use app\models\Logs;
use app\models\Theyear;
use app\models\Reviewprocess;
use app\models\User;
use \PHPExcel_IOFactory;
use yii\web\UploadedFile;
use app\models\UploadForm;
/**
 * LoanController implements the CRUD actions for Loan model.
 */
class LoanController extends Controller
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
            $this->redirect(['site/logout']);
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
     * Lists all Loan models.
     * @return mixed
     */
    public function actionLoanindex($farms_id)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        }
        $farm = Farms::findOne($farms_id);
        $searchModel = new loanSearch();
        $params = Yii::$app->request->queryParams;
        $params['loanSearch']['farms_id'] = (integer)$farms_id;
        $dataProvider = $searchModel->searchone($params);
		Logs::writeLog('贷款列表',$farms_id);
        return $this->render('loanindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'farm' => $farm,
        ]);
    }

    
    public function actionLoaninfo()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        }
    	$searchModel = new loanSearch();
    	$params = Yii::$app->request->queryParams;
    	$whereArray = Farms::getManagementArea()['id'];
    	if (!isset($params ['loanSearch'] ['management_area'])) {
    		$params ['loanSearch'] ['management_area'] = $whereArray;
    	}
    	
    	if(!isset($params['loanSearch']['lock']))
     		$params['loanSearch']['lock'] = 1;
//    	$params['loanSearch']['year'] = User::getYear();
        $params['loanSearch']['state'] = 1;
    	
    	$dataProvider = $searchModel->search ( $params );
        Logs::writeLogs('首页板块-贷款信息');
    	return $this->render('loaninfo',[
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    }
    /**
     * Displays a single Loan model.
     * @param integer $id
     * @return mixed
     */
    public function actionLoanview($id,$farms_id)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        }
        $model = $this->findModel($id);
    	Logs::writeLogs('查看一条贷款信息',$model);
        return $this->render('loanview', [
            'model' => $model,
        	'farms_id' => $farms_id,
        ]);
    }

    public function actionLoanexamine($farms_id)
    {
    	
    }
    
    /**
     * Creates a new Loan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionLoancreate($farms_id)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        }
        $model = new Loan();
        $process = Auditprocess::find()->where(['actionname'=>'loancreate'])->one()['process'];

//        var_dump($process);exit;
// 		var_dump(Yii::$app->request->post());
        if ($model->load(Yii::$app->request->post())) {
//            var_dump($_POST);exit;
            $auditprocessID = Auditprocess::find('id')->where(['actionname'=>Yii::$app->controller->action->id])->one()['id'];
        	$reviewprocessID =Reviewprocess::processRun($auditprocessID,$farms_id);
        	$model->create_at = time();
        	$model->update_at = $model->create_at;
        	$model->reviewprocess_id = $reviewprocessID;
        	$model->state = 2;
        	$model->management_area = Farms::getFarmsAreaID($farms_id);
            $model->auditprocess_id = $auditprocessID;
            $model->lock = 1;
            $model->year = date('Y');
            $model->farmstate = Farms::find()->where(['id'=>$farms_id])->one()['state'];
        	if($model->save())
        	{
                $mModel = new Estate();
                $mModel->isself = Yii::$app->request->post('isself');
                $mModel->iscontract = Yii::$app->request->post('iscontract');
                $mModel->iscardid = Yii::$app->request->post('iscardid');
                $mModel->islocked = Yii::$app->request->post('islocked');
                $mModel->reviewprocess_id = $reviewprocessID;
                $mModel->save();

        		$farmsModel = Farms::findOne($farms_id);
        		$farmsModel->locked = 1;
        		$farmsModel->save();
        		Logs::writeLog('冻结农场',$farms_id);
        		$lockedinfoModel = new Lockedinfo();
        		$lockedinfoModel->farms_id = $farms_id;
//                $loan = Loan::find()->where(['farms_id'=>$farms_id,'year'=>date('Y')])->one();
        		$lockedinfoModel->lockedcontent = '因农场在贷款期限中，已被冻结，不能进行此操作，贷款期限为'.$model->begindate.'——'.$model->enddate;
        		$lockedinfoModel->save();
        		Logs::writeLog('增加冻结信息',$lockedinfoModel->id,'',$lockedinfoModel->attributes);
        		
        	}
        	Logs::writeLogs('新增贷款信息',$model);
            return $this->redirect([
                'loanindex',
                'farms_id' => $farms_id,
            ]);
//            return $this->redirect ( [
//					'print/printloan',
//					'farms_id' => $farms_id,
//            		'loan_id' => $model->id,
//					'reviewprocessid' => $reviewprocessID
//			] );
        } else {
            return $this->render('loancreate', [
                'model' => $model,
                'process' => explode('>', $process),
            ]);
        }
    }

    public function actionLoanunlock($id)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        }
    	$model = $this->findModel($id);
        $model->lock = 0;
        $model->update_at = time();
        $model->save();
        Logs::writeLogs('解锁贷款冻结-解锁贷款',$model);
    	$farmModel = Farms::findOne($model->farms_id);
//        var_dump($farmModel);exit;
    	$farmModel->locked = 0;
    	$farmModel->save();
        Logs::writeLogs('解锁贷款冻结-解锁农场',$farmModel);
        $lockedinfo = Lockedinfo::find()->where(['farms_id'=>$farmModel->id])->one();
        if($lockedinfo) {
            $lockedinfoModel = Lockedinfo::findOne($lockedinfo['id']);
            $lockedinfoModel->delete();
        }
    	return $this->redirect(['reviewprocess/reviewprocessunlock']);
    }
    
    /**
     * Updates an existing Loan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionLoanupdate($id,$farms_id)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        }
        $model = $this->findModel($id);
        $process = Auditprocess::find()->where(['actionname'=>'loancreate'])->one()['process'];
        $old = $model->attributes;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//         	$auditprocessID = Auditprocess::find()->where(['actionname'=>'loancreate'])->one()['id'];
            $revieprocessModel = Reviewprocess::findOne($model->reviewprocess_id);
            $revieprocessModel->estate = 1;
            $revieprocessModel->mortgage = 2;
            $revieprocessModel->regulations = 3;
            $revieprocessModel->finance = 3;
            $revieprocessModel->leader = 3;
            $revieprocessModel->update_at = time();
            $revieprocessModel->estatetime = time();
            $revieprocessModel->save();
//         	estate>mortgage>regulations>finance>leader
            $model->create_at = time();
            $model->update_at = $model->create_at;
            $model->reviewprocess_id = $revieprocessModel->id;
            $model->state = 2;
            $model->management_area = Farms::getFarmsAreaID($farms_id);
            $model->auditprocess_id = $model->auditprocess_id;
            if($model->save())
            {
                $estate = Estate::find()->where(['reviewprocess_id'=>$model->reviewprocess_id])->one();
                if($estate) {
                    $mModel = Estate::findOne($estate['id']);
                } else {
                    $mModel = new Estate();
                }
                $mModel->isself = Yii::$app->request->post('isself');
                $mModel->iscontract = Yii::$app->request->post('iscontract');
                $mModel->iscardid = Yii::$app->request->post('iscardid');
                $mModel->islocked = Yii::$app->request->post('islocked');
                $mModel->reviewprocess_id = $revieprocessModel->id;;
                $mModel->save();

                $farmsModel = Farms::findOne($farms_id);
                $farmsModel->locked = 1;
                $farmsModel->save();
                Logs::writeLog('冻结农场',$farms_id);
                $lockedinfo = Lockedinfo::find()->where(['farms_id'=>$farms_id])->one();
                if($lockedinfo) {
                    $lockedinfoModel = Lockedinfo::findOne($lockedinfo['id']);
                } else {
                    $lockedinfoModel = new Lockedinfo();
                }
                $lockedinfoModel->farms_id = $farms_id;
//                $loan = Loan::find()->where(['farms_id'=>$farms_id,'year'=>date('Y')])->one();
                $lockedinfoModel->lockedcontent = '因农场在贷款期限中，已被冻结，不能进行此操作，贷款期限为'.$model->begindate.'——'.$model->enddate;
                $lockedinfoModel->save();
                Logs::writeLogs('更新冻结信息',$lockedinfoModel);

            }
            Logs::writeLogs('更新贷款信息',$model);
            return $this->redirect(['loanview', 'id' => $model->id,'farms_id'=>$model->farms_id]);
        } else {
            return $this->render('loanupdate', [
                'model' => $model,
                'process' => explode('>', $process),
                'farms_id' => $farms_id,
            ]);
        }
    }

    /**
     * Deletes an existing Loan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionLoandelete($id,$farms_id)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        }
        $loanModel = $this->findModel($id);
        Logs::writeLogs('删除贷款信息',$loanModel);
        $reviewprocessModel = Reviewprocess::findOne($loanModel->reviewprocess_id);
        $reviewprocessModel->delete();
        Logs::writeLogs('删除贷款审核流程信息',$reviewprocessModel);
		$loanModel->delete();
        $farm = Farms::findOne($farms_id);
		$farm->locked = 0;
		$farm->save();
        Logs::writeLogs('解冻农场',$farm);
		$lockedinfo = Lockedinfo::find()->where(['farms_id'=>$farms_id])->one();
		$lockedinfoModel = Lockedinfo::findOne($lockedinfo->id);
		$lockedinfoModel->delete();
		Logs::writeLogs('删除冻结信息',$lockedinfoModel);
        return $this->redirect(['loanindex','farms_id'=>$farm['id']]);
    }


    public function actionXlsimport()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        }
        set_time_limit(0);
        $model = new UploadForm();
        //echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->file && $model->validate()) {
//                var_dump($model);exit;
                $xlsName = time().rand(100,999);
                $model->file->name = $xlsName;
                $model->file->saveAs('uploads/' . $model->file->name . '.' . $model->file->extension);
                $path = 'uploads/' . $model->file->name . '.' . $model->file->extension;
                $loadxls = \PHPExcel_IOFactory::load($path);
//                var_dump($loadxls);exit;
                for($i=0;$i<=$loadxls->getActiveSheet()->getHighestRow();$i++) {
                    //echo $loadxls->getActiveSheet()->getCell('A'.$i)->getValue()."<br>";
                    $farmname = $loadxls->getActiveSheet()->getCell('B'.$i)->getValue();
                    $farmername = $loadxls->getActiveSheet()->getCell('C'.$i)->getValue();
                    $contractnumber = $loadxls->getActiveSheet()->getCell('D'.$i)->getValue();
//                    var_dump($farmname);var_dump($farmername);var_dump($contractnumber);
                    $farm = Farms::find()->where(['farmname'=>$farmname,'farmername'=>$farmername,'contractnumber'=>$contractnumber])->andWhere('state>0')->one();
//                    if(count($farm)>1) {
//                        var_dump($farm);
//                    }
//                    var_dump($farm);
                    $modelLoan = new Loan();
                    $modelLoan->farms_id = $farm['id'];
                    $modelLoan->create_at = time();
                    $modelLoan->update_at = $modelLoan->create_at;
                    $modelLoan->mortgagearea = $farm['contractarea'];
                    if($loadxls->getActiveSheet()->getCell('G'.$i)->getValue() == '解冻') {
                        $modelLoan->mortgagebank = '龙江银行';
                        $modelLoan->lock = 0;
                    } else {
                        $modelLoan->mortgagebank = Loan::toBankname($loadxls->getActiveSheet()->getCell('G' . $i)->getValue());
                        $modelLoan->lock = 1;
                    }
                    $modelLoan->mortgagemoney = (float)$loadxls->getActiveSheet()->getCell('E'.$i)->getValue();
                    $modelLoan->begindate = str_replace(".","-",$loadxls->getActiveSheet()->getCell('F'.$i)->getValue());
                    $y = date('Y',strtotime($modelLoan->begindate)) + 1;
                    $m = date('m',strtotime($modelLoan->begindate));
                    $d = date('d',strtotime($modelLoan->begindate)) -1 ;
                    $modelLoan->enddate = $y.'-'.$m.'-'.$d;
                    $modelLoan->reviewprocess_id = 0;
                    $modelLoan->state = 1;
                    $modelLoan->management_area = $farm['management_area'];
                    $modelLoan->auditprocess_id = 3;
                    $modelLoan->year = '2018';
                    $modelLoan->farmstate = $farm['state'];
//                    $loanModel->serialnumber = $loadxls->getActiveSheet()->getCell('A'.$i)->getValue();
//
                    $modelLoan->save();
                    $new = $modelLoan->attributes;
                    Logs::writeLogs('xls批量导入创建宗地信息',$modelLoan);
                    //print_r($parchmodel->getErrors());
                }
            }
//            exit;
        }

        return $this->render('xlsimport',[
            'model' => $model,
        ]);
    }

    public function actionLoansearch($tab,$begindate,$enddate)
    {
    	if(isset($_GET['tab']) and $_GET['tab'] !== \Yii::$app->controller->id) {
    		return $this->redirect ([$_GET['tab'].'/'.$_GET['tab'].'search',
    				'tab' => $_GET['tab'],
    				'begindate' => strtotime($_GET['begindate']),
    				'enddate' => strtotime($_GET['enddate']),
//    				$_GET['tab'].'Search' => ['management_area'=>$_GET['management_area']],
    		]);
    	} 
    	$searchModel = new loanSearch();
		if(!is_numeric($_GET['begindate']))
			 $_GET['begindate'] = strtotime($_GET['begindate']);
		if(!is_numeric($_GET['enddate']))
			 $_GET['enddate'] = strtotime($_GET['enddate']);

    	$dataProvider = $searchModel->searchIndex ( $_GET );
        Logs::writeLogs('结合查询-贷款信息');
    	return $this->render('loansearch',[
	    			'searchModel' => $searchModel,
	    			'dataProvider' => $dataProvider,
	    			'tab' => $_GET['tab'],
	    			'begindate' => $_GET['begindate'],
	    			'enddate' => $_GET['enddate'],
	    			'params' => $_GET,
    	]);    	
    }
    
    /**
     * Finds the Loan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Loan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Loan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
