<?php

namespace frontend\controllers;

use Yii;
use app\models\Reviewprocess;
use frontend\models\ReviewprocessSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use app\models\Auditprocess;
/**
 * ReviewprocessController implements the CRUD actions for Reviewprocess model.
 */
class ReviewprocessController extends Controller
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

    /**
     * Lists all Reviewprocess models.
     * @return mixed
     */
    public function actionReviewprocessindex()
    {
        $searchModel = new ReviewprocessSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('reviewprocessindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Reviewprocess model.
     * @param integer $id
     * @return mixed
     */
  
    public function actionReviewprocessfarmstransfer($newfarmsid,$oldfarmsid,$actionname,$state = 0)
    {
    	$model = new Reviewprocess();
    	$newfarm = Farms::find()->where(['id'=>$newfarmsid])->one();
    	$oldfarm = Farms::find()->where(['id'=>$oldfarmsid])->one();
    	$process = Auditprocess::find()->where(['actionname'=>$actionname])->one()['process'];
    	
    	return $this->render ( 'reviewprocessfarmstransfer', [
    			'oldfarm' => $oldfarm,
    			'newfarm' => $newfarm,
    			'process' => explode('>', $process),
    			'model' => $model,
    			'state' => $state,
    	] );
    }

    /**
     * Finds the Reviewprocess model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reviewprocess the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reviewprocess::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
