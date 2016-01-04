<?php

namespace app\models;

use Yii;
use yii\helpers\Json;
use app\models\ManagementArea;
use yii\helpers\Url;
use yii\helpers\Html;

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
 	public function attributes()
    {
        return [
        		'id', 
        		'index', 
        		'type', 
        		'farmername',
        		'farmname',
        		'management_area',
        		'measure',
        		'zongdi',
        		'address',
        		'create_at',
        		'update_at',
        		'pinyin',
        		'cardid',
        		'telephone',
        		'farmerpinyin',
        		'notclear',
        		'contractnumber',
        		'locked',
        		'notstate',
        		'notstateinfo'
        ];
    }
}
