<?php

namespace frontend\controllers;

use Yii;
use app\models\Machinescanning;
use frontend\models\MachinescanningSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MachinescanningController implements the CRUD actions for Machinescanning model.
 */
class MachinescanningController extends Controller
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
     * Lists all Machinescanning models.
     * @return mixed
     */
    public function actionMachinescanningindex()
    {
        $searchModel = new MachinescanningSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('machinescanningindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Machinescanning model.
     * @param integer $id
     * @return mixed
     */
    public function actionMachinescanningview($id)
    {
        return $this->render('machinescanningview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Machinescanning model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionMachinescanningcreate()
    {
        $model = new Machinescanning();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['machinescanningview', 'id' => $model->id]);
        } else {
            return $this->render('machinescanningcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Machinescanning model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMachinescanningupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['machinescanningview', 'id' => $model->id]);
        } else {
            return $this->render('machinescanningupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Machinescanning model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMachinescanningdelete($id)
    {
        $model = $this->findModel($id);
//        $farms_id = $model->farms_id;
        if(file_exists(iconv("UTF-8","gbk//TRANSLIT", $model->scanimage))){
            unlink(iconv("UTF-8","gbk//TRANSLIT", $model->scanimage));
        }
        $model->delete();
        echo json_encode(['id'=>$model->id,'select'=>'machinescanning-scanimage']);
    }

    /**
     * Finds the Machinescanning model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Machinescanning the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Machinescanning::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
