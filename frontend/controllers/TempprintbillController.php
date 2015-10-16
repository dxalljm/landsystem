<?php

namespace frontend\controllers;

use Yii;
use app\models\Tempprintbill;
use frontend\models\tempprintbillSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\helpers\MoneyFormat;
use app\models\Logs;
/**
 * TempprintbillController implements the CRUD actions for Tempprintbill model.
 */
class TempprintbillController extends Controller
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
     * Lists all Tempprintbill models.
     * @return mixed
     */
    public function actionTempprintbillindex()
    {
        $searchModel = new tempprintbillSearch();
        $params = Yii::$app->request->queryParams;
        $params['tempprintbillSearch']['state'] = 0;
        
        $dataProvider = $searchModel->search($params);
        $create_at = Tempprintbill::find()->orderBy('id DESC','LIMIT=1')->one()['create_at'];
		$billSum = Tempprintbill::find()->where(['state'=>0])->sum('amountofmoneys');
		//var_dump($billSum);
        return $this->render('tempprintbillindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'billSum' => $billSum,
        	'create_at' => $create_at,
        ]);
    }

    public function actionTempprintbillscrap()
    {
    	$searchModel = new tempprintbillSearch();
    	$params = Yii::$app->request->queryParams;
    	$params['tempprintbillSearch']['state'] = 1;
    
    	$dataProvider = $searchModel->search($params);
    	$create_at = Tempprintbill::find()->orderBy('id DESC','LIMIT=1')->one()['create_at'];
    	//$billSum = Tempprintbill::find()->where(['state'=>1])->sum('amountofmoneys');
    	//var_dump($billSum);
    	return $this->render('tempprintbillscrap', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			//'billSum' => $billSum,
    			'create_at' => $create_at,
    	]);
    }
    
    /**
     * Displays a single Tempprintbill model.
     * @param integer $id
     * @return mixed
     */
    public function actionTempprintbillview($id)
    {
        return $this->render('tempprintbillview', [
            'model' => $this->findModel($id),
        ]);
    }
    
    public function actionTempprintbillsview($id)
    {
    	return $this->render('tempprintbillsview', [
    			'model' => $this->findModel($id),
    	]);
    }

    /**
     * Creates a new Tempprintbill model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionTempprintbillcreate()
    {
        $model = new Tempprintbill();
		$nonumber = Tempprintbill::find()->orderBy('id DESC','LIMIT=1')->one()['nonumber'];
		//var_dump(++$nonumber);
		//exit;
        if ($model->load(Yii::$app->request->post())) {
        	$model->create_at = strtotime($model->create_at.' '.date("H:m:s"));
        	$model->update_at = time();
        	$model->save();
        	//var_dump($model->getErrors());
        	$new = $model->attributes;
        	Logs::writeLog('新增票据打印',$model->id,'',$new);
            return $this->redirect(['tempprintbillview', 'id' => $model->id]);
        } else {
            return $this->render('tempprintbillcreate', [
                'model' => $model,
            	'nonumber' => ++$nonumber,
            ]);
        }
    }

    public function actionFormat($number)
    {
    	$cny = MoneyFormat::cny($number);
    	$numformat = MoneyFormat::num_format($number);
    	echo json_encode(['cny'=>$cny,'num'=>$numformat]);
    }
    
    /**
     * Updates an existing Tempprintbill model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTempprintbillupdate($id)
    {
        $model = $this->findModel($id);
		$old = $model->attributes;
        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->state = 1;
        	$model->save();
        	$new = $model->attributes;
        	Logs::writeLog('报废',$id,$old,$new);
            return $this->redirect(['tempprintbillindex']);
        } else {
            return $this->render('tempprintbillupdate', [
                'model' => $model,
            	'nonumber' => $model->nonumber,
            ]);
        }
    }

    /**
     * Deletes an existing Tempprintbill model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTempprintbilldelete($id)
    {
        $model = $this->findModel($id);
        $model->state = 1;
        $model->save();
		
        return $this->redirect(['tempprintbillindex']);
    }

    /**
     * Finds the Tempprintbill model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tempprintbill the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tempprintbill::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
