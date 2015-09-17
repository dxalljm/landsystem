<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Logs;
use app\models\Farms;
use app\models\Farmer;
use frontend\helpers;
use frontend\helpers\Pinyin;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
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
    public function actionSearchredirect()
    {
       	
    }

    
    public function actionSetpinyin()
    {
    	set_time_limit(0);
    	$farms = Farms::find()->all();
    	//var_dump($farms);
    	foreach ($farms as $value) {

    		$model = Farms::findOne($value['id']);
    		//var_dump($value['farmname']);
    		$model->pinyin = Pinyin::encode($value['farmname']);
    		$model->save();
    		
    	}
    	echo '完成';
    }
}
