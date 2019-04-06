<?php

namespace frontend\controllers;

use Yii;
use app\models\Help;
use frontend\models\helpSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HelpController implements the CRUD actions for Help model.
 */
class HelpController extends Controller
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
     * Lists all Help models.
     * @return mixed
     */
    public function actionHelpindex()
    {
        $searchModel = new helpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('helpindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Help model.
     * @param integer $id
     * @return mixed
     */
    public function actionHelpview($id)
    {
        return $this->render('helpview', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionHelptips($mark)
    {
        $help = Help::find()->where(['mark'=>$mark])->one();
        return $this->render('helptips', [
            'help' => $help,
        ]);
    }
    /**
     * Creates a new Help model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionHelpcreate()
    {
        $model = new Help();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['helpview', 'id' => $model->id]);
        } else {
            return $this->render('helpcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Help model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionHelpupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['helpview', 'id' => $model->id]);
        } else {
            return $this->render('helpupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Help model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionHelpdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['helpindex']);
    }

    /**
     * Finds the Help model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Help the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Help::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
