<?php

namespace frontend\controllers;

use Yii;
use app\models\Nation;
use frontend\models\nationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NationController implements the CRUD actions for Nation model.
 */
class NationController extends Controller
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
     * Lists all Nation models.
     * @return mixed
     */
    public function actionNationindex()
    {
        $searchModel = new nationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('nationindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Nation model.
     * @param integer $id
     * @return mixed
     */
    public function actionNationview($id)
    {
        return $this->render('nationview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Nation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionNationcreate()
    {
        $model = new Nation();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['nationview', 'id' => $model->id]);
        } else {
            return $this->render('nationcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Nation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionNationupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['nationview', 'id' => $model->id]);
        } else {
            return $this->render('nationupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Nation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionNationdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['nationindex']);
    }

    /**
     * Finds the Nation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Nation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Nation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
