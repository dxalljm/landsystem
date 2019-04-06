<?php

namespace backend\controllers;

use Yii;
use app\models\Processname;
use backend\models\ProcessnameSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProcessnameController implements the CRUD actions for Processname model.
 */
class ProcessnameController extends Controller
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
     * Lists all Processname models.
     * @return mixed
     */
    public function actionProcessnameindex()
    {
        $searchModel = new ProcessnameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('processnameindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Processname model.
     * @param integer $id
     * @return mixed
     */
    public function actionProcessnameview($id)
    {
        return $this->render('processnameview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Processname model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionProcessnamecreate()
    {
        $model = new Processname();

        if ($model->load(Yii::$app->request->post())) {
            if(is_array($model->department_id)) {
                $model->department_id = implode(',',$model->department_id);
            }
            if(is_array($model->level_id)) {
                $model->level_id = implode(',',$model->level_id);
            }
            if($model->save())
                return $this->redirect(['processnameview', 'id' => $model->id]);
        } else {
            return $this->render('processnamecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Processname model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionProcessnameupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if(is_array($model->department_id)) {
                $model->department_id = implode(',',$model->department_id);
            }
            if(is_array($model->level_id)) {
                $model->level_id = implode(',',$model->level_id);
            }
            if($model->save())
                return $this->redirect(['processnameview', 'id' => $model->id]);
        } else {
            return $this->render('processnameupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Processname model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionProcessnamedelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['processnameindex']);
    }

    /**
     * Finds the Processname model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Processname the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Processname::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
