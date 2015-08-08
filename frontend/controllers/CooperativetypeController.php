<?php

namespace frontend\controllers;

use Yii;
use app\models\Cooperativetype;
use frontend\models\cooperativetypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CooperativetypeController implements the CRUD actions for Cooperativetype model.
 */
class CooperativetypeController extends Controller
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
     * Lists all Cooperativetype models.
     * @return mixed
     */
    public function actionCooperativetypeindex()
    {
        $searchModel = new cooperativetypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('cooperativetypeindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cooperativetype model.
     * @param integer $id
     * @return mixed
     */
    public function actionCooperativetypeview($id)
    {
        return $this->render('cooperativetypeview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Cooperativetype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCooperativetypecreate($farms_id)
    {
        $model = new Cooperativetype();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['cooperative/cooperativecreate','farms_id'=>$farms_id]);
        } else {
            return $this->render('cooperativetypecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Cooperativetype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCooperativetypeupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['cooperativetypeview', 'id' => $model->id]);
        } else {
            return $this->render('cooperativetypeupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Cooperativetype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCooperativetypedelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['cooperativetypeindex']);
    }

    /**
     * Finds the Cooperativetype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cooperativetype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cooperativetype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
