<?php

namespace frontend\controllers;

use Yii;
use app\models\Projectapplication;
use frontend\models\projectapplicationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use app\models\Projecttype;
use app\models\Infrastructuretype;
use app\models\Reviewprocess;
use app\models\Theyear;
/**
 * ProjectapplicationController implements the CRUD actions for Projectapplication model.
 */
class ProjectapplicationController extends Controller
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
     * Lists all Projectapplication models.
     * @return mixed
     */
    public function actionProjectapplicationindex($farms_id)
    {
        $searchModel = new projectapplicationSearch();
        $params = Yii::$app->request->queryParams;
        $params['projectapplicationSearch']['farms_id'] = $farms_id;
        $dataProvider = $searchModel->search($params);

        return $this->render('projectapplicationindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'farms_id' => $farms_id,
        ]);
    }
    
    public function actionManagementarea()
    {
    	$project = Projectapplication::find()->all();
    	foreach ($project as $value) {
    		$model = $this->findModel($value['id']);
    		$model->management_area = Farms::getFarmsAreaID($value['farms_id']);
    		$model->save();
    	}
    	echo 'finished';
    }
    
    public function actionProjectapplicationinfo()
    {
    	$where = Farms::getManagementArea()['id'];
    	$searchModel = new projectapplicationSearch();
    	$params = Yii::$app->request->queryParams;
    	$params ['projectapplicationSearch'] ['state'] = 1;
		// 管理区域是否是数组
		if (empty($params['projectapplicationSearch']['management_area'])) {
			$params ['projectapplicationSearch'] ['management_area'] = $where;
		}
		$params['begindate'] = Theyear::getYeartime()[0];
		$params['enddate'] = Theyear::getYeartime()[1];
		$dataProvider = $searchModel->search ( $params );

		// 如果选择多个区域, 默认为空
		if (is_array($searchModel->management_area)) {
			$searchModel->management_area = null;
		}
    
    	return $this->render('projectapplicationinfo', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'params' => $params,
    	]);
    }

    public function actionProjectapplicationlist()
    {
    	$searchModel = new projectapplicationSearch();
    	$whereArray = Farms::getManagementArea()['id'];
    	$params = Yii::$app->request->queryParams;
    	$params['projectapplicationSearch']['management_area'] = $whereArray;
    	$params['projectapplicationSearch']['state'] = 1;
    	$dataProvider = $searchModel->search($params);
    
    	return $this->render('projectapplicationlist', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    }
    /**
     * Displays a single Projectapplication model.
     * @param integer $id
     * @return mixed
     */
    public function actionProjectapplicationview($id)
    {
        return $this->render('projectapplicationview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Projectapplication model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionProjectapplicationcreate($farms_id)
    {
        $model = new Projectapplication();
		$farm = Farms::find()->where(['id'=>$farms_id])->one();
        if ($model->load(Yii::$app->request->post())) {
//         	var_dump($model);exit;
        	$reviewprocessID =Reviewprocess::processRun($farms_id);
        	$model->farms_id = $farms_id;
//         	$model->content = Yii::$app->request->post('projectapplication-content');
//         	$model->projectdata = Yii::$app->request->post('projectapplication-projectdata');
//         	$model->unit = Yii::$app->request->post('projectapplication-unit');
        	$model->reviewprocess_id = $reviewprocessID;
        	$model->management_area = Farms::getFarmsAreaID($farms_id);
        	$model->create_at = time();
        	$model->update_at = $model->create_at;
        	$model->is_agree = 0;
        	$model->state = 0;
        	$model->save();
        	
            return $this->redirect(['projectapplicationindex', 'farms_id' => $farms_id]);
        } else {
            return $this->render('projectapplicationcreate', [
                'model' => $model,
            	'farm' => $farm,
            ]);
        }
    }
	
    public function actionProjectapplicationprint($id)
    {
    	$model = $this->findModel($id);
    	$farm = Farms::find()->where(['id'=>$model->farms_id])->one();
    	$projecttypename = Infrastructuretype::find()->where(['id'=>$model->projecttype])->one()['typename'];
//     	var_dump($projecttypename);exit;
    	return $this->render('projectapplicationprint', [
    			'model' => $model,
    			'farm' => $farm,
    			'projecttypename' => $projecttypename,
    	]);
    }
    
    /**
     * Updates an existing Projectapplication model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionProjectapplicationupdate($id,$farms_id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->save();
            return $this->redirect(['projectapplicationindex', 'farms_id' => $farms_id]);
        } else {
            return $this->render('projectapplicationupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Projectapplication model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionProjectapplicationdelete($id,$farms_id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['projectapplicationindex','farms_id'=>$farms_id]);
    }

    public function actionProjectapplicationsearch($tab,$begindate,$enddate)
    {
    	if(isset($_GET['tab']) and $_GET['tab'] !== \Yii::$app->controller->id) {
    		return $this->redirect ([$_GET['tab'].'/'.$_GET['tab'].'search',
    				'tab' => $_GET['tab'],
    				'begindate' => strtotime($_GET['begindate']),
    				'enddate' => strtotime($_GET['enddate']),
    				$_GET['tab'].'Search' => ['management_area'=>$_GET['management_area']],
    		]);
    	} 
    	$searchModel = new projectapplicationSearch();
		if(!is_numeric($_GET['begindate']))
			 $_GET['begindate'] = strtotime($_GET['begindate']);
		if(!is_numeric($_GET['enddate']))
			 $_GET['enddate'] = strtotime($_GET['enddate']);

    	$dataProvider = $searchModel->searchIndex ( $_GET );
    	return $this->render('projectapplicationsearch',[
	    			'searchModel' => $searchModel,
	    			'dataProvider' => $dataProvider,
	    			'tab' => $_GET['tab'],
	    			'begindate' => $_GET['begindate'],
	    			'enddate' => $_GET['enddate'],
	    			'params' => $_GET,
    	]);    	
    }
    
    /**
     * Finds the Projectapplication model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Projectapplication the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Projectapplication::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
