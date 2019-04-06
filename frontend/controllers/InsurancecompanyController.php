<?php

namespace frontend\controllers;

use app\models\Logs;
use Yii;
use app\models\Insurancecompany;
use frontend\models\insurancecompanySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InsurancecompanyController implements the CRUD actions for Insurancecompany model.
 */
class InsurancecompanyController extends Controller
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
    public static function actionName()
    {
    	$result = get_class_methods('InsurancecompanyController');
    	return $result;
    }
    /**
     * Lists all Insurancecompany models.
     * @return mixed
     */
    public function actionInsurancecompanyindex()
    {
        $searchModel = new insurancecompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Logs::writeLogs('保险公司列表');
        return $this->render('insurancecompanyindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Insurancecompany model.
     * @param integer $id
     * @return mixed
     */
    public function actionInsurancecompanyview($id)
    {
        $model = $this->findModel($id);
        Logs::writeLogs('查看保险公司名称',$model);
        return $this->render('insurancecompanyview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Insurancecompany model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionInsurancecompanycreate()
    {
        $model = new Insurancecompany();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Logs::writeLogs('新增保险公司',$model);
            return $this->redirect(['insurancecompanyview', 'id' => $model->id]);
        } else {
            return $this->render('insurancecompanycreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Insurancecompany model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInsurancecompanyupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Logs::writeLogs('更新保险公司',$model);
            return $this->redirect(['insurancecompanyview', 'id' => $model->id]);
        } else {
            return $this->render('insurancecompanyupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Insurancecompany model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInsurancecompanydelete($id)
    {
        $model = $this->findModel($id)->delete();
        Logs::writeLogs('删除保险公司',$model);
        return $this->redirect(['insurancecompanyindex']);
    }

    /**
     * Finds the Insurancecompany model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Insurancecompany the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Insurancecompany::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
