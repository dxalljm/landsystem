<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%menu_to_user}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $menulist
 */
class MenuToUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu_to_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['menulist','businessmenu','plate'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'user_id' => '用户ID',
            'menulist' => '所属导航',
        	'businessmenu' => '业务菜单',
        	'plate' => '板块',
        ];
    }
    
    public static function getUserMenu()
    {
    	$data = MenuToUser::find()->where(['role_id'=>User::getItemname()])->one()['menulist'];
    	$data = explode(',', $data);
    	$mainmenu = Mainmenu::find()->where(['id'=>$data])->all();
    	$menu = [];
    	foreach ($mainmenu as $value) {
    		$menu[$value['id']] = $value['sort'];
    	}
//     	var_dump($menu);exit;
    	asort($menu);
//     	var_dump($menu);exit;
    	$result = array_flip($menu);
    	
    	return $result;
    }
}
