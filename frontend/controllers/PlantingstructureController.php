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
use app\models\Theyear;
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

    public function actionPlantingstructuresearch($begindate,$enddate,$management_area)
    {
    	$post = Yii::$app->request->post();
    		
    	if($post) {
    		if($post['tab'] == 'parmpt')
    			return $this->redirect(['search/searchindex']);
    		$whereDate = Theyear::formatDate($post['begindate'],$post['enddate']);
    		return $this->redirect ([$post['tab'].'/'.$post['tab'].'search',
    				'begindate' => $whereDate['begindate'],
    				'enddate' => $whereDate['enddate'],
    				'management_area' => $post['managementarea'],
    		]);
    	} else {
    	
	    	$searchModel = new plantingstructureSearch();
	    	$params = Yii::$app->request->queryParams;
	    	if($management_area) {
		    	$arrayID = Farms::getFarmArray($management_area);
		    	$params ['plantingstructureSearch']['farms_id'] = $arrayID;
	    	}
	    	$params ['plantingstructureSearch']['begindate'] = $begindate;
	    	$params ['plantingstructureSearch']['enddate'] = $enddate;
	    	$dataProvider = $searchModel->searchIndex ( $params['plantingstructureSearch'] );
	    	return $this->render('plantingstructuresearch',[
	    			'searchModel' => $searchModel,
	    			'dataProvider' => $dataProvider,
	    	]);
    	}
    }
    
    /**
     * Displays a single Plantingstructure model.
     * @param integer $id
     * @return mixed
     */
    public function actionPlantingstructureview($id)
    {
    	$model = $this->findModel($id);
    	$farm = Farms::find()->where(['id'=>$model->farms_id])->one();
    	$plantinputproductModel = Plantinputproduct::find()->where(['planting_id' => $id])->all();
    	$plantpesticidesModel = Plantpesticides::find()->where(['planting_id'=>$id])->all();
    	Logs::writeLog('查看种植结构',$id);
        return $this->render('plantingstructureview', [
            'model' => $model,
        	'plantinputproductModel' => $plantinputproductModel,
        	'plantpesticidesModel' => $plantpesticidesModel,
        	'farm' => $farm,
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
    //已经使用投入品的面积
    public function actionPlantingstructuregetarea($zongdi) 
    {
    	$area = Lease::getListArea($zongdi);
    	echo json_encode(['status'=>1,'area'=>$area]);
    }
    //获取作物面积
    public function actionGetplantarea($farms_id,$plant_id)
    {
    	$area = 0;
    	$planting = Plantingstructure::find()->where(['farms_id'=>$farms_id,'plant_id'=>$plant_id])->all();
    	foreach ($planting as $value) {
    		$area += $value['area'];
    	}
    	echo json_encode(['status'=>1,'area'=>$area]);
    }
    
    public function actionPlantingstructurecreate($lease_id,$farms_id)
    {
    	
    	$model = new Plantingstructure();
    
    	$farm = Farms::find()->where(['id'=>$farms_id])->one();
    	$zongdi = Plantingstructure::getNoZongdi($lease_id, $farms_id);
    	$plantinputproductModel = new Plantinputproduct();
    	$plantpesticidesModel = new Plantpesticides();
    	if ($model->load(Yii::$app->request->post())) {
    		
    		//$model->zongdi = Lease::getZongdi($model->zongdi);
    		$model->create_at = time();
    		$model->update_at = time();
    		$model->save();
    		
    		$new = $model->attributes;
    		//var_dump($new);
    		Logs::writeLog('为'.Lease::find()->where(['id'=>$lease_id])->one()['lessee'].'创建种植结构信息',$model->id,'',$new);
    		
    		
    		//$plantinputproducts = Plantinputproduct::find()->where(['farms_id'=>$planting->farms_id,'lessee_id'=>$planting->lease_id,'plant_id'=>$planting->plant_id,'zongdi'=>$planting->zongdi])->all();
    		$parmembersInputproduct = Yii::$app->request->post('PlantInputproductPost');
    		//var_dump($parmembersInputproduct);
    		if ($parmembersInputproduct) {
    			//var_dump($parmembers);
    			for($i=1;$i<count($parmembersInputproduct['inputproduct_id']);$i++) {
    				$plantinputproductModel = new Plantinputproduct();
    				$plantinputproductModel->farms_id = $model->farms_id;
    				$plantinputproductModel->lessee_id = $model->lease_id;
    				$plantinputproductModel->zongdi = $model->zongdi;
    				$plantinputproductModel->plant_id = $model->plant_id;
    				$plantinputproductModel->planting_id = $model->id;
    				$plantinputproductModel->father_id = $parmembersInputproduct['father_id'][$i];
    				$plantinputproductModel->son_id = $parmembersInputproduct['son_id'][$i];
    				$plantinputproductModel->inputproduct_id = $parmembersInputproduct['inputproduct_id'][$i];
    				$plantinputproductModel->pconsumption = $parmembersInputproduct['pconsumption'][$i];
    				$plantinputproductModel->create_at = time();
    				$plantinputproductModel->update_at = time();
    				$plantinputproductModel->save();
    				$new = $plantinputproductModel->attributes;
    				//var_dump($new);
    				Logs::writeLog('添加投入品',$plantinputproductModel->id,'',$new);
    			}
    		}
    		$parmembersPesticides = Yii::$app->request->post('PlantpesticidesPost');
    		//var_dump($parmembersPesticides);
    		if($parmembersPesticides) {
    			for($i=1;$i<count($parmembersPesticides['pesticides_id']);$i++) {
    				$plantpesticidesModel = new Plantpesticides();
    				$plantpesticidesModel->farms_id = $model->farms_id;
    				$plantpesticidesModel->lessee_id = $model->lease_id;
    				$plantpesticidesModel->plant_id = $model->plant_id;
    				$plantpesticidesModel->planting_id = $model->id;
    				$plantpesticidesModel->pesticides_id = $parmembersPesticides['pesticides_id'][$i];
    				$plantpesticidesModel->pconsumption = $parmembersPesticides['pconsumption'][$i];
    				$plantpesticidesModel->create_at = time();
    				$plantpesticidesModel->update_at = time();
    				$plantpesticidesModel->save();
    				$new = $plantpesticidesModel->attributes;
    				Logs::writeLog('添加投入品',$plantpesticidesModel->id,'',$new);
    			}
    		}
    		
    		return $this->redirect(['plantingstructureindex', 'farms_id' => $farms_id]);
    	} else {
    		return $this->render('plantingstructurecreate', [
    				'plantinputproductModel' => $plantinputproductModel,
    				'plantpesticidesModel' => $plantpesticidesModel,
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
        $plantinputproductModel = Plantinputproduct::find()->where(['planting_id' => $id])->all();
        $plantpesticidesModel = Plantpesticides::find()->where(['planting_id'=>$id])->all();
        
        if ($model->load(Yii::$app->request->post())) {
        	$model->update_at = time();
        	$model->save();
        	$new = $model->attributes;
        	Logs::writeLog('更新租赁信息',$id,$old,$new);
//         	var_dump($model->farms_id);
//         	exit;
        	$parmembersInputproduct = Yii::$app->request->post('PlantInputproductPost');
        	$this->deletePlantinput($plantinputproductModel, $parmembersInputproduct['id']);
        	if ($parmembersInputproduct) {
        		//var_dump($parmembers);
        		for($i=1;$i<count($parmembersInputproduct['inputproduct_id']);$i++) {
        			$plantinputproductModel = Plantinputproduct::findOne($parmembersInputproduct['id'][$i]);
        			if(empty($plantinputproductModel))
        				$plantinputproductModel = new Plantinputproduct();
        			$plantinputproductModel->id = $parmembersInputproduct['id'][$i];
        			$plantinputproductModel->farms_id = $model->farms_id;
        			$plantinputproductModel->lessee_id = $model->lease_id;
        			$plantinputproductModel->zongdi = $model->zongdi;
        			$plantinputproductModel->plant_id = $model->plant_id;
        			$plantinputproductModel->planting_id = $model->id;
        			$plantinputproductModel->father_id = $parmembersInputproduct['father_id'][$i];
        			$plantinputproductModel->son_id = $parmembersInputproduct['son_id'][$i];
        			$plantinputproductModel->inputproduct_id = $parmembersInputproduct['inputproduct_id'][$i];
        			$plantinputproductModel->pconsumption = $parmembersInputproduct['pconsumption'][$i];
        			$plantinputproductModel->create_at = time();
        			$plantinputproductModel->update_at = time();
        			$plantinputproductModel->save();
        			$new = $plantinputproductModel->attributes;
        			//var_dump($new);
        			Logs::writeLog('添加投入品',$plantinputproductModel->id,'',$new);
        		}
        	}
        	//exit;
        	$parmembersPesticides = Yii::$app->request->post('PlantpesticidesPost');
        	$this->deletePlantpesticides($plantpesticidesModel, $parmembersPesticides['id']);
        	if($parmembersPesticides) {
        		for($i=1;$i<count($parmembersPesticides['pesticides_id']);$i++) {
        			$plantpesticidesModel = Plantpesticides::findOne($parmembersPesticides['id'][$i]);
        			if(empty($plantpesticidesModel))
        				$plantpesticidesModel = new Plantpesticides();
        			$plantpesticidesModel->farms_id = $model->farms_id;
        			$plantpesticidesModel->lessee_id = $model->lease_id;
        			$plantpesticidesModel->plant_id = $model->plant_id;
        			$plantpesticidesModel->planting_id = $model->id;
        			$plantpesticidesModel->pesticides_id = $parmembersPesticides['pesticides_id'][$i];
        			$plantpesticidesModel->pconsumption = $parmembersPesticides['pconsumption'][$i];
        			$plantpesticidesModel->create_at = time();
        			$plantpesticidesModel->update_at = time();
        			$plantpesticidesModel->save();
        			$new = $plantpesticidesModel->attributes;
        			Logs::writeLog('添加投入品',$plantpesticidesModel->id,'',$new);
        		}
        	}
            return $this->redirect(['plantingstructureindex', 'farms_id' => $model->farms_id]);
        } else {
            return $this->render('plantingstructureupdate', [
            	'plantinputproductModel' => $plantinputproductModel,
            	'plantpesticidesModel' => $plantpesticidesModel,
                'model' => $model,
            	'farm' => $farm,
            	'zongdi' => $zongdi,
            	//'leases' => $lease,
            ]);
        }
    }

    private function deletePlantinput($nowdatabase,$postdataidarr) {
    	$databaseid = array();
    	foreach($nowdatabase as $value) {
    		$databaseid[] = $value['id'];
    	}
    	$result = array_diff($databaseid,$postdataidarr);
    	if($result) {
    		foreach($result as $val) {
    			$model = Plantinputproduct::findOne($val);
    			//var_dump($model->attributes);
    			$oldAttr = $model->attributes;
    			Logs::writeLog('删除投入品',$val,$oldAttr);
    			$model->delete();
    		}
    		return true;
    	} else
    		return false;
    }
    
    private function deletePlantpesticides($nowdatabase,$postdataidarr) {
    	$databaseid = array();
    	foreach($nowdatabase as $value) {
    		$databaseid[] = $value['id'];
    	}
    	$result = array_diff($databaseid,$postdataidarr);
    	if($result) {
    		foreach($result as $val) {
    			$model = Plantpesticides::findOne($val);
    			$oldAttr = $model->attributes;
    			Logs::writeLog('删除投入品',$val,$oldAttr);
    			$model->delete();
    		}
    		return true;
    	} else
    		return false;
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
		$plantInput = Plantinputproduct::find()->where(['planting_id'=>$id])->all();
		foreach ($plantInput as $value) {
			$plantinputModel = Plantinputproduct::findOne($value['id']);
			$old = $plantinputModel->attributes;
			Logs::writeLog('删除种植结构的关联投入品',$id,$old);
			$plantinputModel->delete();
		}
		$plantpesticides = Plantpesticides::find()->where(['planting_id'=>$id])->all();
		foreach ($plantpesticides as $value) {
			$plantpesticidesModel = Plantpesticides::findOne($value['id']);
			$old = $plantpesticidesModel->attributes;
			Logs::writeLog('删除种植结构的关联农药',$id,$old);
			$plantpesticidesModel->delete();
		}
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
