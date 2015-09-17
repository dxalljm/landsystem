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
    public function actionSearchindex()
    {
       	
    }

    public function actionGetsearch()
    {
    	$farm = Farms::find()->all();
    	//var_dump($farm);
    	$farmData = NULL;
    	foreach($farm as $key=>$val){
    		$searchData[] = [$val['pinyin'] => $val['farmname']];
    	}
    	$farmer = Farmer::find()->all();
    	$farmerData = NULL;
    	foreach ($farmer as $key => $val) {
    		$searchData[] = [$val['pinyin'] => $val['farmername']];
    	}
    	echo json_encode(['status'=>1,'searchdata'=>$searchData]);
    }
    
    public function geturl()
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

    /**
     * Finds the BankAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BankAccount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findFarm($str)
    {
        $result = Farms::find()->andFilterWhere(['like', 'pinyin', $str])->all();

        return $result;
    }
    
    protected function findFarmer($str)
    {
    	$result = Farmer::find()->andFilterWhere(['like', 'pinyin', $str])->all();

        return $result;
    }
}
