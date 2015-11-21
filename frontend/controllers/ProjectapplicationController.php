<?php

namespace frontend\controllers;

use Yii;
use app\models\Projectapplication;
use frontend\models\projectapplicationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProjectapplicationController implements the CRUD actions for Projectapplication model.
 */
class ProjectapplicationController extends Controller
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
     * Lists all Projectapplication models.
     * @return mixed
     */
    public function actionProjectapplicationindex()
    {
        $searchModel = new projectapplicationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('projectapplicationindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Projectapplication model.
     * @param integer $id
     * @return mixed
     */
    public function actionProjectapplicationview($id)
    {
        return $this->render('projectapplicationview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Projectapplication model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionProjectapplicationcreate()
    {
        $model = new Projectapplication();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['projectapplicationview', 'id' => $model->id]);
        } else {
            return $this->render('projectapplicationcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Projectapplication model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionProjectapplicationupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['projectapplicationview', 'id' => $model->id]);
        } else {
            return $this->render('projectapplicationupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Projectapplication model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionProjectapplicationdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['projectapplicationindex']);
    }

    /**
     * Finds the Projectapplication model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Projectapplication the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Projectapplication::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
