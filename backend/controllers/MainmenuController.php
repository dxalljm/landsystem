<?php

namespace backend\controllers;

use Yii;
use app\models\mainmenu;
use backend\models\mainmenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MainmenuController implements the CRUD actions for mainmenu model.
 */
class MainmenuController extends Controller
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
     * Lists all mainmenu models.
     * @return mixed
     */
    public function actionMainmenuindex()
    {
        $searchModel = new mainmenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('mainmenuindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single mainmenu model.
     * @param integer $id
     * @return mixed
     */
    public function actionMainmenuview($id)
    {
        return $this->render('mainmenuview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new mainmenu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionMainmenucreate()
    {
        $model = new mainmenu();
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['mainmenuview', 'id' => $model->id]);
        } else {
            return $this->render('mainmenucreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing mainmenu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMainmenuupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['mainmenuview', 'id' => $model->id]);
        } else {
            return $this->render('mainmenuupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing mainmenu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMainmenudelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['mainmenuindex']);
    }

    /**
     * Finds the mainmenu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return mainmenu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = mainmenu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
