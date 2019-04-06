<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use app\models\Cooperativetype;
use frontend\models\cooperativetypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Logs;

/**
 * CooperativetypeController implements the CRUD actions for Cooperativetype model.
 */
class CooperativetypeController extends Controller
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
     * Lists all Cooperativetype models.
     * @return mixed
     */
    public function actionCooperativetypeindex()
    {
        $searchModel = new cooperativetypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		Logs::writeLogs('合作社类型');
        return $this->render('cooperativetypeindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cooperativetype model.
     * @param integer $id
     * @return mixed
     */
    public function actionCooperativetypeview($id)
    {
    	Logs::writeLog('查看合作社类型',$id);
        return $this->render('cooperativetypeview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Cooperativetype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCooperativetypecreate()
    {
        $model = new Cooperativetype();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	Logs::writeLogs('创建合作社类型',$model);
            return $this->redirect(['cooperative/cooperativecreate']);
        } else {
			return $this->render('cooperativetypecreate', [
				'model' => $model,
			]);
        }
    }

    /**
     * Creates a new Cooperativetype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCooperativetypecreateajax()
    {
        $model = new Cooperativetype();
        $typeName = Yii::$app->request->get('typename');
        if (!empty($typeName)) {
            $model->typename = $typeName;
            $model->save();
            Logs::writeLogs('创建合作社类型',$model);
            echo json_encode(['status' => 1, 'data' => [$model->id, $model->typename]]);
            Yii::$app->end();
        } else {
            return $this->renderAjax('cooperativetypecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Cooperativetype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCooperativetypeupdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	Logs::writeLogs('更新合作社类型',$model);
            return $this->redirect(['cooperativetypeview', 'id' => $model->id]);
        } else {
            return $this->render('cooperativetypeupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Cooperativetype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCooperativetypedelete($id)
    {
    	$model = $this->findModel($id);
        $model->delete();
        Logs::writeLogs('删除合作社类型',$model);
        return $this->redirect(['cooperative/cooperativecreate']);
    }

    /**
     * Finds the Cooperativetype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cooperativetype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cooperativetype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
