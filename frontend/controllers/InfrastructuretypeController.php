<?php

namespace frontend\controllers;

use Yii;
use app\models\Infrastructuretype;
use frontend\models\infrastructuretypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InfrastructuretypeController implements the CRUD actions for Infrastructuretype model.
 */
class InfrastructuretypeController extends Controller
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
     * Lists all Infrastructuretype models.
     * @return mixed
     */
    public function actionInfrastructuretypeindex()
    {
        $searchModel = new infrastructuretypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('infrastructuretypeindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Infrastructuretype model.
     * @param integer $id
     * @return mixed
     */
    public function actionInfrastructuretypeview($id)
    {
        return $this->render('infrastructuretypeview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Infrastructuretype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionInfrastructuretypecreate()
    {
        $model = new Infrastructuretype();

        if ($model->load(Yii::$app->request->post())) {
        	$model->father_id = Yii::$app->request->post['infrastructureFahterPost'];
        	$model->save();
            return $this->redirect(['infrastructuretypeview', 'id' => $model->id]);
        } else {
            return $this->render('infrastructuretypecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Infrastructuretype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInfrastructuretypeupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['infrastructuretypeview', 'id' => $model->id]);
        } else {
            return $this->render('infrastructuretypeupdate', [
                'model' => $model,
            ]);
        }
    }

    public function actionGetson($father_id)
    {
    	$result = Infrastructuretype::find()->where(['father_id'=>$father_id])->all();
    	foreach($result as $key=>$val){
    		$newData[$key] = $val->attributes;
    	}
    	if($result)
    		$son = 1;
    	else 
    		$son = 0;
		echo json_encode(['son'=>$son,'data'=>$newData]);
    }
    
    /**
     * Deletes an existing Infrastructuretype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInfrastructuretypedelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['infrastructuretypeindex']);
    }

    /**
     * Finds the Infrastructuretype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Infrastructuretype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Infrastructuretype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}