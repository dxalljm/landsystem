<?php
namespace backend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
	public $groups;
	public $department_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

//             ['email', 'filter', 'filter' => 'trim'],
//             ['email', 'required'],
//             ['email', 'email'],
//             ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
			
			['groups', 'string', 'max' => 20],
        	 [['department_id'], 'integer'],
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
            $user->department_id = $this->department_id;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->year = date('Y');
            $user->autoyear = 1;
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
