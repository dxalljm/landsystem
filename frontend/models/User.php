<?php

namespace app\models;

use yii\base\Model;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use app\models\SignupForm;
/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $groups
 */
class User extends \yii\db\ActiveRecord
{
	
	const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const ROLE_USER = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }
	
	public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'created_at', 'updated_at', 'department_id'], 'required'],
            [['status', 'created_at', 'updated_at','autoyear','level'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email','year','ip','mac'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['template'], 'string', 'max' => 100],
            [['realname','auditinguser'], 'string', 'max' => 500],
            [['mainmenu','plate'],'string'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

        ];

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'realname' => '姓名',
            'auth_key' => '认证密钥',
            'password_hash' => '密码散列',
            'password_reset_token' => '密码重置令牌',
            'email' => '电子邮箱',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'department_id' => '科室ID',
            'autoyear' => '自动获取',
            'ip' => 'ip地址',
            'level' => '职级',
            'plate' => '管辖板块',
            'mainmenu' => '导航菜单',
            'auditinguser' => '审核权限',
            'template' => '模板',
        ];
    }
	
	 public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    public static function getUserManagementArea()
    {
    	$departmentid = User::find ()->where ( [
    			'id' => \Yii::$app->getUser ()->id
    	] )->one ()['department_id'];
    	$departmentData = Department::find ()->where ( [
    			'id' => $departmentid
    	] )->one ();
    	$whereArray = explode ( ',', $departmentData ['membership'] );
    	
    	return $whereArray;
    }
    
    public static function searchManagearea()
    {
    	$departmentid = User::find ()->where ( [
    			'id' => \Yii::$app->getUser ()->id
    	] )->one ()['department_id'];
    	$departmentData = Department::find ()->where ( [
    			'id' => $departmentid
    	] )->one ();
    	$whereArray = explode ( ',', $departmentData ['membership'] );
    	if(count($whereArray) > 1) {
    		return '';
    	} else
    		return $whereArray;
    }
    
    public static function getItemname($str = null,$level = null,$username = null)
    {
        if(!empty($username)) {
            if(Yii::$app->getUser()->getIdentity()->username == $username) {
                return true;
            }
        } else {
            $levelname = Userlevel::find()->where(['id' => Yii::$app->getUser()->getIdentity()->level])->one()['levelname'];
//        var_dump($levelname);
            if (empty($str)) {

//            var_dump(self::getPlate()['name']);
                if (in_array(Yii::$app->controller->id, self::getPlate()['name'])) {
                    if (empty($level)) {
                        return true;
                    } else {
                        if ($levelname == $level) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                } else
                    return false;
            } else {
                $temp = Tempauditing::find()->where(['tempauditing' => Yii::$app->getUser()->id, 'state' => 1])->andWhere('begindate<=' . strtotime(date('Y-m-d')) . ' and enddate>=' . strtotime(date('Y-m-d')))->one();
                if ($temp) {
                    $userinfo = User::find()->where(['id' => $temp['user_id']])->one();
                    $dep = Department::find()->where(['id' => $userinfo['department_id']])->one();
                    if ($str == '地产科') {
                        $departmentname = ['地产科', '地产一组', '地产二组', '地产三组', '地产四组', '地产五组', '地产六组', '地产七组'];
                        if (in_array($dep['departmentname'], $departmentname))
                            return true;
                        else
                            return false;
                    } else {
//                     foreach (explode(',', $dep['businessmenu']) as $id) {
//                         $menuname[] = Mainmenu::find()->where(['id' => $id])->one()['menuname'];
//                     }
//                var_dump($str == $dep['departmentname']);
                        if ($str == $dep['departmentname']) {
                            if (empty($level)) {
                                return true;
                            } else {
                                if ($levelname == $level) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        } else
                            return false;
                    }
                } else {
                    $dep = Department::find()->where(['id' => Yii::$app->getUser()->getIdentity()->department_id])->one();
                    if ($str == '地产科') {
                        $departmentname = ['地产科', '地产一组', '地产二组', '地产三组', '地产四组', '地产五组', '地产六组', '地产七组'];
                        if (in_array($dep['departmentname'], $departmentname))
                            return true;
                        else
                            return false;
                    } else {
//                 	$menuid = explode(',', $dep['businessmenu']);

//                     $menuname = ArrayHelper::map(Mainmenu::find()->where(['id' => $menuid])->all(),'id','menuname');

//                var_dump($menuname);var_dump($dep['departmentname']);exit;
//                var_dump($str == $dep['departmentname']);
                        if ($str == $dep['departmentname']) {
                            if (empty($level)) {
                                return true;
                            } else {
                                if ($levelname == $level) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        } else
                            return false;
                    }
                }
            }
        }
    }
    
    public static function getUserItemname($user_id)
    {
    	return AuthAssignment::find()->where(['user_id'=>$user_id])->one()['item_name'];
    }
    
    public static function getYear()
    {
        if(Yii::$app->user->isGuest) {
            return false;
        }
    	$model = User::findOne(\Yii::$app->getUser()->id);
    	return $model->year;
    }

    public static function getLastYear()
    {
//        var_dump(Yii::$app->user->identity->id);exit;
        $model = User::findOne(Yii::$app->user->identity->id);
//        var_dump($model);exit;
        return $model->year - 1;
    }

    public static function getPlate()
    {
        $result = ['name'=>'','id'=>'','role'=>''];
        $plateArray = explode('/',Yii::$app->getUser()->getIdentity()->plate);
//        var_dump($plateArray);exit;
        if($plateArray[0]) {
            foreach ($plateArray as $value) {
                $plateValue = explode('-', $value);
                $result['name'][] = $plateValue[0];
                $result['id'][] = (int)$plateValue[1];
                $exValue = explode(',',$plateValue[2]);
                foreach ($exValue as $val) {
                    $result[] = $plateValue[1] . '-' . $val;
                }
            }
        }
//        var_dump($result);exit;
        return $result;
    }

    public static function getEcharts()
    {
        $plantarr = ArrayHelper::map(Mainmenu::find()->where(['typename'=>1])->all(), 'id', 'menuname');
        $allid = [];
        foreach ($plantarr as $key=>$value) {
            $allid[] = $key;
        }
        $plate = self::getPlate()['id'];
        $newid = array_intersect($allid,$plate);
        $result = [];
        foreach ($newid as $value) {
            $result[$value] = $plantarr[$value];
        }
        return $result;
    }

    public static function getPlateRole()
    {
//        $result = ['name'=>'','id'=>'','role'=>''];
        $plateArray = explode('/',Yii::$app->getUser()->getIdentity()->plate);
        $plateArray[] = 'log-20-view';
        if($plateArray[0]) {
            foreach ($plateArray as $value) {
                $plateValue = explode('-', $value);
                $exValue = explode(',',$plateValue[2]);
                foreach ($exValue as $val) {
                    $result[$plateValue[0]][] = $val;
                }
            }
        }
        return $result;
    }

    public static function getUserRole($action)
    {
        $plateArray = User::getPlateRole();
        $nowController = Yii::$app->controller->id;
        foreach ($plateArray as $key => $plate) {
            if($key == $nowController) {
                foreach ($plate as $p) {
                    $tempaction = $key.$p;
                    if($action == $tempaction) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public static function setAllUserYear($year)
    {
        $users = User::find()->all();
        foreach ($users as $user) {
            $model = User::findOne($user['id']);
            $model->year = $year;
            $model->save();
        }
    }

    public static function tableBegin($title)
    {
        $html = '';
        switch (Yii::$app->user->identity->template) {
            case 'default':
                $html .= '<div class="box">';
                $html .= '<div class="box-header">';
                $html .= '<h3>&nbsp;&nbsp;'.$title .'&nbsp;&nbsp;<font color="red">('.User::getYear().'年度)</font></h3></div>';
                $html .= '<div class="box-body">';
                break;
            case 'template2018':
                $html .= '<div class="card card-stats2">';
                $html .= '<div class="card-header card-header-icon" data-background-color="rose">';
                $html .= '<i class="fa fa-file-text"></i>';
                $html .= '</div>';
                $html .= '<div class="card-content">';
                if($title == '贷款' or $title == '畜牧业') {
                    $html .= '<h4 class="card-title">' . $title . '</h4>';
                } else {
                    $html .= '<h4 class="card-title">' . $title . '<font color="red">(' . User::getYear() . '年度)</font></h4>';
                }
                $html .= '<div class="table-responsive">';
                break;
        }
        echo $html;
    }

    public static function tableEnd()
    {
        $html = '';
        switch (Yii::$app->user->identity->template) {
            case 'default':
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                break;
            case 'template2018':
                $html .= '</div>';
                break;
        }
        echo $html;
    }

    public static function dataListBegin($title)
    {
        $html = '';
        switch (Yii::$app->user->identity->template) {
            case 'default':
                $html .= '<div class="box">';
                $html .= '<div class="box-header">';
                $html .= '<div class="box-body">';
                $html .= '<div class="nav-tabs-custom">';
                break;
            case 'template2018':
                $html .= '<div class="card">';
                $html .= '<div class="card-header">';
                $html .= '<h4 class="card-title">'.$title .'<font color="red">('.User::getYear().'年度)</font></h4>';
                $html .= '</div>';
                $html .= '<div class="card-content">';
                break;
        }
        echo $html;
    }

    public static function dataListEnd()
    {
        $html = '';
        switch (Yii::$app->user->identity->template) {
            case 'default':
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                break;
            case 'template2018':
                $html .= '</div>';
                $html .= '</div>';
                break;
        }
        echo $html;
    }

    public static function disabled()
    {
        if(date('Y') !== User::getYear()) {
            return true;
        }
        return false;
    }
}
