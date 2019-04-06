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
use app\models\Zongdioffarm;
use app\models\User;
use app\models\ManagementArea;
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
	public function beforeAction($action)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/logout']);
		} else {
			return true;
		}
	}
//     public function beforeAction($action)
//     {
//     	$action = Yii::$app->controller->action->id;
//     	if(\Yii::$app->user->can($action)){
//     		return true;
//     	}else{
//     		throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
//     	}
//     }
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
	
//     public function actionParcellistajax()
//     {
//     	$farms_id = Yii::$app->request->get('farms_id');
//     	//if (!empty($farms_id)) {
//     		$zongdi = Farms::find()->where(['id'=>$farms_id])->one()['zongdi'];
//     		$zongdiarr = explode('、', $zongdi);
//     		foreach($zongdiarr as $value) {
//     			$zd_area[] = $value.'('.Parcel::find()->where(['unifiedserialnumber'=> $value])->one()['grossarea'].')';
//     		}
// //     		echo json_encode(['status' => 1]);
// //     		Yii::$app->end();
//     	//} else {
//     		return $this->renderAjax('parcellistajax', [
//     				'zdarea' => $zd_area,
//     		]);
//     	//}
//     }
    
    /**
     * Displays a single Parcel model.
     * @param integer $id
     * @return mixed
     */
    public function actionParcelview($id)
    {
		$model = $this->findModel($id);
    	Logs::writeLogs('查看宗地信息',$model);
        return $this->render('parcelview', [
            'model' => $model,
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
        	Logs::writeLogs('创建宗地',$model);
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
    				Logs::writeLogs('xls批量导入创建宗地信息',$parchmodel);
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
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	Logs::writeLogs('更新宗地信息',$model);
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
    	Logs::writeLogs('删除宗地',$model);
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
public  function  actionParcelarea($zongdi,$farms_id=null)
    {
    	$netarea = 0;
		$areaNumber = explode('-', $zongdi);
		$management = User::getUserManagementArea();
// 		if(in_array($areaNumber[0],$management)) {
			$parcel = Parcel::find()->where(['unifiedserialnumber' => $zongdi])->one();
	    	if($parcel) {
				$zongdiinfo = $this->findParcel($zongdi,$farms_id);
				$newzongdiinfo = $zongdiinfo;
// 		    		var_dump($zongdiinfo);
// 				if (in_array($farms_id,$zongdiinfo['farms_id'])) {
// 					$status = 1;
// 					$netarea = $newzongdiinfo['netarea'];
// 					$message = true;
// 					$showmsg = false;
// 				} else {
					if ($zongdiinfo['state']) {
						$status = 0;
						$netarea = 0;
						$showmsg = true;
						$message = '对不起' . "</br>";
						$sum = 0.0;
						unset($zongdiinfo['state']);
						unset($zongdiinfo['netarea']);
						unset($zongdiinfo['farms_id']);
						unset($zongdiinfo['measure']);
						unset($zongdiinfo[$farms_id]);
						$message .= $zongdi . '面积为' . $newzongdiinfo['measure'] . '亩<br>';
// 						var_dump($zongdiinfo);
						foreach ($zongdiinfo as $value) {
							$message .= $value['management_area'] . '-' . $value['farmname'] . '(' . $value['farmername'] . ')占用' . $value['area'] . '亩' . "</br>";
							$message .= '合同号：' . $value['contractnumber'] . '</br>';
// 							$sum += $value['area'];
						}

						if (bccomp($newzongdiinfo['netarea'], $newzongdiinfo['measure'], 2)) {
							//$cha为被占用面积
							$cha = bcsub($newzongdiinfo['netarea'], $sum, 2);
							if ($cha > 0) {
								$result = $cha;
								$status = 1;
								//剩余面积
								$netarea = $cha;
// 			    			$showmsg = true;
								$message .= '将显示剩余面积' . $cha . '亩。';
							} else {
								$status = 0;
								$netarea = 0;
// 	    					$showmsg = true;
								$message .= '没有剩余面积。';
							}
						} else {
							$status = 0;
							$netarea = 0;
// 	    				$showmsg = true;
							$message .= '没有剩余面积。';

						}
					} else {
						$status = 1;
						$netarea = $newzongdiinfo['netarea'];
						$message = true;
						$showmsg = false;
					}
// 				}
			} else {
					$status = 0;
					$showmsg = true;
					$message = '对不起，您输入的地块不存在！';
				}

// 		} else {
// 			$status = 0;
// 			$showmsg = true;
// 			$message = '对不起，您只能输入您所辖管理区的宗地号！';
// 		}
// 	    	var_dump($netarea);var_dump($message);var_dump($showmsg);
    	echo json_encode(['status' => $status, 'area'=>$netarea, 'message' => $message,'showmsg' => $showmsg]);

    }

	public function actionParcelzongdioffarmsave($farms_id,$zongdiinfo)
	{
		$model = new Zongdioffarm();
		$model->zongdinumber = Lease::getZongdi($zongdiinfo);
		$model->farms_id = $farms_id;
		$model->measure = Lease::getArea($zongdiinfo);
		$model->save();
	}

	public function actionParcelzongdioffarmdelete($farms_id,$zongdi)
	{
		$zdof = Zongdioffarm::find()->where(['zongdinumber'=>$zongdi,'farms_id'=>$farms_id])->one();
//		var_dump($zdof);exit;
		$model = Zongdioffarm::findOne($zdof['id']);
		$model->delete();
		echo json_encode(['data'=>$zdof]);
		var_dump($model->getErrors());exit;
	}
	//查找宗地信息，如果状态为真，则被占用，返回被占用的面积值和原净面积值
    private function findParcel($zongdi,$farms_id=null)
    {
    	$result = [];
		$result['farms_id'] = '';
		$farmsarea = 0.0;
    	$parcel = Parcel::find()->where(['unifiedserialnumber' => $zongdi])->one();
//     	var_dump($parcel['netarea']);
    	
    	$parcels = Zongdioffarm::find()->where(['zongdinumber' => $zongdi])->all();
//		var_dump($parcels);
    	$sum = 0.0;
//     	$state = false;
    	
    	$farmname = '';
     	if($parcels) {
			foreach ($parcels as $value) {
				$farm = Farms::find()->where(['id' => $value['farms_id']])->one();
				$result[$farm['id']]['management_area'] = ManagementArea::getAreaname($farm['management_area']);
				$result[$farm['id']]['farmname'] = $farm['farmname'];
				$result[$farm['id']]['farmername'] = $farm['farmername'];
				$result[$farm['id']]['contractnumber'] = $farm['contractnumber'];
				$result[$farm['id']]['area'] = $value['measure'];
				$result['farms_id'][] = $farm['id'];
				if($value['farms_id'] !== (int)$farms_id) { 					
					$farmsarea += $value['measure'];
				}
			}
			
		}
// 		var_dump($farmsarea);
//     	$result['area'] = $parcel['netarea'] - $sum;
    	
    	$result['netarea'] = $parcel['netarea'] - $farmsarea;
    	$result['measure'] = $parcel['netarea'];
    	if($result['netarea'] < $parcel['netarea']) {
    		$state = true;
    	} else
    		$state = false;
    	$result['state'] = $state;
//     	var_dump($result);exit;
    	return $result;
    }
    
    public function actionParcelsetfarms()
    {
    	set_time_limit ( 0 );
    	$parcels = Parcel::find()->all();
    	foreach ($parcels as $parcel) {
    		$farms = Farms::find();
    		$farm = $farms->andFilterWhere(['like','zongdi',$parcel['unifiedserialnumber']])->one();
    		$model = $this->findModel($parcel['id']);
    		if($farm) {
	    		$model->farms_id = $farm['id'];
	    		$model->save();
    		}
    	}
    	echo 'finished';
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
    	$areaSum = 0.0;
    	$format = [];
    	foreach ($zongdiarr as $key => $value) {
    		if($value == '')
    			unset($key);
    		else {
    			if(!strstr($value,'(')) {
		    		$area = Parcel::find()->where(['unifiedserialnumber' => $value])->one()['netarea'];
		    		$format[] = $value.'('.$area.')';
		    		$areaSum += $area;
    			}	
	    		else {
	    			$format[] = $value;
	    			$areaSum += Lease::getArea($value);
	    		}
    		}
    	}
    	$str = implode('、', $format);
    	echo json_encode(['status' => 1, 'formatzongdi'=>$str,'sum'=>$areaSum]);
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
