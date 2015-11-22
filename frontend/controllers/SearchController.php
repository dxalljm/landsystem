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
use app\models\Plant;
use app\models\Plantingstructure;
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
    
    public function actionSearchindex()
    {
    	$planting = $this->getPlantingstructure();
    	return $this->render('searchindex',[
    			'planting' => $planting,
    	]);
    }
    
    public function getPlantingstructure()
    {
    	$planting = Plantingstructure::find()->all();
    	foreach ($planting as $value) {
    		$plantname = Plant::find()->where(['id'=>$value['plant_id']])->one()['cropname'];
    		$result[$plantname]['area'][] = $value['area'];
    		$result[$plantname]['goodseed_id'][] = $value['goodseed_id'];
    	}
    	return $result;
    }
 
}
