<?php

namespace frontend\controllers;

use Yii;
use app\models\Breedtype;
use frontend\models\breedtypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Logs;
/**
 * BreedtypeController implements the CRUD actions for Breedtype model.
 */
class BreedtypeController extends Controller
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
     * Lists all Breedtype models.
     * @return mixed
     */
    public function actionBreedtypeindex()
    {
        Logs::writeLogs('畜牧种类列表');
        $searchModel = new breedtypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('breedtypeindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Breedtype model.
     * @param integer $id
     * @return mixed
     */
    public function actionBreedtypeview($id)
    {
        $model = $this->findModel($id);
        Logs::writeLogs('查看畜牧种类',$model);
        return $this->render('breedtypeview', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Breedtype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBreedtypecreate()
    {
        $model = new Breedtype();
		$father = Breedtype::find()->where(['father_id'=>[0,1]])->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Logs::writeLogs('新增畜牧种类',$model);
            return $this->redirect(['breedtypeview', 'id' => $model->id]);
        } else {
            return $this->render('breedtypecreate', [
                'model' => $model,
            	'father' => $father,
            	'father_id' => 0,
            ]);
        }
    }

    public function actionBreedtypecreateajax()
    {
    	$model = new Breedtype();
    	$father = Breedtype::find()->where(['father_id'=>[0,1]])->all();
    	$typeName = Yii::$app->request->get('typename');
    	$father_id = Yii::$app->request->get('father_id');
    	if (!empty($typeName)) {
            $model->typename = $typeName;
            $model->father_id = $father_id;
            $model->save();
            $newAttr = $model->attributes;
            Logs::writeLogs('创建畜牧种类',$model);
            echo json_encode(['status' => 1, 'data' => [$model->id, $model->typename]]);
            Yii::$app->end();
        } else {
            return $this->renderAjax('breedtypecreate', [
                'model' => $model,
            	'father' => $father,
            	'father_id' => $father_id,
            ]);
        }
    }
    
    /**
     * Updates an existing Breedtype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBreedtypeupdate($id)
    {
        $model = $this->findModel($id);
        $father = Breedtype::find()->where(['father_id'=>[0,1]])->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Logs::writeLogs('更新畜牧种类',$model);
            return $this->redirect(['breedtypeindex']);
        } else {
            return $this->render('breedtypeupdate', [
                'model' => $model,
            	'father' => $father,
            	'father_id' => 0,
            ]);
        }
    }

    /**
     * Deletes an existing Breedtype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBreedtypedelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Logs::writeLogs('删除畜牧种类',$model);
        return $this->redirect(['breedtypeindex']);
    }

    /**
     * Finds the Breedtype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Breedtype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Breedtype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
