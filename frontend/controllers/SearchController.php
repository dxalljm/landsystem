<?php

namespace frontend\controllers;

use Yii;
use app\models\BankAccount;
use frontend\models\bankaccountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Logs;
use app\models\Farms;
use app\models\Farmer;
/**
 * BankAccountController implements the CRUD actions for BankAccount model.
 */
class SearchController extends Controller
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
    public function actionSearchindex()
    {
       	
    }

    public function actionGetsearch()
    {
    	$farm = $this->findFarm($str);
    }
    
    public function geturl()
    {
    	
    }
    

    /**
     * Finds the BankAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BankAccount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findFarm($str)
    {
        $query = Farms::find();
        $dataProvider = new ActiveDataProvider([
        		'query' => $query,
        ]);
        $query->andFilterWhere(['like', 'farmname', $str]);
        return $dataProvider;
    }
    
    protected function findFarmer($str)
    {
    	$query = Farmer::find();
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    	$query->andFilterWhere(['like', 'farmername', $str]);
    	return $dataProvider;
    }
}
