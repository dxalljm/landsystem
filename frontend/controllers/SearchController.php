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
use app\models\Collection;
use app\models\User;
use app\models\Department;
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
    //获取用户管理区信息
    public function getUserManagementArea()
    {
    	$departmentid = User::find ()->where ( [
    			'id' => \Yii::$app->getUser ()->id
    	] )->one ()['department_id'];
    	$departmentData = Department::find ()->where ( [
    			'id' => $departmentid
    	] )->one ();
    	$whereArray = explode ( ',', $departmentData ['membership'] );
    	return $whereArray;
    }
    
    //获取用户所在管理区的所有农场
    public function getAllFarmsID()
    {
    	$farms = Farms::find()->where(['management_area'=>$this->getUserManagementArea()])->all();
    	foreach ($farms as $value) {
    		$result[] = $value['id'];
    	}
    	return $result;
    }
    
    public function getPlantingstructure()
    {
    	$planting = [];
    	foreach($this->getAllFarmsID() as $value) {
    		$p = Plantingstructure::find()->where(['farms_id'=>$value])->all();
    		if(!empty($p))
    			$planting[$value][] = $p;
    	}
// 		var_dump($planting[2][0]);exit;
    	foreach ($planting as $key => $value) {
    		foreach ($value as $val) {
    			var_dump($val);exit;
//     			$plantname = Plant::find()->where(['id'=>$val['plant_id']])->one()['cropname'];
// 	    		$result[$plantname]['area'][] = $val['area'];
// 	    		$result[$plantname]['goodseed_id'][] = $val['goodseed_id'];
    		}
    		
    	}
    	return $result;
    }
 
    public function getCollection()
    {
    	$collection = Collection::find()->all(); 
    } 
}
