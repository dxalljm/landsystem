<?php

namespace frontend\controllers;

use Yii;
use app\models\Afterchenqian;
use frontend\models\AfterchenqianSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use app\models\Farmer;
use app\models\Logs;

/**
 * AfterchenqianController implements the CRUD actions for Afterchenqian model.
 */
class DownloadController extends Controller
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
    /**
     * Lists all Afterchenqian models.
     * @return mixed
     */
    
    public function actionDownloadindex()
    {
        Logs::writeLogs('控件下载');
    	return $this->render('downloadindex', [
    			 
    	]);
    }
}
