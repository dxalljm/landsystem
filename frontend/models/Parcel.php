<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%parcel}}".
 *
 * @property integer $id
 * @property integer $serialnumber
 * @property integer $temporarynumber
 * @property string $unifiedserialnumber
 * @property string $powei
 * @property string $poxiang
 * @property string $podu
 * @property string $agrotype
 * @property string $stonecontent
 * @property double $grossarea
 * @property double $piecemealarea
 * @property double $netarea
 * @property string $figurenumber
 */
class Parcel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%parcel}}';
    }

    
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           	[['farms_id'],'integer'],
            [['unifiedserialnumber', 'temporarynumber', 'powei', 'poxiang', 'podu', 'agrotype',  'figurenumber'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'serialnumber' => '编号',
            'temporarynumber' => '地块暂编号',
            'unifiedserialnumber' => '地块统编号(地块号)',
            'powei' => '坡位',
            'poxiang' => '坡向',
            'podu' => '坡度',
            'agrotype' => '土壤类型',
            'stonecontent' => '含石量',
            'grossarea' => '毛面积',
            'piecemealarea' => '零星地类面积',
            'netarea' => '净面积',
            'figurenumber' => '图幅号',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        	'farms_id' => '农场',
        ];
    }
    
    public static function getFormatzongdi($zongdi)
    {
    	$grossarea = 0;
	    $zongdiarr = explode('、',$zongdi);

	    foreach ($zongdiarr as $zd) {
	    	$area = Lease::getArea($zd);
	    	$grossarea += $area;
	    }
	    return $grossarea;
    }
    
    public static function getAllGrossarea()
    {
    	$all = 0;
    	$parcels = Parcel::find()->all();
    	foreach($parcels as $value) {
    		$all += $value['grossarea'];
    	}
    	return $all;
    }
	
    public static function parcelState($array)
    {
//     	var_dump($array);exit;
    	if($array['state']) {
    		$arrayZongdi = explode('、', $array['zongdi']);
    		foreach ($arrayZongdi as $zongdi) {
    			$parcel = Parcel::find()->where(['unifiedserialnumber'=>Lease::getZongdi($zongdi)])->one();
//     			var_dump(Lease::getZongdi($zongdi));exit;
	    		$model = Parcel::findOne($parcel['id']);
	    		$model->farms_id = $array['farms_id'];
	    		$model->save();
    		}    		
    	} else {
	    	$parcels = Parcel::find()->where(['farms_id'=>$array['farms_id']])->all();
	    	foreach ($parcels as $parcel) {
	    		$model = Parcel::findOne($parcel['id']);
	    		$model->farms_id = NULL;
	    		$model->save();
	    	}
    	}
    }

	public static function in_parcel($value,$array,$planting_id=null)
	{
//		var_dump($value);exit;
		$disabled = false;
		$area = Lease::getArea($value);
		$zongdi = Lease::getZongdi($value);
		foreach ($array as $val) {
			$vz = Lease::getZongdi($val);
			if($zongdi == $vz) {
				$area -= Lease::getArea($val);
				if(bccomp($area,0) == 0) {
					$disabled = true;
				}
				if(!empty($planting_id)) {
					$p = Plantingstructurecheck::findOne($planting_id);
					$temp = Lease::getZongdiToNumber($p->zongdi);
					if(in_array($zongdi,explode('、',$temp))) {
						$disabled = true;
					}
				}
			}
		}
		return ['area'=>$area,'disabled'=>$disabled,'value'=>$zongdi.'('.$area.')'];
	}

	//宗地号一致时,合并面积
	public static function zongdi_merge_area($array1,$array2)
	{
		$result = [];
		$zongdiArray = array_merge($array1,$array2);
		$format = self::zongdiFormat($zongdiArray);
		foreach ($format as $key => $value) {
			$result[] = $key.'('.array_sum($value).')';
		}
//		foreach ($array1 as $a1) {
//			$num1 = Lease::getZongdi($a1);
//			$area1 = Lease::getArea($a1);
//			foreach ($array2 as $k2 => $a2) {
//				$num2 = Lease::getZongdi($a2);
//				$area2 = Lease::getArea($a2);
//				if($num1 == $num2) {
//					$area = bcadd($area1,$area2,2);
//					$result[] = $num1.'('.$area.')';
//					unset($array2[$k2]);
//				}
//			}
//		}
//		var_dump($result);
//		var_dump($array2);
//		$result = array_merge($result,$array2);
		return $result;
	}

	public static function zongdiFormat($array)
	{
		$result = [];
		foreach ($array as $value) {
			$result[Lease::getZongdi($value)][] = Lease::getArea($value);
		}
		return $result;
	}
}
