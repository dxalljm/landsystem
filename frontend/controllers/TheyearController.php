<?php

namespace frontend\controllers;

use Yii;
use app\models\Theyear;
use frontend\models\theyearSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Logs;

/**
 * TheyearController implements the CRUD actions for Theyear model.
 */
class TheyearController extends Controller
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
     * Lists all Theyear models.
     * @return mixed
     */
//     public function actionTheyearindex()
//     {
//         $searchModel = new theyearSearch();
//         $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
// 		Logs::writeLog('年度管理');
//         return $this->render('theyearindex', [
//             'searchModel' => $searchModel,
//             'dataProvider' => $dataProvider,
//         ]);
//     }

    /**
     * Displays a single Theyear model.
     * @param integer $id
     * @return mixed
     */
//     public function actionTheyearview($id)
//     {
//     	Logs::writeLog('查看年度',$id);
//         return $this->render('theyearview', [
//             'model' => $this->findModel($id),
//         ]);
//     }

    /**
     * Creates a new Theyear model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//     public function actionTheyearcreate()
//     {
//         $model = new Theyear();

//         if ($model->load(Yii::$app->request->post()) && $model->save()) {
//         	$new = $model->attributes;
//         	Logs::writeLog('添加年度',$model->id,'',$new);
        	
//             return $this->redirect(['theyearview', 'id' => $model->id]);
//         } else {
//             return $this->render('theyearcreate', [
//                 'model' => $model,
//             ]);
//         }
//     }

    /**
     * Updates an existing Theyear model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTheyearupdate()
    {
        $model = $this->findModel(1);
		$old = $model->attributes;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$new = $model->attributes;
        	Logs::writeLog('更新年度',1,$old,$new);
            return $this->redirect(['theyearview', 'id' => $model->id]);
        } else {
            return $this->render('theyearupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Theyear model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
//     public function actionTheyeardelete()
//     {
//         $model = $this->findModel(1);
//     	$old = $model->attributes;
//     	Logs::writeLog('删除年度',1,$old);
//         $model->delete();

//         return $this->redirect(['theyearindex']);
//     }

    /**
     * Finds the Theyear model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Theyear the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Theyear::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
