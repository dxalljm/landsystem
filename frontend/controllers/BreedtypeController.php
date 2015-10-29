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

    /**
     * Lists all Breedtype models.
     * @return mixed
     */
    public function actionBreedtypeindex()
    {
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
        return $this->render('breedtypeview', [
            'model' => $this->findModel($id),
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
            Logs::writeLog('创建养殖种类',$model->id,'',$newAttr);
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['breedtypeview', 'id' => $model->id]);
        } else {
            return $this->render('breedtypeupdate', [
                'model' => $model,
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
        $this->findModel($id)->delete();

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
