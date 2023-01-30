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
           // ['PRUSERPWD', 'string'],
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
            //$user = $this->getUser();
            $user = User::findByUsername($this->username);
            $pruser = User::getPruser($this->username);

            
            // var_dump($user);
            // exit();

            if (!$pruser) {   //record not found in majlis.pruser
                $this->addError($attribute, 'Rekod Pengguna tidak wujud di Sistem Majlis. Sila hubungi Pentadbir Sistem Majlis.'); 
            }elseif (null==$user) {
                $this->addError($attribute, 'Rekod Pengguna tidak wujud di Sistem eJKP. Sila hubungi Pentadbir Sistem eJKP untuk pendaftaran pengguna baru.'); 
            }elseif (!$user->validatePassword($this->password, $pruser)) {   //rekod wujud di pruser tapi katalaluan mismatch
                $this->addError($attribute, 'Kata Laluan Tidak Sah.Sila Kemaskini Kata Laluan anda di Sistem Majlis.');
            } 
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
}
