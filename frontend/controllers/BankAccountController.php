<?php

namespace frontend\controllers;

use Yii;
use app\models\BankAccount;
use frontend\models\bankaccountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Logs;
/**
 * BankAccountController implements the CRUD actions for BankAccount model.
 */
class BankAccountController extends Controller
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
     * Lists all BankAccount models.
     * @return mixed
     */
    public function actionBankaccountindex()
    {
        $searchModel = new bankaccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		Logs::writeLog('进入银账号管理页面');
        return $this->render('bankaccountindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bankaccount model.
     * @param integer $id
     * @return mixed
     */
    public function actionBankaccountview($id)
    {
    	Logs::writeLog('银行账号查看操作',$id);
        return $this->render('bankaccountview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BankAccount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBankaccountcreate()
    {
        $model = new BankAccount();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$newAttr = $model->attributes;
        	Logs::writeLog('创建银行账号',$model->id,'',$newAttr);
            return $this->redirect(['bankaccountview', 'id' => $model->id]);
        } else {
            return $this->render('bankaccountcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Bankaccount model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBankaccountupdate($id)
    {
        $model = $this->findModel($id);
		$old = $model->attributes;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$new = $model->attributes;
        	Logs::writeLog('更新银行账号',$id,$old,$new);
            return $this->redirect(['bankaccountview', 'id' => $model->id]);
        } else {
            return $this->render('bankaccountupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Bankaccount model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBankaccountdelete($id)
    {
        $model = $this->findModel($id);
		$old = $model->attributes;
		$model->delete();
		Logs::writeLog('删除银行账号',$id,$old);
        return $this->redirect(['bankaccountindex']);
    }

    /**
     * Finds the BankAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BankAccount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BankAccount::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
