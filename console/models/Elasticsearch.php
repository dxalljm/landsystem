<?php

namespace console\models;


/**
 * This is the model class for table "{{%farms}}".
 *
 * @property integer $id
 * @property string $farmname
 * @property string $address
 * @property string $management_area
 * @property string $spyear
 * @property integer $measure
 * @property string $zongdi
 * @property string $cooperative_id
 * @property string $surveydate
 * @property string $groundsign
 * @property string $investigator
 * @property string $farmersign
 */
class Elasticsearch extends \yii\elasticsearch\ActiveRecord
{
 	public static function getAtt()
	{
		$model = new Farms();
		$attributes = $model->getAttributes();
		$result = ['index','type'];
		foreach($attributes as $key => $value) {
			$result[] = $key;
		}
		return $result;
	}
 	public function attributes()
    {
    	return self::getAtt();
    }
}
