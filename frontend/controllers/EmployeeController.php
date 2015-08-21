<?php

namespace frontend\controllers;

use Yii;
use app\models\Employee;
use frontend\models\employeeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farmer;
use app\models\Lease;
use frontend\models\leaseSearch;
/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
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

    public function actionEmployeefathers($farms_id)
    {
    	
    	$lease = Lease::find()->where(['farms_id'=>$farms_id])->all();
		//$this->getView()->registerJsFile($url)
        return $this->render('employeefathers', [
             'lease' => $lease,
        ]);
    }
    
    /**
     * Lists all Employee models.
     * @return mixed
     */
    public function actionEmployeeindex()
    {
        $searchModel = new employeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('employeeindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Employee model.
     * @param integer $id
     * @return mixed
     */
    public function actionEmployeeview($id)
    {
        return $this->render('employeeview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionEmployeecreate($farms_id)
    {
        $model = new Employee();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['employeefathers', 'farms_id' => $farms_id]);
        } else {
            return $this->render('employeecreate', [
                'model' => $model,
            ]);
        }
    }
	//批量添加方法
    public function actionEmployeebatch($father_id,$farms_id)
    {

        $model = new Employee();
    	$employees = Employee::find()->where(['father_id'=>$father_id])->all();


        $EmployeesPost = Yii::$app->request->post('EmployeesPost');
    	if ($EmployeesPost) {
            // 批量添加
            Employee::batchAdd($EmployeesPost);
	    	return $this->redirect(['employeefathers', 'farms_id' => $farms_id]);
    	} else {

	    	return $this->render('employeebatch', [
	    			'model' => $model,
	    			'employees' => $employees,
	    	]);
    	}
    }
    
    /**
     * Updates an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEmployeeupdate($id,$farms_id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['employeefathers', 'farms_id' => $farms_id]);
        } else {
            return $this->render('employeeupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Employee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEmployeedelete($id,$farms_id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['employeefathers', 'farms_id' => $farms_id]);
    }

    /**
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function getAreas($id) {
    	$areas = 0;
    	if(($model = Lease::find()->where(['farms_id'=>$id])->all()) !== null) {
    		foreach($model as $val) {
    			$areas+=$val['lease_area'];
    		}
    	}
    	return $areas;
    }
}
