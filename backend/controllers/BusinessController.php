<?php

namespace backend\controllers;

use Yii;
use app\models\User;
use frontend\models\farmsSearch;
use backend\models\userSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farmer;
use frontend\models\farmerSearch;

/**
 * FarmsController implements the CRUD actions for Farms model.
 */
class BusinessController extends Controller
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
     * Lists all Farms models.
     * @return mixed
     */
    public function actionBusinessindex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('businessindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBusinessupdate($id)
    {
    	$searchFarms = new FarmsSearch();
    	$dataFarms = $searchFarms->search(Yii::$app->request->queryParams);
    	
    	$searchFarmer = new farmerSearch();
    	$dataFarmer = $searchFarmer->search(Yii::$app->request->queryParams);
    	
    	return $this->render('businessupdate', [
    			'$dataFarms' => $dataFarms,
    			'$dataFarmer' => $dataFarmer,
    	]);
    }
    
    public function actionBusinesslists($id)
    {
    	$countBranches = Farmer::find()
    	->where(['farms_id' => $id])
    	->count();
    	$branches = Branches::find()
    	->where(['companies_company_id' => $id])
    	->all();
    	if ($countBranches > 0) {
    		foreach ($branches as $branche) {
    			echo "<option value='" . $branche->branch_id . "'>" . $branche->branch_name . "</option>";
    		}
    	} else {
    		echo "<option>-</option>";
    	}
    }
   
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
