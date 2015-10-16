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
use app\models\Logs;
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
		$farmname = Farms::findOne($farms_id)['farmname'];
		Logs::writeLog($farmname.'的种植结构');
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
    	Logs::writeLog('查看种植结构',$id);
        return $this->render('plantingstructureview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Plantingstructure model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    

    //获取承租人的宗地信息，如果已经添加过，则过滤掉
    public function getListZongdi($lease_id)
    {
    	//$zongdi = array();
    	$lease = Lease::find()->where(['id'=>$lease_id])->one();
    	$zongdiarr = explode('、', $lease['lease_area']);
    	$plantings = Plantingstructure::find()->where(['lease_id'=>$lease_id])->all();
    	
    	$zongdi = [];
    	if($plantings) {
	    	foreach($zongdiarr as $value) {
	    		foreach ($plantings as $plants) {
	    			$plantArray = explode('、', $plants['zongdi']);
	    			foreach($plantArray as $plant) {
	    				//echo Lease::getArea($value) .'-'. Lease::getArea($plant).'<br>';
		    			if(Lease::getZongdi($value) == Lease::getZongdi($plant)){		
		    				if(Lease::getArea($value) !== Lease::getArea($plant)){
		    					//echo Lease::getArea($value) .'-'. Lease::getArea($plant).'<br>';
		    					$areac = Lease::getArea($value) - Lease::getArea($plant);
		    					$v = Lease::getZongdi($value).'('.$areac.')';
		    					//var_dump($v);
		    					$zongdi[$v] = $v;
		    					//echo 'zongdi_l=';var_dump($zongdi);
		    				}
		    			} else {
		    				//var_dump($zongdiarr);
		    				$zongdi[$value] = $value;
		    				//var_dump($zongdi);
		    				//$zongdi = array_diff($zongdi,$zongdiarr);
		    			}
		    		}
	    		}
	    	}	
	    	//var_dump($zongdi);
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
    	$area = Lease::getListArea($zongdi);
    	echo json_encode(['status'=>1,'area'=>$area]);
    }
    
    
    public function actionPlantingstructurecreate($lease_id,$farms_id)
    {
    	$model = new Plantingstructure();
    
    	$farm = Farms::find()->where(['id'=>$farms_id])->one();
    	$zongdi = Plantingstructure::getNoZongdi($lease_id, $farms_id);

    	//exit;
    	if ($model->load(Yii::$app->request->post())) {
    		
    		//$model->zongdi = Lease::getZongdi($model->zongdi);
    		$model->create_at = time();
    		$model->update_at = time();
    		$model->save();
    		
    		$new = $model->attributes;
    		Logs::writeLog('为'.Lease::find()->where(['id'=>$lease_id])->one()['lessee'].'创建种植结构信息',$model->id,'',$new);
    		return $this->redirect(['plantingstructureindex', 'farms_id' => $farms_id]);
    	} else {
    		return $this->render('plantingstructurecreate', [
    				'model' => $model,
    				'farm' => $farm,
    				'zongdi' => $zongdi,
    		]);
    	}
    }
    /**
     * Updates an existing Plantingstructure model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantingstructureupdate($id,$lease_id,$farms_id)
    {
        $model = $this->findModel($id);
        $old = $model->attributes;
        $farm = Farms::find()->where(['id'=>$model->farms_id])->one();
        if($lease_id == 0) {
        	$zongdi = Lease::getNOZongdi($farms_id);
        }
        else {
        	$zongdi = $this->getListZongdi($lease_id,$farms_id);
        }
        //var_dump($zongdi);
        $plantings = Plantingstructure::find()->where(['lease_id'=>$lease_id,'farms_id'=>$farms_id])->all();
    	if($plantings) {
    		$plantingzongdi = [];
    		foreach ($plantings as $value) {
    			$plantingzongdi = array_merge($plantingzongdi,explode('、',$value['zongdi']));
    		}
    		 			var_dump($plantingzongdi);
    		 			var_dump($zongdi);
    		$zongdi = array_diff($zongdi,$plantingzongdi);
    		//var_dump($zongdi);
    		exit;
    		sort($zongdi);
    		$zongdi = Lease::getLastArea($zongdi, $_GET['lease_id'], $_GET['farms_id']);
    	}
        
        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->save();
        	$new = $model->attributes;
        	Logs::writeLog('更新租赁信息',$id,$old,$new);
            return $this->redirect(['plantingstructureview', 'id' => $model->id]);
        } else {
            return $this->render('plantingstructureupdate', [
                'model' => $model,
            	'farm' => $farm,
            	'zongdi' => $zongdi,
            	//'leases' => $lease,
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
        $model = $this->findModel($id);
    	$old = $model->attributes;
    	Logs::writeLog('删除租赁信息',$id,$old);
        $model->delete();

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
