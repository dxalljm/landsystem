<?php

namespace frontend\controllers;

use Yii;
use app\models\Afterchenqian;
use frontend\models\AfterchenqianSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use backend\models\farmsSearch;

/**
 * AfterchenqianController implements the CRUD actions for Afterchenqian model.
 */
class AfterchenqianController extends Controller
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
     * Lists all Afterchenqian models.
     * @return mixed
     */
    public function actionAfterchenqianindex()
    {
        $searchModel = new farmsSearch();
        $params = Yii::$app->request->queryParams;
        $where = Farms::getManagementArea()['id'];
        $params['farmsSearch']['management_area'] = $where;
        $dataProvider = $searchModel->search($params);

        return $this->render('afterchenqianindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Afterchenqian model.
     * @param integer $id
     * @return mixed
     */
    public function actionAfterchenqianview($id)
    {
        return $this->render('afterchenqianview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Afterchenqian model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAfterchenqiancreate($farms_id)
    {
        $model = new Afterchenqian();

        if ($model->load(Yii::$app->request->post())) {
        	$model->farms_id = $farms_id;
        	$model->management_area = Farms::getFarmsAreaID($farms_id);
        	$model->save();
            return $this->redirect(['afterchenqianview', 'id' => $model->id]);
        } else {
            return $this->render('afterchenqiancreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Afterchenqian model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionAfterchenqianupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['afterchenqianview', 'id' => $model->id]);
        } else {
            return $this->render('afterchenqianupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Afterchenqian model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionAfterchenqiandelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['afterchenqianindex']);
    }

    /**
     * Finds the Afterchenqian model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Afterchenqian the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Afterchenqian::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
