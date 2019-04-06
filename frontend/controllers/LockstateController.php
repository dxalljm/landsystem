<?php

namespace frontend\controllers;

use app\models\Farms;
use app\models\Logs;
use Yii;
use app\models\Lockstate;
use frontend\models\LockstateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LockstateController implements the CRUD actions for Lockstate model.
 */
class LockstateController extends Controller
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
     * Lists all Lockstate models.
     * @return mixed
     */
    public function actionLockstateindex()
    {
        $searchModel = new LockstateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('lockstateindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lockstate model.
     * @param integer $id
     * @return mixed
     */
    public function actionLockstateview($id)
    {
        return $this->render('lockstateview', [
            'model' => $this->findModel($id),
        ]);
    }
    public function actionLockstateunset($farms_id)
    {
        $array = [];
        $model = $this->findModel(1);
        if($model->notuserloan) {
            $array = explode(',',$model->notuserloan);
            $array[] = $farms_id.'-'.time();
            $model->notuserloan = implode(',',$array);
        } else {
            $array[] = $farms_id.'-'.time();
            $model->notuserloan = $farms_id.'-'.time();
        }
        foreach ($array as $key => $value) {
            $val = explode('-',$value);
            $farmsid[$key] = $val[0];
            $farmsidTime[$key] = $val[1];
        }
        if(in_array($farms_id,$farmsid)) {
            $model->save();
        }

        return $this->redirect(['farms/farmsland']);
    }

    /**
     * Creates a new Lockstate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionLockstatecreate()
    {
        $model = new Lockstate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['lockstateview', 'id' => $model->id]);
        } else {
            return $this->render('lockstatecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Lockstate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionLockstateupdate()
    {
        $model = $this->findModel(1);

        if ($model->load(Yii::$app->request->post())) {
//            var_dump($model->systemstatedate);
            if(!empty($model->systemstatedate)) {
                $model->systemstatedate = (string)strtotime($model->systemstatedate);
            }
            if(!empty($model->platestate)) {
                $model->platestate = implode(',', $model->platestate);
            }
            $model->save();
            Logs::writeLogs('系统冻结配置',$model);
//            var_dump($model->getErrors());exit;
            return $this->redirect(['lockstateview', 'id' => $model->id]);
        } else {
            return $this->render('lockstateupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Lockstate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionLockstatedelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['lockstateindex']);
    }

    /**
     * Finds the Lockstate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lockstate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lockstate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
