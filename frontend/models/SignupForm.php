<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $realname;
    public $password;
    public $password_again;
	public $isNewRecord;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => '这个用户名已经被使用了.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['realname', 'filter', 'filter' => 'trim'],
            ['realname', 'required'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        		
        	['password_again', 'required'],
        	['password_again', 'string', 'min' => 6],

        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            //$user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
    
    public function signmod()
    {
    	//if ($this->validate()) {
    		$user = User::findIdentity(\Yii::$app->getUser()->id);
    		//var_dump($user);
    		$user->username = $this->username;
    		$user->realname = $this->realname;
    		//$user->email = $this->email;
    		
    		$user->setPassword($this->password);
    		$user->generateAuthKey();
    		if ($user->save()) {
    			return $user;
    		}
    	//}
    	
    	return null;
    }
}
