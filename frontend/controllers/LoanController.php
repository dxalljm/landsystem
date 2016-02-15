<?php

namespace frontend\controllers;

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
        $searchModel = new loanSearch();
        $params = Yii::$app->request->queryParams;
        $params['loanSearch']['farms_id'] = (integer)$farms_id;
        $dataProvider = $searchModel->search($params);
		Logs::writeLog('贷款列表');
        return $this->render('loanindex', [
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
    	Logs::writeLog('查看一条贷款信息',$id);
        return $this->render('loanview', [
            'model' => $this->findModel($id),
        	'farms_id' => $farms_id,
        ]);
    }

    /**
     * Creates a new Loan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionLoancreate($farms_id)
    {
        $model = new Loan();
// 		var_dump(Yii::$app->request->post());
        if ($model->load(Yii::$app->request->post())) {
        	$model->create_at = time();
        	$model->update_at = time();
        	$model->management_area = Farms::getFarmsAreaID($farms_id);
        	if($model->save())
        	{
        		$farmsModel = Farms::findOne($farms_id);
        		$farmsModel->locked = 1;
        		$farmsModel->save();
        		Logs::writeLog('冻结农场',$farms_id);
        		$lockedinfoModel = new Lockedinfo();
        		$lockedinfoModel->farms_id = $farms_id;
        		$lockedinfoModel->lockedcontent = '因农场在贷款期限中，已被冻结，不能进行此操作，解冻日期为'.Loan::find()->where(['farms_id'=>$farms_id])->one()['enddate'];
        		$lockedinfoModel->save();
        		Logs::writeLog('增加冻结信息',$lockedinfoModel->id,'',$lockedinfoModel->attributes);
        	}
        	Logs::writeLog('新增贷款信息',$model->id,'',$model->attributes);
            return $this->redirect(['loanindex', 'farms_id'=>$farms_id]);
        } else {
            return $this->render('loancreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Loan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionLoanupdate($id)
    {
        $model = $this->findModel($id);
		$old = $model->attributes;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	Logs::writeLog('更新贷款信息',$id,$old,$model->attributes);
            return $this->redirect(['loanview', 'id' => $model->id,'farms_id'=>$model->farms_id]);
        } else {
            return $this->render('loanupdate', [
                'model' => $model,
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
        $loanModel = $this->findModel($id);
        $old = $loanModel->attributes;
        Logs::writeLog('删除贷款信息',$id,$old);
		$loanModel->delete();
        $farm = Farms::findOne($farms_id);
		$farm->locked = 0;
		$farm->save();
		Logs::writeLog('解冻农场',$farms_id);
		$lockedinfo = Lockedinfo::find()->where(['farms_id'=>$farms_id])->one();
		$lockedinfoModel = Lockedinfo::findOne($lockedinfo->id);
		$oldlockedinfo = $lockedinfoModel->attributes;
		$lockedinfoModel->delete();
		Logs::writeLog('删除冻结信息',$lockedinfo->id,$oldlockedinfo);
        return $this->redirect(['loanindex','farms_id'=>$farms_id]);
    }

    public function actionLoansearch($tab,$begindate,$enddate)
    {
    	if(isset($_GET['tab']) and $_GET['tab'] !== \Yii::$app->controller->id) {
    		return $this->redirect ([$_GET['tab'].'/'.$_GET['tab'].'search',
    				'tab' => $_GET['tab'],
    				'begindate' => strtotime($_GET['begindate']),
    				'enddate' => strtotime($_GET['enddate']),
    				$_GET['tab'].'Search' => ['management_area'=>$_GET['management_area']],
    		]);
    	} 
    	$searchModel = new loanSearch();
		if(!is_numeric($_GET['begindate']))
			 $_GET['begindate'] = strtotime($_GET['begindate']);
		if(!is_numeric($_GET['enddate']))
			 $_GET['enddate'] = strtotime($_GET['enddate']);

    	$dataProvider = $searchModel->searchIndex ( $_GET );
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
