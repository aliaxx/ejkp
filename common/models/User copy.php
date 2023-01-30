<?php
namespace common\models;

use Yii;
use kartik\widgets\Growl;
/**
 * User model
 */
class User extends \common\models\Pengguna implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */

    public $password_pruser;
    
    public static function findIdentity($id)
    {
        return static::findOne(['ID' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return true;
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByUsername($username,$password)
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


            $this->password_pruser = $USERPASSWORD;

            // var_dump($this->password_pruser);
            // exit();

            //if password match then,
            //data akan update at tbpengguna sekiranya user ada buat perubahan details di pruser.
            if ($USERPASSWORD == $passwordmd5){ 
                // var_dump($passwordmd5);
                // exit();
                $result = \Yii::$app->db->createCommand("UPDATE C##EJKP.TBPENGGUNA SET NOKP = '$nokp',NAMA ='$name',EMAIL = '$email' WHERE ID='$userid'")->execute();
            }

            //$A = static::findOne(['USERNAME' => $username]);

            return static::findOne(['USERNAME' => $username]);
        }
    }
    
    

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->AUTH_KEY;
    }

    /**
     * {@inheritdoc}
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
        // $passwordmd5 = strtoupper(md5($password));

            // var_dump($password);
            // var_dump("<br>");
            // var_dump(md5($password));
            // var_dump("<br>");
            // var_dump($this->password_pruser);
            // exit();
        return (md5($password) == $this->USERPASSWORD);
    }

    // public function validatePassword($password)
    // {
        
    //     $get_data = (new \yii\db\Query())
    //     ->select('*')
    //     ->from('C##MAJLIS.PRUSER')
    //     ->where(['USERNAME' => $password])
    //     ->all(); 
        
    //     if($get_data){ //record found in majlis.pruser 

    //     //Data2 yang akan update di TBPengguna mengikut data paling latest di PRUser.
    //     $nokp  = ($get_data[0]['NIRC']);
    //     $USERPASSWORD = ($get_data[0]['USERPASSWORD']);

    //     return (md5($password) == $USERPASSWORD);
    //     }
    // }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    // public function setPassword($password)
    // {
    //     $this->user->USERPASSWORD= md5($password);
    // }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->AUTH_KEY = Yii::$app->security->generateRandomString();
    }

    public function getRoles()
    {
        return \codetitan\helpers\RbacHelper::getRoles($this->ID);
    }

    public function hasRoles($criterias, $operator = 'OR')
    {
        return \codetitan\helpers\RbacHelper::hasRoles($this->ID, $criterias, $operator);
    }

    public function setRoles($roles)
    {
        \codetitan\helpers\RbacHelper::setRoles($this->ID, $roles);
    }

    // TODO
    /*public function saveImage()
    {
        if ($this->image) {
            $filename = $this->id.'.'.$this->image->extension;
            $this->image->saveAs(Yii::getAlias('@webroot/images/avatars/').$filename);

            $this->avatar = $filename;
            $this->save(false);
            return true;
        }

        return false;
    }*/
}
