<?php

namespace frontend\controllers;

use Yii;
use app\models\Breed;
use frontend\models\breedSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Breedtype;

/**
 * BreedController implements the CRUD actions for Breed model.
 */
class BreedController extends Controller
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
     * Lists all Breed models.
     * @return mixed
     */
    public function actionBreedindex()
    {
    	$model = new Breed();
    	$breedtypeFather = Breedtype::find()->where(['father_id'=>1])->all();
    	
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['breedview', 'id' => $model->id]);
        } else {
            return $this->render('breedcreate', [
                'model' => $model,
            	'breedtypeFather' => $breedtypeFather,
            ]);
        }
    }

    /**
     * Displays a single Breed model.
     * @param integer $id
     * @return mixed
     */
    public function actionBreedview($id)
    {
        return $this->render('breedview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Breed model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBreedcreate()
    {
        $model = new Breed();
        $breedtypeFather = Breedtype::find()->where(['father_id'=>1])->all();
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['breedview', 'id' => $model->id]);
        } else {
            return $this->render('breedcreate', [
                'model' => $model,
            	'breedtypeFather' => $breedtypeFather,
            ]);
        }
    }

    public function actionGetbreedtypeson($father_id)
    {
    	$result = Breedtype::find()->where(['father_id'=>$father_id])->all();
    	foreach ($result as $value) {
    		$arraySon[] = [
    			'id'=>$value['id'],
    			'father_id'=>$value['father_id'],
    			'typename'=>$value['typename'],
    		];
    	}
    	echo json_encode(['status'=>1,'breedtypeSon'=>$arraySon]);
    }
    /**
     * Updates an existing Breed model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBreedupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['breedview', 'id' => $model->id]);
        } else {
            return $this->render('breedupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Breed model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBreeddelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['breedindex']);
    }

    /**
     * Finds the Breed model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Breed the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Breed::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
