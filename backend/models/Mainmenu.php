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
            [['sort','typename'], 'integer'],
<<<<<<< HEAD
            [['menuname', 'menuurl','dropdown','level','class'], 'string', 'max' => 500]
=======
            [['menuname', 'menuurl','dropdown'], 'string', 'max' => 500]
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
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
            'sort' => '排序',
        	'typename' => '菜单类型',
<<<<<<< HEAD
            'dropdown' => '下拉菜单标识',
            'level' => '访问等级',
            'class' => '可访问类',
=======
            'dropdown' => '下拉菜单标识'
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
        ]; 
    }
    public static function getLevelMenu($level)
    {
        $result = [];
        $menus = Mainmenu::find()->orderBy('sort ASC')->all();
        foreach ($menus as $menu) {
            $levelArray = explode(',',$menu['level']);
            if(in_array($level,$levelArray)) {
                $result[] = $menu['id'];
            }
        }
        return $result;
    }
}
