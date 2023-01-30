<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'ID Pengguna',
            'password' => 'Kata Laluan',
            'rememberMe' => 'Ingat Log Masuk Saya',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if($user == "400"){
                $this->addError($attribute, 'Kata Laluan Tidak Sah.Sila Kemaskini Kata Laluan anda di Sistem eJKP.');
            }elseif (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Pengguna tidak wujud di Sistem eJKP. Sila hubungi Pentadbir Sistem eJKP.');
            }else{

            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    // public function login()
    // {
    //     if ($this->validate()) {
    //         return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
    //     } else {
    //         return false;
    //     }
    // }

    public function login() {
        if ($this->validate()&& $this->choice == 1) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        else if ($this->validate()&& $this->choice == 2) {
            return Yii::$app->pruser->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    // protected function getUser()
    // {
    //     if ($this->_user === null) {
    //         $this->_user = User::findByUsername($this->username,$this->password);
    //     }

    //     return $this->_user;
    // }

    public function getUser() {
        if ($this->_user === false && $this->choice == 1) {            
            $this->_user = User::findByUsername($this->username);
        }
    
        else if ($this->_user === false && $this->choice == 2) {
            $this->_user = PrAkses::findByUsername($this->username);
        }
    
        return $this->_user;
    }
}
