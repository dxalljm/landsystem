<?php

namespace frontend\controllers;

use Yii;
use app\models\Firepreventionemployee;
use frontend\models\firepreventionemployeeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FirepreventionemployeeController implements the CRUD actions for Firepreventionemployee model.
 */
class FirepreventionemployeeController extends Controller
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
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        } else {
            return true;
        }
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
     * Lists all Firepreventionemployee models.
     * @return mixed
     */
    public function actionFirepreventionemployeeindex()
    {
        $searchModel = new firepreventionemployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('firepreventionemployeeindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Firepreventionemployee model.
     * @param integer $id
     * @return mixed
     */
    public function actionFirepreventionemployeeview($id)
    {
        return $this->render('firepreventionemployeeview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Firepreventionemployee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionFirepreventionemployeecreate()
    {
        $model = new Firepreventionemployee();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['firepreventionemployeeview', 'id' => $model->id]);
        } else {
            return $this->render('firepreventionemployeecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Firepreventionemployee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFirepreventionemployeeupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['firepreventionemployeeview', 'id' => $model->id]);
        } else {
            return $this->render('firepreventionemployeeupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Firepreventionemployee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFirepreventionemployeedelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['firepreventionemployeeindex']);
    }

    /**
     * Finds the Firepreventionemployee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Firepreventionemployee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Firepreventionemployee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
