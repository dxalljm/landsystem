<?php

namespace frontend\controllers;

use Yii;
use app\models\Dispute;
use frontend\models\disputeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DisputeController implements the CRUD actions for Dispute model.
 */
class DisputeController extends Controller
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
     * Lists all Dispute models.
     * @return mixed
     */
    public function actionDisputeindex($farms_id)
    {
        $searchModel = new disputeSearch();
        $dataProvider = $searchModel->search(['farms_id'=>$farms_id]);

        return $this->render('disputeindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Dispute model.
     * @param integer $id
     * @return mixed
     */
    public function actionDisputeview($id)
    {
        return $this->render('disputeview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Dispute model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDisputecreate()
    {
        $model = new Dispute();

        if ($model->load(Yii::$app->request->post())) {
        	$model->create_at = time();
        	$model->update_at = time();
        	$model->save();
            return $this->redirect(['disputeview', 'id' => $model->id]);
        } else {
            return $this->render('disputecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Dispute model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDisputeupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->save();
            return $this->redirect(['disputeview', 'id' => $model->id]);
        } else {
            return $this->render('disputeupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Dispute model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDisputedelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['disputeindex']);
    }

    /**
     * Finds the Dispute model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dispute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dispute::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
