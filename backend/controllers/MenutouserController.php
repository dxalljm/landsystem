<?php

namespace backend\controllers;

use Yii;
use app\models\MenuToUser;
use backend\models\menutouserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenutouserController implements the CRUD actions for MenuToUser model.
 */
class MenutouserController extends Controller
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
     * Lists all MenuToUser models.
     * @return mixed
     */
    public function actionMenutouserindex()
    {
        $searchModel = new menutouserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('menutouserindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Menutouser model.
     * @param integer $id
     * @return mixed
     */
    public function actionMenutouserview($id)
    {
        return $this->render('menutouserview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MenuToUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionMenutousercreate()
    {
        $model = new MenuToUser();
		
        if ($model->load(Yii::$app->request->post())) {
        	if(is_array($model->menulist))
        		$model->menulist = implode(',', $model->menulist);
        	if(is_array($model->plate))
        		$model->plate = implode(',',$model->plate);
        	if(is_array($model->businessmenu))
        		$model->businessmenu = implode(',',$model->businessmenu);
        	if(is_array($model->searchmenu))
        		$model->searchmenu = implode(',', $model->searchmenu);
            if(is_array($model->auditinguser))
                $model->auditinguser = implode(',', $model->auditinguser);
        	$model->save();
            return $this->redirect(['menutouserview', 'id' => $model->id]);
        } else {
            return $this->render('menutousercreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Menutouser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMenutouserupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
        	if(is_array($model->menulist))
        		$model->menulist = implode(',', $model->menulist);
        	if(is_array($model->plate))
        		$model->plate = implode(',',$model->plate);
        	if(is_array($model->businessmenu))
        		$model->businessmenu = implode(',',$model->businessmenu);
        	if(is_array($model->searchmenu))
        		$model->searchmenu = implode(',', $model->searchmenu);
            if(is_array($model->auditinguser))
                $model->auditinguser = implode(',', $model->auditinguser);
        	$model->save();
            return $this->redirect(['menutouserview', 'id' => $model->id]);
        } else {
            return $this->render('menutouserupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Menutouser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMenutouserdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['menutouserindex']);
    }

    /**
     * Finds the MenuToUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MenuToUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MenuToUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
