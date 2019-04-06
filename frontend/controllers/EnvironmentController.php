<?php

namespace frontend\controllers;

use app\models\Creditscore;
use app\models\User;
use console\models\Farms;
use Yii;
use app\models\Environment;
use frontend\models\EnvironmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EnvironmentController implements the CRUD actions for Environment model.
 */
class EnvironmentController extends Controller
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
     * Lists all Environment models.
     * @return mixed
     */
    public function actionEnvironmentindex()
    {
        $searchModel = new EnvironmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('environmentindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSetenvironment($farms_id,$value)
    {
        $farm = Farms::findOne($farms_id);
        $model = Environment::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
        if(empty($model)) {
            $model = new Environment();
            $model->farms_id = $farms_id;
            $model->management_area = $farm->management_area;
            $model->contractarea = $farm->contractarea;
            $model->contractnumber = $farm->contractnumber;
            $model->state = $farm->state;
            $model->isgovernment = $value;
            $model->create_at = time();
            $model->update_at = $model->create_at;
            $model->year = User::getYear();
        } else {
            $model->isgovernment = $value;
            $model->update_at = time();
        }
        $state = $model->save();
        Creditscore::run($farms_id,$state);
        echo json_encode(['state'=>$state]);
    }
    /**
     * Displays a single Environment model.
     * @param integer $id
     * @return mixed
     */
    public function actionEnvironmentview($id)
    {
        return $this->render('environmentview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Environment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionEnvironmentcreate()
    {
        $model = new Environment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['environmentview', 'id' => $model->id]);
        } else {
            return $this->render('environmentcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Environment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEnvironmentupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['environmentview', 'id' => $model->id]);
        } else {
            return $this->render('environmentupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Environment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEnvironmentdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['environmentindex']);
    }

    /**
     * Finds the Environment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Environment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Environment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
