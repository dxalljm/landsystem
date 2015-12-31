<?php

namespace frontend\controllers;

use Yii;
use app\models\Inputproductbrandmodel;
use frontend\models\inputproductbrandmodelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\helpers\Pinyin;
/**
 * InputproductbrandmodelController implements the CRUD actions for Inputproductbrandmodel model.
 */
class InputproductbrandmodelController extends Controller
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
     * Lists all Inputproductbrandmodel models.
     * @return mixed
     */
    public function actionInputproductbrandmodelindex()
    {
        $searchModel = new inputproductbrandmodelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('inputproductbrandmodelindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Inputproductbrandmodel model.
     * @param integer $id
     * @return mixed
     */
    public function actionInputproductbrandmodelview($id)
    {
        return $this->render('inputproductbrandmodelview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Inputproductbrandmodel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionInputproductbrandmodelcreate()
    {
        $model = new Inputproductbrandmodel();

        if ($model->load(Yii::$app->request->post())) {
        	$model->brandpinyin = Pinyin::encode($model->brand);
        	$model->save();
            return $this->redirect(['inputproductbrandmodelview', 'id' => $model->id]);
        } else {
            return $this->render('inputproductbrandmodelcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Inputproductbrandmodel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInputproductbrandmodelupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
        	$model->brandpinyin = Pinyin::encode($model->brand);
        	$model->save();
            return $this->redirect(['inputproductbrandmodelview', 'id' => $model->id]);
        } else {
            return $this->render('inputproductbrandmodelupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Inputproductbrandmodel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInputproductbrandmodeldelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['inputproductbrandmodelindex']);
    }

    /**
     * Finds the Inputproductbrandmodel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Inputproductbrandmodel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Inputproductbrandmodel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
   
}
