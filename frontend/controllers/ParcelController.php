<?php

namespace frontend\controllers;

use Yii;
use app\models\Parcel;
use frontend\models\parcelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PHPExcel;
use \PHPExcel_IOFactory;
use yii\web\UploadedFile;
use app\models\UploadForm;
use app\models\Farms;
use app\models\Logs;
use app\models\Lease;
/**
 * ParcelController implements the CRUD actions for Parcel model.
 */
class ParcelController extends Controller
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
     * Lists all Parcel models.
     * @return mixed
     */
    public function actionParcelindex()
    {
        $searchModel = new parcelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		Logs::writeLog('宗地管理');
        return $this->render('parcelindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionParcellist($zongdi)
    {
    	$zongdiarr = explode('、', $zongdi);
    	foreach($zongdiarr as $value) {
    		$parcels[] = Parcel::find()->where(['unifiedserialnumber'=>Lease::getZongdi($value)])->one();
    	}
    	
    	return $this->render('parcellist', [
    			'parcels' => $parcels,
    	]);
    }
	
    public function actionParcellistajax()
    {
    	$farms_id = Yii::$app->request->get('farms_id');
    	//if (!empty($farms_id)) {
    		$zongdi = Farms::find()->where(['id'=>$farms_id])->one()['zongdi'];
    		$zongdiarr = explode('、', $zongdi);
    		foreach($zongdiarr as $value) {
    			$zd_area[] = $value.'('.Parcel::find()->where(['unifiedserialnumber'=> $value])->one()['grossarea'].')';
    		}
//     		echo json_encode(['status' => 1]);
//     		Yii::$app->end();
    	//} else {
    		return $this->renderAjax('parcellistajax', [
    				'zdarea' => $zd_area,
    		]);
    	//}
    }
    
    /**
     * Displays a single Parcel model.
     * @param integer $id
     * @return mixed
     */
    public function actionParcelview($id)
    {
    	Logs::writeLog('查看宗地信息',$id);
        return $this->render('parcelview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Parcel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionParcelcreate()
    {
        $model = new Parcel();
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$new = $model->attributes;
        	Logs::writeLog('创建宗地',$model->id,'',$new);
            return $this->redirect(['parcelview', 'id' => $model->id]);
        } else {
            return $this->render('parcelcreate', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionParcelxls()
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
	            for($i=1;$i<=$loadxls->getActiveSheet()->getHighestRow();$i++) {
	            	//echo $loadxls->getActiveSheet()->getCell('A'.$i)->getValue()."<br>";
	            	$parchmodel = new Parcel();
    				$parchmodel->serialnumber = $loadxls->getActiveSheet()->getCell('A'.$i)->getValue();
    				$parchmodel->temporarynumber =  $loadxls->getActiveSheet()->getCell('B'.$i)->getValue();
    				$parchmodel->unifiedserialnumber =  $loadxls->getActiveSheet()->getCell('C'.$i)->getValue();
    				$parchmodel->powei =  $loadxls->getActiveSheet()->getCell('D'.$i)->getValue();
    				$parchmodel->poxiang =  $loadxls->getActiveSheet()->getCell('E'.$i)->getValue();
    				$parchmodel->podu =  $loadxls->getActiveSheet()->getCell('F'.$i)->getValue();
    				$parchmodel->agrotype =  $loadxls->getActiveSheet()->getCell('G'.$i)->getValue();
    				$parchmodel->stonecontent =  $loadxls->getActiveSheet()->getCell('H'.$i)->getValue();
    				$parchmodel->grossarea =  $loadxls->getActiveSheet()->getCell('I'.$i)->getValue();
    				$parchmodel->piecemealarea =  $loadxls->getActiveSheet()->getCell('J'.$i)->getValue();
    				$parchmodel->netarea =  $loadxls->getActiveSheet()->getCell('K'.$i)->getValue();
    				$parchmodel->figurenumber =  $loadxls->getActiveSheet()->getCell('L'.$i)->getValue();
    				$parchmodel->save();
    				$new = $parchmodel->attributes;
    				Logs::writeLog('xls批量导入创建宗地信息',$parchmodel->id,'',$new);
    				//print_r($parchmodel->getErrors());
	            }
	        }
	    }
	  
    	return $this->render('parcelxls',[
    			'model' => $model,
    	]);
    }

    /**
     * Updates an existing Parcel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionParcelupdate($id)
    {
        $model = $this->findModel($id);
		$old = $model->attributes;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$new = $model->attributes;
        	Logs::writeLog('更新宗地信息',$id,$old,$new);
            return $this->redirect(['parcelview', 'id' => $model->id]);
        } else {
            return $this->render('parcelupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Parcel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionParceldelete($id)
    {
        $model = $this->findModel($id);
    	$old = $model->attributes;
    	Logs::writeLog('删除宗地',$id,$old);
        $model->delete();

        return $this->redirect(['parcelindex']);
    }

    /**
     * Finds the Parcel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Parcel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Parcel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    //得到宗的面积（累加值）$zongdi='1-100、2-100'
    public  function  actionParcelarea($zongdi)
    {
    	$grossarea = 0;
	    $zongdiarr = explode('、',$zongdi);
	    $count = count($zongdiarr);
	    
	    $zd = $zongdiarr[$count-1];
	    //foreach ($zongdiarr as $zd) {
	    	$area = Parcel::find()->where(['unifiedserialnumber' => $zd])->one()['grossarea'];
	    	if($area) {	
	    		$zongdistate = $this->scanzongdi($zd);
	    		//var_dump($zongdistate[0]);
	    		if($zongdistate[0]) {
	    			$status = 0;
	    			$grossarea = 0;
	    			$message = '对不起，您输入的地块已经被“'.$zongdistate[1].'”占用';
	    		} else {
		    		$status = 1;
		    		$grossarea += $area;
		    		$message = true;
	    		}
	    	}
	    	else {
	    		$status = 0;
	    		$message = '对不起，您输入的地块不存在！';
	    	}
	    	
    	echo json_encode(['status' => $status, 'area' => $grossarea,'message' => $message]);

    }
    
	//面积总和（farmssplit方法用）
    public function actionAreasum($zongdi)
    {
    	$sum = 0;
    	$array = explode('、', $zongdi);
    	foreach ($array as $value) {
    		$sum += Lease::getArea($value);
    	}
    	echo json_encode(['status'=>1,'sum'=>$sum]);
    }
    //格式化宗地$zongdi='1-100、2-100'转换为'1-100(123)、2-100(200)
    public function actionGetformatzongdi($zongdi)
    {
    	$str = '';
    	$zongdiarr = explode('、',$zongdi);
    	foreach ($zongdiarr as $key => $value) {
    		if($value == '')
    			unset($key);
    		else {
    			if(Lease::getArea($value)) {
		    		$area = Parcel::find()->where(['unifiedserialnumber' => $value])->one()['grossarea'];
		    		$format[] = $value.'('.$area.')';
    			}	
	    		else 
	    			$format[] = $value;
    		}
    	}
    	$str = implode('、', $format);
    	echo json_encode(['status' => 1, 'formatzongdi'=>$str]);
    }
    //检索是否为已经添加过的地块$zongdi='1-100、2-100'
    public function scanzongdi($zongdi)
    {
    	
    	$Allfarms = Farms::find()->all();
    	foreach ($Allfarms as $farm) {
    		if($farm['zongdi'] !== '') {
	    		$farmZongdiArray = explode('、', $farm['zongdi']);
	    		foreach ($farmZongdiArray as $farmzongdi) {
	    			if($zongdi == Lease::getZongdi($farmzongdi)) {
	    				$result = [true,$farm['farmname']];
	    				return $result;
	    			}
	    		}
    		}
    	}
    	return [false,false];
    }
}
