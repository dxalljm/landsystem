<?php

namespace app\models;

use Yii;

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
    		if($value == 18)
    			return true;
    		else
    			return false;
    	}
    }
}
