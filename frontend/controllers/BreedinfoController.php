<?php

namespace frontend\controllers;

use Yii;
use app\models\Breedinfo;
use frontend\models\breedinfoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Theyear;
use app\models\Farms;
/**
 * BreedinfoController implements the CRUD actions for Breedinfo model.
 */
class BreedinfoController extends Controller
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
     * Lists all Breedinfo models.
     * @return mixed
     */
    public function actionBreedinfoindex()
    {
        $searchModel = new breedinfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('breedinfoindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionBreedinfoinfo()
    {
    	$searchModel = new breedinfoSearch();
    	$params = Yii::$app->request->queryParams;
    	$whereArray = Farms::getManagementArea()['id'];
    
    	if (empty($params['breedinfoSearch']['management_area'])) {
    		$params ['breedinfoSearch'] ['management_area'] = $whereArray;
    	}
    	$dataProvider = $searchModel->search ( $params );
    	if (is_array($searchModel->management_area)) {
    		$searchModel->management_area = null;
    	}
    
    	return $this->render('breedinfoinfo', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'params' => $params,
    	]);
    }

    /**
     * Displays a single Breedinfo model.
     * @param integer $id
     * @return mixed
     */
    public function actionBreedinfoview($id)
    {
        return $this->render('breedinfoview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Breedinfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBreedinfocreate($farms_id)
    {
        $model = new Breedinfo();

        if ($model->load(Yii::$app->request->post())) {
        	$model->management_area = Farms::getFarmsAreaID($farms_id);
        	$model->create_at = time();
        	$model->update_at = $model->create_at;
        	$model->save();
            return $this->redirect(['breedinfoview', 'id' => $model->id]);
        } else {
            return $this->render('breedinfocreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Breedinfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBreedinfoupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->save();
            return $this->redirect(['breedinfoview', 'id' => $model->id]);
        } else {
            return $this->render('breedinfoupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Breedinfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBreedinfodelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['breedinfoindex']);
    }
    
    public function actionBreedinfosearch($begindate,$enddate,$management_area)
    {
    	$post = Yii::$app->request->post();
    
    	if($post) {
    		if($post['tab'] == 'parmpt')
    			return $this->redirect(['search/searchindex']);
    		$whereDate = Theyear::formatDate($post['begindate'],$post['enddate']);
    		return $this->redirect ([$post['tab'].'/'.$post['tab'].'search',
    				'begindate' => $whereDate['begindate'],
    				'enddate' => $whereDate['enddate'],
    				'management_area' => $post['managementarea'],
    		]);
    	} else {
    		 
    		$searchModel = new breedinfoSearch();
    		$params = Yii::$app->request->queryParams;
    		if($management_area) {
    			$arrayID = Farms::getFarmArray($management_area);
    			$params ['breedinfoSearch']['farms_id'] = $arrayID;
    		}
    		$params ['breedinfoSearch']['begindate'] = $begindate;
    		$params ['breedinfoSearch']['enddate'] = $enddate;
    		$dataProvider = $searchModel->search ( $params );
    		return $this->render('breedinfosearch',[
    				'searchModel' => $searchModel,
    				'dataProvider' => $dataProvider,
    		]);
    	}
    }

    /**
     * Finds the Breedinfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Breedinfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Breedinfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
