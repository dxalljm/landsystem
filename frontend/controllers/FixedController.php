<?php

namespace frontend\controllers;

use app\models\Fixedtype;
use app\models\Logs;
use console\models\Farms;
use Yii;
use app\models\Fixed;
use frontend\models\FixedSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FixedController implements the CRUD actions for Fixed model.
 */
class FixedController extends Controller
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
     * Lists all Fixed models.
     * @return mixed
     */
    public function actionFixedindex($farms_id)
    {
        $searchModel = new FixedSearch();
        $farm = Farms::findOne($farms_id);
        $param = Yii::$app->request->queryParams;
        $param['FixedSearch']['cardid'] = $farm->cardid;
        $dataProvider = $searchModel->search($param);

        return $this->render('fixedindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Fixed model.
     * @param integer $id
     * @return mixed
     */
    public function actionFixedview($id)
    {
        return $this->render('fixedview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Fixed model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionFixedcreate($farms_id)
    {
        $model = new Fixed();
        $farm = Farms::findOne($farms_id);
        if ($model->load(Yii::$app->request->post())) {
            $typename = Fixedtype::find()->where(['typename'=>$model->typeid])->one();
            if($typename) {
                $model->typeid = (string)$typename['id'];
            } else {
                $typeModel = new Fixedtype();
                $typeModel->typename = $model->typeid;
                $typeModel->save();
                $model->typeid = (string)$typeModel->id;
            }
            $model->farms_id = $farms_id;
            $model->cardid = $farm->cardid;
            $model->management_area = $farm['management_area'];
            $model->save();
            return $this->redirect(['fixedindex', 'farms_id' => $farms_id]);
        } else {
            return $this->render('fixedcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Fixed model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFixedupdate($id)
    {
        $model = $this->findModel($id);
        $farms_id = $model->farms_id;
        if ($model->load(Yii::$app->request->post())) {
            $typename = Fixedtype::find()->where(['typename'=>$model->typeid])->one();
            if($typename) {
                $model->typeid = (string)$typename['id'];
            } else {
                $typeModel = new Fixedtype();
                $typeModel->typename = $model->typeid;
                $typeModel->save();
                $model->typeid = (string)$typeModel->id;
            }
            $model->save();
            return $this->redirect(['fixedindex', 'farms_id' => $farms_id]);
        } else {
            return $this->render('fixedupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Fixed model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFixeddelete($id)
    {
        $model = $this->findModel($id);
        Logs::writeLogs('删除固定资产',$model);
        $farms_id = $model->farms_id;
        $model->delete();

        return $this->redirect(['fixedindex','farms_id'=>$farms_id]);
    }

    /**
     * Finds the Fixed model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Fixed the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fixed::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
