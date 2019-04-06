<?php

namespace frontend\controllers;

use app\models\Logs;
use app\models\User;
use Yii;
use app\models\Machine;
use frontend\models\MachineSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\models\Machinetype;
use yii\helpers\ArrayHelper;
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
    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        } else {
            return true;
        }
    }
    /**
     * Lists all Machine models.
     * @return mixed
     */
    public function actionMachineindex()
    {
        $searchModel = new MachineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Logs::writeLogs('农机器具列表');
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

    public function actionGetmachineinfo($id)
    {
    	$result = [];
    	$machine = Machine::find()->where(['id'=>$id])->one();

    	$result['machinename'] = $machine['productname'];
    	$result['machinemodel'] = $machine['implementmodel'];
        $result['filename'] = $machine['filename'];
        $result['enterprisename'] = $machine['enterprisename'];
        $result['parameter'] = $machine['parameter'];
    	$father_id = Machinetype::find()->where(['id'=>$machine['machinetype_id']])->one()['father_id'];
    	$last = Machinetype::find()->where(['id'=>$father_id])->one();
    	
    	$result['last']['id'] = $machine['machinetype_id'];
    	$lastdata = ArrayHelper::map(Machinetype::find()->where(['father_id'=>$last['id']])->all(), 'id', 'typename');
    	foreach ($lastdata as $key=>$value) {
    		$result['last']['data'][] = ['id'=>$key,'typename'=>$value];
    	}

//     	var_dump(Machinetype::find()->where(['father_id'=>$last['father_id']])->all());
    	$result['small']['id'][] = $last['id'];
    	$smalldata = ArrayHelper::map(Machinetype::find()->where(['father_id'=>$last['father_id']])->all(), 'id', 'typename');
    	foreach ($smalldata as $key=>$value) {
    		$result['small']['data'][] = ['id'=>$key,'typename'=>$value];
    	}
    	
//     	$big = Machinetype::find()->where(['id'=>$small['father_id']])->one();
    	$result['big']['id'] = $last['father_id'];
//     	$result['big']['text'] = $big['typename'];
//     	$result['big'] = ['data'=>ArrayHelper::map(Machinetype::find()->where(['father_id'=>$big])->all(), 'id', 'typename')];
// 		var_dump($result);
    	echo json_encode(['status'=>1,'data'=>$result]);
    }

    public function actionMachineyear()
    {
        $machines = Machine::find()->all();
        foreach ($machines as $machine) {
            $model = Machine::findOne($machine['id']);
            $model->year = 2015;
            $model->save();
        }
        echo 'finished';
    }

    public function actionMachinexls()
    {
    	set_time_limit(0);
        require_once '../../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
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
//    				$province = $loadxls->getActiveSheet()->getCell('F'.$i)->getValue();
                    $machinetype = $loadxls->getActiveSheet()->getCell('J'.$i)->getValue();
                    $state = $loadxls->getActiveSheet()->getCell('K'.$i)->getValue();
    				$enterprisename = $loadxls->getActiveSheet()->getCell('F'.$i)->getValue();
    				$parameter = $loadxls->getActiveSheet()->getCell('I'.$i)->getValue();
    				$content = $loadxls->getActiveSheet()->getCell('L'.$i)->getValue();
					$machinetypename = $loadxls->getActiveSheet()->getCell('D'.$i)->getValue();
					$machinetypeID = Machinetype::find()->where(['typename'=>$machinetypename])->one()['id'];
// 					if(Machine::find()->where(['implementmodel'=>$implementmodel])->count())
                    $machineModel = Machine::find()->where(['implementmodel'=>$implementmodel,'enterprisename'=>$enterprisename])->one();
                    if(empty($machineModel)) {
                        $machineModel = new Machine();
//					$machineModel->id = $loadxls->getActiveSheet()->getCell('A'.$i)->getValue();
                        //农机类型
                        $machineModel->machinetype_id = $machinetypeID;
                        //产品名称
                        $machineModel->productname = $productname;
                        //产品类型
                        $machineModel->implementmodel = $implementmodel;
                        //分档名称
                        $machineModel->filename = $filename;
                        //企业所属省份
//					$machineModel->province = $province;
                        //企业名称
                        $machineModel->enterprisename = $enterprisename;
                        //基本配置及参数
                        $machineModel->parameter = $parameter;
                        $machineModel->content = $content;
                        $machineModel->machinetype = $machinetype;
                        if ($state == '通过') {
                            $machineModel->state = 1;
                        } else {
                            $machineModel->state = 0;
                        }
                        $machineModel->year = User::getYear();
                        $machineModel->save();
                    } else {
                        if(empty($machineModel->machinetype_id)) {
                            $machineModel->machinetype_id = $machinetypeID;
                            $machineModel->save();
                        }
//                        $machineModel->machinetype = $machinetype;
//                        if ($state == '通过') {
//                            $machineModel->state = 1;
//                        } else {
//                            $machineModel->state = 0;
//                        }
//                        $machineModel->year = User::getYear();

                    }
    			}
    			//     				$new = $machinetypeModel->attributes;
    			//     				Logs::writeLog('xls批量导入创建宗地信息',$machinetypeModel->id,'',$new);
    			//print_r($machinetypeModel->getErrors());
    		}
    	}
    	 
        Logs::writeLogs('导入农机器具XLS');
    	return $this->render('machinexls',[
    			'model' => $model,
    	]);
    }

    public function actionMachinexls2()
    {
        set_time_limit(0);
        require_once '../../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
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
                for($i=1;$i<=$loadxls->getActiveSheet()->getHighestRow();$i++) {
                    //echo $loadxls->getActiveSheet()->getCell('A'.$i)->getValue()."<br>";
//                    $productname = $loadxls->getActiveSheet()->getCell('G'.$i)->getValue();
//                    $implementmodel = $loadxls->getActiveSheet()->getCell('H'.$i)->getValue();
//                    $filename = $loadxls->getActiveSheet()->getCell('E'.$i)->getValue();
//    				$province = $loadxls->getActiveSheet()->getCell('F'.$i)->getValue();
//                    $machinetype = $loadxls->getActiveSheet()->getCell('J'.$i)->getValue();
//                    $state = $loadxls->getActiveSheet()->getCell('K'.$i)->getValue();
                    $enterprisename = $loadxls->getActiveSheet()->getCell('F'.$i)->getValue();
//                    $parameter = $loadxls->getActiveSheet()->getCell('I'.$i)->getValue();
                    $content = $loadxls->getActiveSheet()->getCell('K'.$i)->getValue();
//                    $machinetypename = $loadxls->getActiveSheet()->getCell('D'.$i)->getValue();
//                    $machinetypeID = Machinetype::find()->where(['typename'=>$machinetypename])->one()['id'];
// 					if(Machine::find()->where(['implementmodel'=>$implementmodel])->count())
                    $machines = Machine::find()->where(['enterprisename'=>$enterprisename,'year'=>2017])->all();
                    foreach($machines as $machine) {
                        $machineModel = Machine::findOne($machine['id']);
                        $machineModel->content = $content;
                        $machineModel->state = 0;
//                        $machineModel->year = User::getYear();
                        $machineModel->save();
                    }
                }
                //     				$new = $machinetypeModel->attributes;
                //     				Logs::writeLog('xls批量导入创建宗地信息',$machinetypeModel->id,'',$new);
                //print_r($machinetypeModel->getErrors());
            }
        }

        Logs::writeLogs('导入农机器具XLS');
        return $this->render('machinexls2',[
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
