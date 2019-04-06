<?php

namespace backend\controllers;

use Yii;
use app\models\Groups;
use backend\models\groupsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GroupsController implements the CRUD actions for Groups model.
 */
class GroupsController extends Controller
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
     * Lists all Groups models.
     * @return mixed
     */
    public function actionGroupsindex()
    {
        $searchModel = new groupsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('groupsindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Groups model.
     * @param integer $id
     * @return mixed
     */
    public function actionGroupsview($id)
    {
        return $this->render('groupsview', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionGroupsrole()
    {
    	return $this->render('groupsrole', [
            'model' => $this->findModel($id),
        ]);
    }
    
    /**
     * Creates a new Groups model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionGroupscreate()
    {
        $model = new Groups();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['groupsview', 'id' => $model->id]);
        } else {
            return $this->render('groupscreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Groups model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionGroupsupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['groupsview', 'id' => $model->id]);
        } else {
            return $this->render('groupsupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Groups model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionGroupsdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['groupsindex']);
    }

    /**
     * Finds the Groups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Groups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Groups::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
