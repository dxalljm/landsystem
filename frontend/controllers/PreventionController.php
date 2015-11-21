<?php

namespace frontend\controllers;

use Yii;
use app\models\Prevention;
use frontend\models\preventionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\breedinfoSearch;
use app\models\Breed;

/**
 * PreventionController implements the CRUD actions for Prevention model.
 */
class PreventionController extends Controller
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
     * Lists all Prevention models.
     * @return mixed
     */
    public function actionPreventionindex($farms_id)
    {
    	$breed = Breed::find()->where(['farms_id'=>$farms_id])->one();
        $searchModel = new breedinfoSearch();
        $paramsbreed = Yii::$app->request->queryParams;
        if($breed)
        	$paramsbreed['breedinfoSearch']['breed_id'] = $breed->id;
        else 
        	$paramsbreed['breedinfoSearch']['breed_id'] = 0;
        $dataProvider = $searchModel->search($paramsbreed);
        
        
		$preventionSearch = new preventionSearch();
		$params = Yii::$app->request->queryParams;
		$params['preventionSearch']['farms_id'] = $farms_id;
		$preventionData = $preventionSearch->search($params);
        return $this->render('preventionindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'preventionSearch' => $preventionSearch,
        	'preventionData' => $preventionData,
        ]);
    }

    /**
     * Displays a single Prevention model.
     * @param integer $id
     * @return mixed
     */
    public function actionPreventionview($id)
    {
        return $this->render('preventionview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Prevention model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPreventioncreate($farms_id)
    {
        $model = new Prevention();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['preventionindex', 'farms_id'=>$farms_id]);
        } else {
            return $this->render('preventioncreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Prevention model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPreventionupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['preventionview', 'id' => $model->id]);
        } else {
            return $this->render('preventionupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Prevention model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPreventiondelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['preventionindex']);
    }

    /**
     * Finds the Prevention model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Prevention the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Prevention::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
