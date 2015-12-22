<?php

namespace frontend\controllers;

use Yii;
use app\models\Projectplan;
use frontend\models\ProjectplanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProjectplanController implements the CRUD actions for Projectplan model.
 */
class ProjectplanController extends Controller
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
     * Lists all Projectplan models.
     * @return mixed
     */
    public function actionProjectplanindex()
    {
        $searchModel = new ProjectplanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('projectplanindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Projectplan model.
     * @param integer $id
     * @return mixed
     */
    public function actionProjectplanview($id)
    {
        return $this->render('projectplanview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Projectplan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionProjectplancreate($id)
    {
        $model = new Projectplan();

        if ($model->load(Yii::$app->request->post())) {
//         	var_dump($_POST);exit;
        	$model->project_id = $id;
        	$model->begindate = strtotime($_POST['Projectplan']['begindate']);
        	$model->enddate = strtotime($_POST['Projectplan']['enddate']);
        	$model->create_at = time();
        	$model->update_at = $model->create_at;
        	$model->state = 1;
        	if($model->save())
            	return $this->redirect(['projectapplication/projectapplicationlist']);
        } else {
            return $this->render('projectplancreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Projectplan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionProjectplanupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['projectplanview', 'id' => $model->id]);
        } else {
            return $this->render('projectplanupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Projectplan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionProjectplandelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['projectplanindex']);
    }

    /**
     * Finds the Projectplan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Projectplan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Projectplan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
