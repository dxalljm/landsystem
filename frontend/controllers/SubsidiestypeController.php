<?php

namespace frontend\controllers;

use app\models\Logs;
use Yii;
use app\models\Subsidiestype;
use frontend\models\subsidiestypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubsidiestypeController implements the CRUD actions for Subsidiestype model.
 */
class SubsidiestypeController extends Controller
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
    /**
     * Lists all Subsidiestype models.
     * @return mixed
     */
    public function actionSubsidiestypeindex()
    {
        $searchModel = new subsidiestypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('subsidiestypeindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Subsidiestype model.
     * @param integer $id
     * @return mixed
     */
    public function actionSubsidiestypeview($id)
    {
        return $this->render('subsidiestypeview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Subsidiestype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSubsidiestypecreate()
    {
        $model = new Subsidiestype();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['subsidiestypeview', 'id' => $model->id]);
        } else {
            return $this->render('subsidiestypecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Subsidiestype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSubsidiestypeupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['subsidiestypeview', 'id' => $model->id]);
        } else {
            return $this->render('subsidiestypeupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Subsidiestype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSubsidiestypedelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['subsidiestypeindex']);
    }

    /**
     * Finds the Subsidiestype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Subsidiestype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subsidiestype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
