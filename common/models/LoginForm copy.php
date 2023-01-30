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
    public $password_pruser;

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
            [['password_pruser'], 'safe'],
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

    // var_dump($this->password_pruser);   
    // exit(); 

    //         if (!$user) {   //record not found in majlis.pruser
    //             $this->addError($attribute, 'Rekod Pengguna tidak wujud di Sistem Majlis. Sila hubungi Pentadbir Sistem Majlis.'); 
    //         }elseif (!$user->validatePassword($this->password)) {   //rekod wujud di pruser tapi katalaluan mismatch
    //             $this->addError($attribute, 'Kata Laluan Tidak Sah.Sila Kemaskini Kata Laluan anda di Sistem Majlis.');
    //         } 
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
            //$this->_user = User::findByUsername($this->username,$this->password);
            $this->_user = $this->findPruser($this->username,$this->password);
        }

        return $this->_user;
    }

    public static function findPruser($username,$password)
    {
        //$passwordmd5 = strtoupper(md5($password));
        $passwordmd5 = md5($password);

        $get_data = (new \yii\db\Query())
                            ->select('*')
                            ->from('C##MAJLIS.PRUSER')
                            ->where(['USERNAME' => $username])
                            ->all();    

        if($get_data){ //record found in majlis.pruser 

            //Data2 yang akan update di TBPengguna mengikut data paling latest di PRUser.
            $nokp  = ($get_data[0]['NIRC']);
            $USERPASSWORD = ($get_data[0]['USERPASSWORD']);
            $name = ($get_data[0]['NAME']);
            $email = ($get_data[0]['EMAIL']);
            $userid = ($get_data[0]['USERID']);
            $password_pruser = $USERPASSWORD;

            // var_dump($password_pruser);
            // exit();


            //if password match then,
            //data akan update at tbpengguna sekiranya user ada buat perubahan details di pruser.
            if ($USERPASSWORD == $passwordmd5){ 
                // var_dump($passwordmd5);
                // exit();
                $result = \Yii::$app->db->createCommand("UPDATE C##EJKP.TBPENGGUNA SET NOKP = '$nokp',NAMA ='$name',EMAIL = '$email' WHERE ID='$userid'")->execute();
            }

            //$A = static::findOne(['USERNAME' => $username]);

            //return static::findOne(['USERNAME' => $username]);
            return User::findOne(['USERNAME' => $username]);
        }
    }    
}
