<?php

namespace frontend\controllers;

use Yii;
use app\models\BankAccount;
use frontend\models\bankaccountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
     * Lists all BankAccount models.
     * @return mixed
     */
    public function actionBankaccountindex()
    {
        $searchModel = new bankaccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
        $this->findModel($id)->delete();

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
