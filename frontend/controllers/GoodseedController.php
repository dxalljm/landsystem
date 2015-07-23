<?php

namespace frontend\controllers;

use Yii;
use app\models\Goodseed;
use frontend\models\goodseedSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GoodseedController implements the CRUD actions for Goodseed model.
 */
class GoodseedController extends Controller
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
     * Lists all Goodseed models.
     * @return mixed
     */
    public function actionGoodseedindex()
    {
        $searchModel = new goodseedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('goodseedindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Goodseed model.
     * @param integer $id
     * @return mixed
     */
    public function actionGoodseedview($id)
    {
        return $this->render('goodseedview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Goodseed model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionGoodseedcreate()
    {
        $model = new Goodseed();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['goodseedview', 'id' => $model->id]);
        } else {
            return $this->render('goodseedcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Goodseed model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionGoodseedupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['goodseedview', 'id' => $model->id]);
        } else {
            return $this->render('goodseedupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Goodseed model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionGoodseeddelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['goodseedindex']);
    }

    /**
     * Finds the Goodseed model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Goodseed the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Goodseed::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
