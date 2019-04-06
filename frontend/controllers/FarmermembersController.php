<?php

namespace frontend\controllers;

use Yii;
use app\models\Farmermembers;
use frontend\models\farmermembersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FarmermembersController implements the CRUD actions for Farmermembers model.
 */
class FarmermembersController extends Controller
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
//     public function beforeAction($action)
//     {
//     	$action = Yii::$app->controller->action->id;
//     	if(\Yii::$app->user->can($action)){
//     		return true;
//     	}else{
//     		throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
//     	}
//     }
    /**
     * Lists all Farmermembers models.
     * @return mixed
     */
    public function actionFarmermembersindex()
    {
        $searchModel = new farmermembersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('farmermembersindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Farmermembers model.
     * @param integer $id
     * @return mixed
     */
    public function actionFarmermembersview($id)
    {
        return $this->render('farmermembersview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Farmermembers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionFarmermemberscreate()
    {
        $model = new Farmermembers();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['farmermembersview', 'id' => $model->id]);
        } else {
            return $this->render('farmermemberscreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Farmermembers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFarmermembersupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['farmermembersview', 'id' => $model->id]);
        } else {
            return $this->render('farmermembersupdate', [
                'model' => $model,
            ]);
        }
    }

    
    /**
     * Deletes an existing Farmermembers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFarmermembersdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['farmermembersindex']);
    }

    /**
     * Finds the Farmermembers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Farmermembers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Farmermembers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
