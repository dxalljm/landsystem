<?php

namespace frontend\controllers;

use Yii;
use app\models\Fireprevention;
use frontend\models\firepreventionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Employee;
use app\models\Lease;
use app\models\Firepreventionemployee;
use app\models\Logs;
/**
 * FirepreventionController implements the CRUD actions for Fireprevention model.
 */
class FirepreventionController extends Controller
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
     * Lists all Fireprevention models.
     * @return mixed
     */
    public function actionFirepreventionindex()
    {
        $searchModel = new firepreventionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		Logs::writeLog('防火工作');
        return $this->render('firepreventionindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Fireprevention model.
     * @param integer $id
     * @return mixed
     */
    public function actionFirepreventionview($id,$farms_id)
    {
    	$lease = Lease::find()->where(['farms_id'=>$farms_id])->all();
    	foreach($lease as $val) {
    		$employees[] = Employee::find()->where(['father_id'=>$val['id']])->all();
    	}
    	Logs::writeLog('查看防火工作',$id);
        return $this->render('firepreventionview', [
            'model' => $this->findModel($id),
        	'employees' => $employees,
        ]);
    }

    /**
     * Creates a new Fireprevention model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionFirepreventioncreate($farms_id)
    {
    	$oldAttr = '';
    	if($this->findFarmsModel($farms_id)) {
    		$model = $this->findFarmsModel($farms_id);
    		$model->update_at = time();
    		$oldAttr = $model->attributes;
    	}
    	else {
    		$model = new Fireprevention();
    		$model->create_at = time();
    		$model->update_at = time();
    	}

        $lease = Lease::find()->where(['farms_id'=>$farms_id])->all();
		foreach($lease as $val) {
			$employees[] = Employee::find()->where(['father_id'=>$val['id']])->all();
		}
		
        if ($model->load(Yii::$app->request->post())) { 	
        	$model->save();
        	$newAttr = $model->attributes;
        	Logs::writeLog('添加防火信息',$model->id,$oldAttr,$newAttr);
        $ArrEmployeesFire = Yii::$app->request->post('ArrEmployeesFire');
        $row = count($ArrEmployeesFire['id']);
        $old = '';
        for($i=0;$i<$row;$i++) {
        	if($this->findFirepreventionemployeeModel($ArrEmployeesFire['id'][$i])) {
        		$fireemployeeModel = $this->findFirepreventionemployeeModel($ArrEmployeesFire['id'][$i]);
        		$old = $fireemployeeModel->attributes;
        		$fireemployeeModel->update_at = time();
        		$message = '更新雇工防火信息';
        	}
        	else {
        		$fireemployeeModel = new Firepreventionemployee();
        		$fireemployeeModel->create_at = time();
        		$fireemployeeModel->update_at = time();
        		$message = '创建雇工防火信息';
        	}
        	$fireemployeeModel->employee_id = $ArrEmployeesFire['employee_id'][$i];
        	$fireemployeeModel->is_smoking = $ArrEmployeesFire['is_smoking'][$i];
        	$fireemployeeModel->is_retarded = $ArrEmployeesFire['is_retarded'][$i];
        	$new = $fireemployeeModel->attributes;
        	$fireemployeeModel->save();
        	Logs::writeLog($message,$fireemployeeModel->id,$old,$new);
        	
        }
            return $this->redirect(['firepreventionview', 'id' => $model->id,'farms_id'=>$farms_id]);
        } else {
            return $this->render('firepreventioncreate', [
                'model' => $model,
            	'employees' => $employees,
            	//'fireemployeeModel' => $fireemployeeModel,
            ]);
        }
    }

    /**
     * Updates an existing Fireprevention model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFirepreventionupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['firepreventionview', 'id' => $model->id]);
        } else {
            return $this->render('firepreventionupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Fireprevention model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFirepreventiondelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['firepreventionindex']);
    }

    /**
     * Finds the Fireprevention model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Fireprevention the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fireprevention::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    //判断是否存在farms_id的数据
    protected function findFarmsModel($farms_id)
    {
    	if (($model = Fireprevention::find()->where(['farms_id'=>$farms_id])->one()) !== null) {
    		return $model;
    	} else {
    		return false;
    	}
    }
    
    protected function findFirepreventionemployeeModel($id)
    {
    	if (($model = Firepreventionemployee::findOne($id)) !== null) {
    		//print_r($model);
    		return $model;
    	} else {
    		return false;
    	}
    }
}
