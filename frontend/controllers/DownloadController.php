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

    /**
     * Lists all Afterchenqian models.
     * @return mixed
     */
    
    public function actionDownloadindex()
    {
    	return $this->render('downloadindex', [
    			 
    	]);
    }
}
