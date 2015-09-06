<?php

namespace backend\controllers;

use Yii;
use app\models\Department;
use backend\models\departmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DepartmentController implements the CRUD actions for Department model.
 */
class DepartmentController extends Controller
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
     * Lists all Department models.
     * @return mixed
     */
    public function actionDepartmentindex()
    {
        $searchModel = new departmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('departmentindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Department model.
     * @param integer $id
     * @return mixed
     */
    public function actionDepartmentview($id)
    {
        return $this->render('departmentview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Department model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDepartmentcreate()
    {
        $model = new Department();
		
        if ($model->load(Yii::$app->request->post())) {
        	//var_dump($model);
        	$model->membership = implode(',', $model->membership);
        	$model->operableaction = implode(',', $model->operableaction);
        	if($model->save())
            	return $this->redirect(['departmentview', 'id' => $model->id]);
        } else {
            return $this->render('departmentcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Department model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDepartmentupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
        	
        	$model->membership = implode(',', $model->membership);
        	$model->operableaction = implode(',', $model->operableaction);
        	//var_dump($model);
        	if($model->save())
            	return $this->redirect(['departmentview', 'id' => $model->id]);
        } else {
            return $this->render('departmentupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Department model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDepartmentdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['departmentindex']);
    }

    /**
     * Finds the Department model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Department the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Department::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}