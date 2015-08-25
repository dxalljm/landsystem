<?php

namespace frontend\controllers;

use Yii;
use app\models\Farms;
use frontend\models\farmsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Theyear;
use app\models\UploadForm;
use app\models\Farmer;
use app\models\ManagementArea;
use yii\web\UploadedFile;
use frontend\models\parcelSearch;
use app\models\Parcel;
/**
 * FarmsController implements the CRUD actions for farms model.
 */
class FarmsController extends Controller
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
     * Lists all farms models.
     * @return mixed
     */
    public function actionFarmsindex()
    {
        $searchModel = new farmsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('farmsindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionFarmsxls()
    {
    	set_time_limit(0);
    	$model = new UploadForm();
    	$rows = 0;
    	if (Yii::$app->request->isPost) {
    		
    		$model->file = UploadedFile::getInstance($model, 'file');
    		if($model->file == null)
    			throw new \yii\web\UnauthorizedHttpException('对不起，请先选择xls文件');
    		if ($model->file && $model->validate()) {
    	
    			$xlsName = time().rand(100,999);
    			$model->file->name = $xlsName;
    			$model->file->saveAs('uploads/' . $model->file->name . '.' . $model->file->extension);
    			$path = 'uploads/' . $model->file->name . '.' . $model->file->extension;
    			$loadxls = \PHPExcel_IOFactory::load($path);
    			$rows = $loadxls->getActiveSheet()->getHighestRow();
    			for($i=1;$i<=$rows;$i++) {
    				echo $loadxls->getActiveSheet()->getCell('F'.$i)->getValue()."<br>";
    				//echo  ManagementArea::find()->where(['areaname'=>$loadxls->getActiveSheet()->getCell('B'.$i)->getValue()])->one()['id'];"<br>";
    				$farmsmodel = new Farms();
					$farmsmodel = $this->findModel($loadxls->getActiveSheet()->getCell('A'.$i)->getValue());
    				$farmsmodel->id = $loadxls->getActiveSheet()->getCell('A'.$i)->getValue();
    				$farmsmodel->management_area = ManagementArea::find()->where(['areaname'=>$loadxls->getActiveSheet()->getCell('B'.$i)->getValue()])->one()['id'];
    				$farmsmodel->farmname =  $loadxls->getActiveSheet()->getCell('C'.$i)->getValue();
    				$farmsmodel->address =  $loadxls->getActiveSheet()->getCell('D'.$i)->getValue();
    				$farmsmodel->spyear =  $loadxls->getActiveSheet()->getCell('E'.$i)->getValue();
					$time = ($loadxls->getActiveSheet()->getCell('F'.$i)->getValue() - 25569)*24*60*60;
    				$farmsmodel->surveydate =  date('Y-m-d',$time);
    				//echo $farmsmodel->surveydate;
    				$farmsmodel->groundsign =  $loadxls->getActiveSheet()->getCell('G'.$i)->getValue();
    				$farmsmodel->investigator =  $loadxls->getActiveSheet()->getCell('H'.$i)->getValue();
    				$farmsmodel->farmersign =  $loadxls->getActiveSheet()->getCell('I'.$i)->getValue();
     				$farmsmodel->save();
//     				print_r($farmsmodel->getErrors());
    			}
    		}
    	}
    	return $this->render('farmsxls',[
    			'model' => $model,
    			'rows' => $rows,
    	]);
    }
    
    public function actionFarmsbusiness()
    {
    	$searchModel = new farmsSearch();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
    	return $this->render('farmsbusiness', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    }

    public function actionFarmsmenu($id,$areaid)
    {
    	$farm = $this->findModel($id);
    	return $this->render('farmsmenu',[
    		'farm' => $farm,
    		'year' => Theyear::findOne(1),
    		'areaid' => $areaid,
    	]);
    }
    
    /**
     * Displays a single Farms model.
     * @param integer $id
     * @return mixed
     */
    public function actionFarmsview($id)
    {
    	$model = $this->findModel($id);
    	
    	$zongdiarr = explode(' ', $model->zongdi);
    	foreach($zongdiarr as $zongdi) {
    		$dataProvider[] = Parcel::find()->where(['unifiedserialnumber' => $zongdi])->one();
    	}
        return $this->render('farmsview', [
            'model' => $model,
        	'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new farms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionFarmscreate()
    {
        $model = new farms();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['farmsview', 'id' => $model->id]);
        } else {
            return $this->render('farmscreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Farms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFarmsupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['farmsview', 'id' => $model->id]);
        } else {
            return $this->render('farmsupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Farms model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionFarmsdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['farmsindex']);
    }

    /**
     * Finds the farms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return farms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = farms::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
