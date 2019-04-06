<?php

namespace frontend\controllers;

use Yii;
use app\models\Machinesubsidy;
use frontend\models\MachinesubsidySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\models\Machinetype;
/**
 * MachinesubsidyController implements the CRUD actions for Machinesubsidy model.
 */
class MachinesubsidyController extends Controller
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
     * Lists all Machinesubsidy models.
     * @return mixed
     */
    public function actionMachinesubsidyindex()
    {
        $searchModel = new MachinesubsidySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('machinesubsidyindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMachinesubsidyxls()
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
                for($i=4;$i<=$loadxls->getActiveSheet()->getHighestRow();$i++) {
                    //echo $loadxls->getActiveSheet()->getCell('A'.$i)->getValue()."<br>";
//                    $productname = $loadxls->getActiveSheet()->getCell('G'.$i)->getValue();
//                    $implementmodel = $loadxls->getActiveSheet()->getCell('H'.$i)->getValue();
                    $filename = $loadxls->getActiveSheet()->getCell('F'.$i)->getValue();
//    				$province = $loadxls->getActiveSheet()->getCell('M'.$i)->getValue();
                    $enterprisename = $loadxls->getActiveSheet()->getCell('G'.$i)->getValue();
//                    $parameter = $loadxls->getActiveSheet()->getCell('F'.$i)->getValue();
                    $subsidymoney = (string)$loadxls->getActiveSheet()->getCell('H'.$i)->getValue();
                    $machinetype = $loadxls->getActiveSheet()->getCell('I'.$i)->getValue();
                    $machinetypename = $loadxls->getActiveSheet()->getCell('E'.$i)->getValue();
                    $machinetypeID = Machinetype::find()->where(['typename'=>$machinetypename])->one()['id'];
// 					if(Machine::find()->where(['implementmodel'=>$implementmodel])->count())
                    $machineModel = new Machinesubsidy();
//					$machineModel->id = $loadxls->getActiveSheet()->getCell('A'.$i)->getValue();
                    $machineModel->machinetype_id = $machinetypeID;
//                    $machineModel->productname = $productname;
//                    $machineModel->implementmodel = $implementmodel;
                    $machineModel->filename = $filename;
//					$machineModel->province = $province;
                    $machineModel->parameter = $enterprisename;
                    $machineModel->machinetype = $machinetype;
//                    $machineModel->parameter = $parameter;
//                    $machineModel->content = $content;
//                    $machineModel->year = User::getYear();
                    $machineModel->subsidymoney = $subsidymoney;
                    $machineModel->save();
                }
                //     				$new = $machinetypeModel->attributes;
                //     				Logs::writeLog('xls批量导入创建宗地信息',$machinetypeModel->id,'',$new);
                //print_r($machinetypeModel->getErrors());
            }
        }


        return $this->render('machinesubsidyxls',[
            'model' => $model,
        ]);
    }
    /**
     * Displays a single Machinesubsidy model.
     * @param integer $id
     * @return mixed
     */
    public function actionMachinesubsidyview($id)
    {
        return $this->render('machinesubsidyview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Machinesubsidy model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionMachinesubsidycreate()
    {
        $model = new Machinesubsidy();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['machinesubsidyview', 'id' => $model->id]);
        } else {
            return $this->render('machinesubsidycreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Machinesubsidy model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMachinesubsidyupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['machinesubsidyview', 'id' => $model->id]);
        } else {
            return $this->render('machinesubsidyupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Machinesubsidy model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMachinesubsidydelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['machinesubsidyindex']);
    }

    /**
     * Finds the Machinesubsidy model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Machinesubsidy the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Machinesubsidy::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
