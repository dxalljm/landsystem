<?php

namespace frontend\controllers;

use Yii;
use app\models\Afterchenqian;
use frontend\models\AfterchenqianSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Farms;
use app\models\Farmer;
use app\models\Electronicarchives;
use frontend\helpers\imageClass;

/**
 * AfterchenqianController implements the CRUD actions for Afterchenqian model.
 */
class PhotographController extends Controller
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
     * Lists all Afterchenqian models.
     * @return mixed
     */
    public function actionPhotographindex($farms_id,$select=NULL)
    {
    	$eaResult = [];
    	if(!empty($select)) {
//     		var_dump($select);exit;
			$selectArray = explode('-', $select);
			$class = 'app\\models\\'.ucfirst($selectArray[0]);
// 			var_dump($class);
			if($selectArray[0] == 'electronicarchives') {
				$data = $class::find()->where(['farms_id'=>$farms_id])->orderBy('pagenumber DESC')->one();
				$ea = $class::find()->where(['farms_id'=>$farms_id])->all();
// 				$eaResult = [];
// 				$j=0;
				$eacount = count($ea);
				$i=0;
// 				var_dump($row);exit;
				for($j=0;$j<$eacount;$j++) {
					if($j%4 == 0 and $j != 0)
						$i++;
					$eaResult[$i][] = $ea[$j];
					
				}
// 				var_dump($eaResult);exit;
				$model = $class::findOne($data['id']);
				if($model)
					$pagenumber = $model->pagenumber;
				else
					$pagenumber = 0;
// 				var_dump($ea);exit;
			}
			else {
				$data = $class::find()->where(['farms_id'=>$farms_id])->one();
				$model = $class::findOne($data['id']);
				$ea = '';
				$pagenumber = 0;
			}
			
			
			$photo = $model[$selectArray[1]];
			if($photo)
				$photoInfo = imageClass::getImageInfo(iconv("UTF-8","gbk//TRANSLIT", $photo));
			else 
				$photoInfo = ['width'=>0,'height'=>0];
// 			var_dump($photoInfo);exit;
		}
		else {
			$model = Farmer::find()->where(['farms_id'=>$farms_id])->one();
			$photo = '';				
			$photoInfo = ['width'=>0,'height'=>0];
			$pagenumber = 0;
			$ea = '';
		}
// 		$farmer = 
		$farm = Farms::findOne($farms_id);
// 		$ea = Electronicarchives::find()->where(['farms_id'=>$farms_id])->all();
		$selectItem = ['prampt'=>'请选择...'];
// 		if($farmer['photo'] == '') {
			$selectItem['farmer-photo']='法人近照';
// 		}
// 		if($farmer['cardpic'] == '') {
			$selectItem['farmer-cardpic']='身份证扫描件正面';
// 		}
// 		if($farmer['cardpicback'] == '') {
			$selectItem['farmer-cardpicback']='身份证扫描件反面';
// 		}
// 		if(!$ea) {
			$selectItem['electronicarchives-archivesimage']='合同扫描件';
// 		}
			
        return $this->render('photographindex', [
           		'selectItem' => $selectItem,
        		'farms_id' => $farms_id,
        		'model' => $model,
        		'pagenumber' => $pagenumber,
        		'photo' => $photo,
        		'photoInfo' => $photoInfo,
        		'farm' => $farm,
        		'ea' => $eaResult,
        		'select' => $select,
        ]);
    }
    
    public function actionPhotogrpahcontract()
    {
    	return $this->render('photographshow', [
    			 
    	]);
    }
}
