<?php

namespace frontend\controllers;

<<<<<<< HEAD
use app\models\Logs;
=======
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
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
<<<<<<< HEAD
    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        } else {
            return true;
        }
    }
=======

>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
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
<<<<<<< HEAD
        Logs::writeLogs('保险公司列表');
=======

>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
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
<<<<<<< HEAD
        $model = $this->findModel($id);
        Logs::writeLogs('查看保险公司名称',$model);
=======
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
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
<<<<<<< HEAD
            Logs::writeLogs('新增保险公司',$model);
=======
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
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
<<<<<<< HEAD
            Logs::writeLogs('更新保险公司',$model);
=======
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
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
<<<<<<< HEAD
        $model = $this->findModel($id)->delete();
        Logs::writeLogs('删除保险公司',$model);
=======
        $this->findModel($id)->delete();

>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
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
