<?php

namespace frontend\controllers;

use Yii;
use app\models\Machineoffarm;
use frontend\models\MachineoffarmSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\MachineSearch;
use app\models\Machinetype;
use app\models\Machine;

/**
 * MachineoffarmController implements the CRUD actions for Machineoffarm model.
 */
class MachineoffarmController extends Controller
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
     * Lists all Machineoffarm models.
     * @return mixed
     */
    public function actionMachineoffarmindex($farms_id)
    {
        $searchModel = new MachineoffarmSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('machineoffarmindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'farms_id' => $farms_id,
        ]);
    }

    /**
     * Displays a single Machineoffarm model.
     * @param integer $id
     * @return mixed
     */
    public function actionMachineoffarmview($id)
    {
        return $this->render('machineoffarmview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Machineoffarm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionMachineoffarmcreate($farms_id,$machine_id = 0)
    {
    	$post = Yii::$app->request->post();
    	$searchModel = new MachineSearch();
    	$params = Yii::$app->request->queryParams;
    	$lastclass = '';
    	$smallclass = '';
    	$bigclass = '';
    	if($post) {
//     		var_dump($post);exit;
    		$params['MachineSearch']['machinetype_id'] = $post['lastclass'];
    		if(isset($post['MachineSearch']['productname']))
    			$params['MachineSearch']['productname'] = $post['MachineSearch']['productname'];
    		if(isset($post['MachineSearch']['implementmodel']))
    			$params['MachineSearch']['implementmodel'] = $post['MachineSearch']['implementmodel'];
    		if(isset($post['MachineSearch']['filename']))
    			$params['MachineSearch']['filename'] = $post['MachineSearch']['filename'];
    		if(isset($post['MachineSearch']['enterprisename']))
    			$params['MachineSearch']['enterprisename'] = $post['MachineSearch']['enterprisename'];
    		$lastclass = $post['lastclass'];
    		$smallclass = Machinetype::find()->where(['id'=>$lastclass])->one()['father_id'];
    		$bigclass = Machinetype::find()->where(['id'=>$smallclass])->one()['father_id'];
    	
    	$dataProvider = $searchModel->search($params);
    	
//         $model = new Machineoffarm();

//         if ($model->load(Yii::$app->request->post())) {
//         	$model->farms_id = $farms_id;
//         	if($machine_id !== 0) {
//         		$machinename = Machine::find()->where(['id'=>$machine_id])->one()['productname'];
//         		$model->machinename = $machinename;
//         		$model->machine_id = $machine_id;
//         		$model->create_at = time();
//         		$model->update_at = $model->create_at;
//         		$model->save();
//         	}
//         	if(!empty($model->machinename)) {
        		
//         	}
        	
//             return $this->redirect(['machineoffarmindex', 'farms_id' => $model->farms_id]);
        } else {
            return $this->render('machineoffarmcreate', [
                	'model' => $model,
            		'searchModel' => $searchModel,
            		'dataProvider' => $dataProvider,
            		'lastclass' => $lastclass,
            		'smallclass' =>$smallclass,
            		'bigclass' => $bigclass,
            		'farms_id' => $farms_id,
            ]);
        }
    }

    
    /**
     * Updates an existing Machineoffarm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMachineoffarmupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['machineoffarmview', 'id' => $model->id]);
        } else {
            return $this->render('machineoffarmupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Machineoffarm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMachineoffarmdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['machineoffarmindex']);
    }

    /**
     * Finds the Machineoffarm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Machineoffarm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Machineoffarm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
