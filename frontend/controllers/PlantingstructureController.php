<?php

namespace frontend\controllers;

use Yii;
use app\models\Plantingstructure;
use frontend\models\plantingstructureSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use app\models\Parcel;
use app\models\Lease;
use frontend\models\leaseSearch;
use app\models\Plantinputproduct;
use app\models\Plantpesticides;
/**
 * PlantingstructureController implements the CRUD actions for Plantingstructure model.
 */
class PlantingstructureController extends Controller
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
     * Lists all Plantingstructure models.
     * @return mixed
     */
    public function actionPlantingstructureindex($farms_id)
    {
        $lease = Lease::find()->where(['farms_id'=>$farms_id])->all();
		//$this->getView()->registerJsFile($url)
        return $this->render('Plantingstructureindex', [
             'leases' => $lease,
        ]);
    }

    /**
     * Displays a single Plantingstructure model.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantingstructureview($id)
    {
        return $this->render('plantingstructureview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Plantingstructure model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPlantingstructurecreate($lease_id,$farms_id)
    {
        $model = new Plantingstructure();
        $plantinputproductModel = new Plantinputproduct();
        $plantpesticidesModel = new Plantpesticides();
        $plantinputproductData = Plantinputproduct::find()->where(['farms_id'=>$farms_id,'lessee_id'=>$lease_id])->all();
        $plantpesticidesData = Plantpesticides::find()->where(['farms_id'=>$farms_id,'lessee_id'=>$lease_id])->all();
		$farm = Farms::find()->where(['id'=>$farms_id])->one();
		$zongdi = $this->getListZongdi($lease_id);
		
        if ($model->load(Yii::$app->request->post())) {
            $model->zongdi = Lease::getZongdi($model->zongdi);
        	$model->save();
            return $this->redirect(['plantingstructureindex', 'farms_id' => $farms_id]);
        } else {
            return $this->render('plantingstructurecreate', [
                'model' => $model,
            	'plantpesticidesModel' => $plantpesticidesModel,
            	'plantinputproductModel' => $plantinputproductModel,
            	'plantinputproductData' => $plantinputproductData,
            	'plantpesticidesData' => $plantpesticidesData,
            	'farm' => $farm,
            	'zongdi' => $zongdi,
            ]);
        }
    }

    //获取承租人的宗地信息，如果已经添加过，则过滤掉
    public function getListZongdi($lease_id)
    {
    	echo '<br><br><br><br><br><br><br>';
    	//$zongdi = array();
    	$lease = Lease::find()->where(['id'=>$lease_id])->one();
    	$zongdiarr = explode('、', $lease['lease_area']);
    	$plantings = Plantingstructure::find()->where(['lease_id'=>$lease_id])->all();
    	
    	if(count($plantings)>=1) {
    		$plantings = $this->zongdiAreaSum($plantings);
	    	foreach ($plantings as $value) {
	    		$ps[$value['zongdi'].'('.$value['area'].')'] = $value['zongdi'].'('.$value['area'].')';
	    	}
	    	$zongdiarr = array_diff($zongdiarr,$ps);
    	}
    	
    	//echo 'ps='.var_dump($ps);
    	
    	//var_dump($zongdi);
    	if($plantings) {
	    	foreach($zongdiarr as $value) {
	    		foreach ($plantings as $plants) {
	    			if(Lease::getZongdi($value) == $plants['zongdi']){
	    				echo Lease::getArea($value) .'-'. $plants['area'].'<br>';
	    				if(Lease::getArea($value) !== $plants['area']){
	    					echo Lease::getArea($value) .'-'. $plants['area'].'<br>';
	    					$areac = Lease::getArea($value) - $plants['area'];
	    					$zongdi[Lease::getZongdi($value).'('.$areac.')'] = Lease::getZongdi($value).'('.$areac.')';
	    					//echo 'zongdi_l=';var_dump($zongdi);
	    				}
	    			} else {
	    				$zongdi[$value] = $value;
	    				$zongdi = array_diff($zongdi,$zongdiarr);
	    			}
	    		}
	    	}	
	    	return $zongdi;
    	}
    	else {
    		foreach($zongdiarr as $key => $value) {
    			$zongdi[$value] = $value;
    		}
    		//var_dump($zongdi);
    		return $zongdi;
    	}
    }
    //对plantingstructure中获取的面积进程累加处理
    public function zongdiAreaSum($arrayArea) 
    {
    	//var_dump($arrayArea[0]['zongdi']);
    	
    	for($i=0;$i<count($arrayArea);$i++) {
    		for($j=$i+1;$j<count($arrayArea);$j++) {
    			if(isset($arrayArea[$j]['zongdi'])) {
	    			if(Lease::getZongdi($arrayArea[$i]['zongdi']) == Lease::getZongdi($arrayArea[$j]['zongdi'])) {
	    				$areaSum = $arrayArea[$i]['area']+$arrayArea[$j]['area'];
	    				//$arrayArea[$i]['zongdi'] = Lease::getZongdi($arrayArea[$i]['zongdi']).'('.$areaSum.')';
	    				$arrayArea[$i]['area'] = $areaSum;
	    				unset($arrayArea[$j]);
	    				sort($arrayArea);
	    				//var_dump($arrayArea);
	    				$arrayArea = self::zongdiAreaSum($arrayArea);
	    			}
    			}
    		}
    	}
    	return $arrayArea;
    }
    public function actionPlantingstructuregetarea($zongdi) 
    {
    	$area = Lease::getArea($zongdi);
    	echo json_encode(['status'=>1,'area'=>$area]);
    }
    
    /**
     * Updates an existing Plantingstructure model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantingstructureupdate($id)
    {
        $model = $this->findModel($id);
        $farm = Farms::find()->where(['id'=>$model->farms_id])->one();
        $lease = Lease::find()->where(['id'=>$model->lease_id])->one();
        $zongdiarr = explode('、', $lease['lease_area']);
        foreach($zongdiarr as $value) {
        	$zongdi[]['unifiedserialnumber'] = $value;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['plantingstructureview', 'id' => $model->id]);
        } else {
            return $this->render('plantingstructureupdate', [
                'model' => $model,
            	'farm' => $farm,
            	'zongdi' => $zongdi,
            ]);
        }
    }

    /**
     * Deletes an existing Plantingstructure model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantingstructuredelete($id,$farms_id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['plantingstructureindex','farms_id'=>$farms_id]);
    }

    /**
     * Finds the Plantingstructure model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plantingstructure the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plantingstructure::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
