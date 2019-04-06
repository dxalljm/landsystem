<?php

namespace frontend\controllers;

use app\models\Logs;
use Yii;
use app\models\Projectplan;
use frontend\models\ProjectplanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Projectapplication;

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
    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        } else {
            return true;
        }
    }
    /**
     * Lists all Projectplan models.
     * @return mixed
     */
    public function actionProjectplanindex()
    {
        $searchModel = new ProjectplanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Logs::writeLogs('项目情况列表');
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
        $model = $this->findModel($id);
        Logs::writeLogs('查看项目情况',$model);
        return $this->render('projectplanview', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Projectplan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionProjectplancreate($id)
    {
       
		$project = Projectapplication::findOne($id);
		$plant = Projectplan::find()->where(['project_id'=>$id])->one();
		if($plant) {
			$model = Projectplan::findOne($plant['id']);
			$model->update_at = $model->create_at;
		} else {
			$model = new Projectplan();
			$model->create_at = time();
			$model->update_at = $model->create_at;
		}
        if ($model->load(Yii::$app->request->post())) {
//         	var_dump($_POST);exit;
        	$model->project_id = $id;
        	$model->begindate = strtotime($_POST['Projectplan']['begindate']);
        	$model->enddate = strtotime($_POST['Projectplan']['enddate']);
        	
        	$model->state = 1;
        	$model->save();
            Logs::writeLogs('创建项目开工情况',$model);
            return $this->redirect(['projectapplication/projectapplicationlist']);
        } else {
            return $this->render('projectplancreate', [
                'model' => $model,
            	'project' => $project,
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
            Logs::writeLogs('更新项目开工情况',$model);
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
        $model = $this->findModel($id)->delete();
        Logs::writeLogs('删除项目开工情况',$model);
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
