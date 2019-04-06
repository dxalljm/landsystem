<?php

namespace frontend\controllers;

use app\models\Logs;
use Yii;
use app\models\Projecttype;
use frontend\models\projecttypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProjecttypeController implements the CRUD actions for Projecttype model.
 */
class ProjecttypeController extends Controller
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
     * Lists all Projecttype models.
     * @return mixed
     */
    public function actionProjecttypeindex()
    {
        $searchModel = new projecttypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Logs::writeLogs('项目类型列表');
        return $this->render('projecttypeindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Projecttype model.
     * @param integer $id
     * @return mixed
     */
    public function actionProjecttypeview($id)
    {
        $model = $this->findModel($id);
        Logs::writeLogs('查看项目类型',$model);
        return $this->render('projecttypeview', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Projecttype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionProjecttypecreate()
    {
        $model = new Projecttype();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Logs::writeLogs('创建项目类型',$model);
            return $this->redirect(['projecttypeview', 'id' => $model->id]);
        } else {
            return $this->render('projecttypecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Projecttype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionProjecttypeupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Logs::writeLogs('更新项目类型',$model);
            return $this->redirect(['projecttypeview', 'id' => $model->id]);
        } else {
            return $this->render('projecttypeupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Projecttype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionProjecttypedelete($id)
    {
        $model = $this->findModel($id)->delete();
        Logs::writeLogs('删除项目类型',$model);
        return $this->redirect(['projecttypeindex']);
    }

    /**
     * Finds the Projecttype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Projecttype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Projecttype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
