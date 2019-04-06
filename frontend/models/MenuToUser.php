<?php

namespace app\models;

use Yii;
use app\models\User;
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
            [['role_id','menulist','plate','businessmenu','searchmenu','auditinguser'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'role_id' => '角色ID',
            'menulist' => '所属导航',
        	'plate' => '八大板块',
        	'businessmenu' => '业务菜单',
        	'searchmenu' => '综合查询',
			'auditinguser' => '审核权限',
        ];
    }
    
    public function getUser()
    {
    	return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
	public static function getUserMenu()
	{
//     	var_dump(User::getItemname());exit;
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
//    	var_dump($result);
		return $result;
	}

	public static function getUserBusinessMenu()
	{
		$data = MenuToUser::find()->where(['role_id'=>User::getItemname()])->one()['businessmenu'];
		$data = explode(',', $data);
		$mainmenu = Mainmenu::find()->where(['id'=>$data])->all();
		$menu = [];
		foreach ($mainmenu as $value) {
			$menu[$value['id']] = $value['sort'];
		}
//     	    	var_dump($menu);exit;
		asort($menu);
//    	     	var_dump($menu);exit;
		$result = array_flip($menu);

		return $result;
	}
	public static function getAuditingList()
	{
		$result = [];
		$aud = Auditprocess::find()->all();
		foreach ($aud as $item) {
			$result[$item['id']] = $item['projectname'];
		}
		return $result;
	}

    public static function getSearchList()
    {
    	$class = [
    			'farms'=>'农场法人',
    			'insurance'=>'保险业务',
    			'plantingstructure'=>'种植作物',
    			'projectapplication'=>'项目申报',
    			'yields'=>'产量信息',
    			'sales'=>'销量信息',
    			'huinonggrant'=>'惠农政策',
    			'breedinfo'=>'养殖信息',
    			'prevention'=>'防疫情况',
    			'fireprevention'=>'防火情况',
    			'loan'=>'贷款情况',
    			'collection'=>'缴费情况',
    			'disaster'=>'灾害情况',
				'machineapply'=>'农机补贴',
    	];
    	return $class;
    }
    
    public static function getSearchOne($id)
    {
    	$class = self::getSearchList();
    	if($id)
    		return $class[$id];
    	else 
    		return '';
    }
    
	public static function getUserSearch()
    {
//    	$useritem = User::getItemname();
    	$searchMenuStr = Department::find()->where(['id'=>Yii::$app->getUser()->getIdentity()->department_id])->one()['searchmenu'];
    	$searchMenuArray = explode(',', $searchMenuStr);
//		$platename = User::getPlate()['name'];
//		var_dump($platename);exit;
//		$searchMenu = array_intersect($searchMenuArray,$platename);
    	$result = [];
    	foreach ($searchMenuArray as $menu) {
    		$result[$menu] = self::getSearchOne($menu);
    	}
//		var_dump($result);exit;
    	return $result;
    }
}
