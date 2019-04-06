<?php

namespace app\models;

use Yii;
use yii\widgets\Menu;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%mainmenu}}".
 *
 * @property integer $id
 * @property string $menuname
 * @property string $menuurl
 */
class Mainmenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mainmenu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menuname', 'menuurl'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'menuname' => '菜单名称',
            'menuurl' => '菜单地址',
        ];
    }

    public static function isDropdown($strMenuID)
    {
    	$menuArr = explode(',', $strMenuID);
    	foreach ($menuArr as $value) {
    		$menuname = Mainmenu::find()->where(['id'=>$value])->one()['menuname'];
    		if($menuname == 'dropdown')
    			return true;
    		else
    			return false;
    	}
    }

    public static function getTenMenu()
    {
        $result = [];
        $menus = Mainmenu::find()->where(['id'=>[20,21,24,25,26,27,71,72]])->all();
        $result = ArrayHelper::map($menus,'id','menuname');
        $result['lease'] = '租赁备案';
        return $result;
    }

    public static function getBusinessMenu()
    {
        $result = [];
        $menus = Mainmenu::find()->where(['typename'=>2])->all();
        return ArrayHelper::map($menus,'id','menuname');
    }


    public static function getTenMenuOne($id)
    {
        $array = self::getTenMenu();
        if($id)
            return $array[$id];
        else
            return '';
    }

    public static function getConditionList()
    {
        $result = [];
        if(isset($_GET['farmsSearch']['businesstype'])) {
            switch ($_GET['farmsSearch']['businesstype']) {
                case 20:
                    $result = ['无宗地', '有宗地'];
                    break;
                case 21:
                    $result = ['未种植', '已种植'];
                    break;
                case 24:
                    $result = ['未收缴', '已收缴'];
                    break;
                case 25:
                    $result = ['未完成', '已完成','部分完成'];
                    break;
                case 26:
                    $result = ['无畜牧', '有畜牧'];
                    break;
                case 27:
                    $result = ['无项目', '有项目'];
                    break;
                case 71:
                    $result = ['未参加', '已参加'];
                    break;
                case 72:
                    $result = ['未贷款', '已贷款'];
                    break;
                case 'lease':
                    $result = ['未租赁','有租赁'];
            }
        }
        return $result;
    }

    public static function getConditonListOne($id,$key=null)
    {
        $array = self::getConditionList($id);
        if($array and !($key === '')) {
            return $array[$key];
        }
        else
            return '';
    }
}
