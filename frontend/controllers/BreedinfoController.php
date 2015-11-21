<?php

namespace frontend\controllers;

use Yii;
use app\models\Breedinfo;
use frontend\models\breedinfoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BreedinfoController implements the CRUD actions for Breedinfo model.
 */
class BreedinfoController extends Controller
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
    	$action = Yii::$app->controller->action->id;
    	if(\Yii::$app->user->can($action)){
    		return true;
    	}else{
    		throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
    	}
    }
    
    /**
     * Lists all Breedinfo models.
     * @return mixed
     */
    public function actionBreedinfoindex()
    {
        $searchModel = new breedinfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('breedinfoindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Breedinfo model.
     * @param integer $id
     * @return mixed
     */
    public function actionBreedinfoview($id)
    {
        return $this->render('breedinfoview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Breedinfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBreedinfocreate()
    {
        $model = new Breedinfo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['breedinfoview', 'id' => $model->id]);
        } else {
            return $this->render('breedinfocreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Breedinfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBreedinfoupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['breedinfoview', 'id' => $model->id]);
        } else {
            return $this->render('breedinfoupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Breedinfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBreedinfodelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['breedinfoindex']);
    }

    /**
     * Finds the Breedinfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Breedinfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Breedinfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
