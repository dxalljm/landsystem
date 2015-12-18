<?php

namespace frontend\controllers;

use Yii;
use app\models\Machine;
use frontend\models\MachineSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\models\Machinetype;
/**
 * MachineController implements the CRUD actions for Machine model.
 */
class MachineController extends Controller
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
     * Lists all Machine models.
     * @return mixed
     */
    public function actionMachineindex()
    {
        $searchModel = new MachineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('machineindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Machine model.
     * @param integer $id
     * @return mixed
     */
    public function actionMachineview($id)
    {
        return $this->render('machineview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Machine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionMachinecreate()
    {
        $model = new Machine();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['machineview', 'id' => $model->id]);
        } else {
            return $this->render('machinecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Machine model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMachineupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['machineview', 'id' => $model->id]);
        } else {
            return $this->render('machineupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Machine model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMachinedelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['machineindex']);
    }

    public function actionMachinexls()
    {
    	set_time_limit(0);
    	$model = new UploadForm();
    	//echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";
    	 
    	if (Yii::$app->request->isPost) {
    		$model->file = UploadedFile::getInstance($model, 'file');
    		 
    		if ($model->file && $model->validate()) {
    			 
    			$xlsName = time().rand(100,999);
    			$model->file->name = $xlsName;
    			$model->file->saveAs('uploads/' . $model->file->name . '.' . $model->file->extension);
    			$path = 'uploads/' . $model->file->name . '.' . $model->file->extension;
    			$loadxls = \PHPExcel_IOFactory::load($path);
//     			var_dump($loadxls->getActiveSheet()->getHighestRow());exit;
    			for($i=2;$i<=$loadxls->getActiveSheet()->getHighestRow();$i++) {
    				//echo $loadxls->getActiveSheet()->getCell('A'.$i)->getValue()."<br>";
    				$productname = $loadxls->getActiveSheet()->getCell('G'.$i)->getValue();
    				$implementmodel = $loadxls->getActiveSheet()->getCell('H'.$i)->getValue();
    				$filename = $loadxls->getActiveSheet()->getCell('E'.$i)->getValue();
    				$province = $loadxls->getActiveSheet()->getCell('M'.$i)->getValue();
    				$enterprisename = $loadxls->getActiveSheet()->getCell('F'.$i)->getValue();
    				$parameter = $loadxls->getActiveSheet()->getCell('I'.$i)->getValue();
    				$content = $loadxls->getActiveSheet()->getCell('L'.$i)->getValue();
					$machinetypename = $loadxls->getActiveSheet()->getCell('D'.$i)->getValue();
					$machinetypeID = Machinetype::find()->where(['typename'=>$machinetypename])->one()['id'];
// 					if(Machine::find()->where(['implementmodel'=>$implementmodel])->count())
					$machineModel = new Machine();
					$machineModel->id = $loadxls->getActiveSheet()->getCell('A'.$i)->getValue();
					$machineModel->machinetype_id = $machinetypeID;
					$machineModel->productname = $productname;
					$machineModel->implementmodel = $implementmodel;
					$machineModel->filename = $filename;
					$machineModel->province = $province;
					$machineModel->enterprisename = $enterprisename;
					$machineModel->parameter = $parameter;
					$machineModel->content = $content;
					$machineModel->save();
    			}
    			//     				$new = $machinetypeModel->attributes;
    			//     				Logs::writeLog('xls批量导入创建宗地信息',$machinetypeModel->id,'',$new);
    			//print_r($machinetypeModel->getErrors());
    		}
    	}
    	 
    
    	return $this->render('machinexls',[
    			'model' => $model,
    	]);
    }
    
    /**
     * Finds the Machine model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Machine the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Machine::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
