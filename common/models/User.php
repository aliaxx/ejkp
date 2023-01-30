<?php
namespace common\models;

use Yii;
use kartik\widgets\Growl;
/**
 * User model
 */

class User extends \common\models\Pengguna implements \yii\web\IdentityInterface
{
    //public $PRUSERPWD;
    /**
     * {@inheritdoc}
     */
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

    // /**
    //  * Finds user by username
    //  *
    //  * @param string $username
    //  * @return static|null
    //  */
    // public static function findByUsernameOLD($username,$password)
    // {
    //     $passwordmd5 = md5($password);

    //     var_dump("findByUsername1");
    //     var_dump("<br>");
    //     var_dump("passwordmd5 : " . $passwordmd5);
    //     var_dump("<br>");

    //     $get_data = (new \yii\db\Query())
    //                         ->select('*')
    //                         ->from('C##MAJLIS.PRUSER')
    //                         ->where(['USERNAME' => $username])
    //                         ->all();    
    //     var_dump("findByUsername2");
    //     var_dump("<br>");
    //     var_dump("get_data : ");
    //     var_dump($get_data);
    //     var_dump("<br>");
   

    //     if($get_data){ //record found in majlis.pruser 

    //         //Data2 yang akan update di TBPengguna mengikut data paling latest di PRUser.
    //         $nokp  = ($get_data[0]['NIRC']);
    //         $USERPASSWORD = ($get_data[0]['USERPASSWORD']);
    //         $name = ($get_data[0]['NAME']);
    //         $email = ($get_data[0]['EMAIL']);
    //         $userid = ($get_data[0]['USERID']);

    //         //setPassword($USERPASSWORD);

    //         $PRUSERPWD = $USERPASSWORD;

    //         var_dump("findByUsername3");
    //         var_dump("<br>");
    //         var_dump("USERPASSWORD : " .$PRUSERPWD);
    //         var_dump("<br>");   

    //         //if password match then,
    //         //data akan update at tbpengguna sekiranya user ada buat perubahan details di pruser.
    //         if ($USERPASSWORD == $passwordmd5){ 
    //             var_dump("passwordmd5 : " .$passwordmd5);
      
    //             var_dump("<br>");
             
    //             // var_dump($passwordmd5);
    //             // exit();
    //             $result = \Yii::$app->db->createCommand("UPDATE C##EJKP.TBPENGGUNA SET NOKP = '$nokp',NAMA ='$name',EMAIL = '$email' WHERE ID='$userid'")->execute();

    //             // var_dump($result);
    //             // var_dump("<br>");
    //             //exit();
    //         }else{
            
    //             echo Growl::widget([
    //                 'type' => Growl::TYPE_DANGER,
    //                 'title' => 'Oh snap!',
    //                 'icon' => 'glyphicon glyphicon-remove-sign',
    //                 'body' => 'Pengguna Tidak Wujud di INSID.',
    //                 'showSeparator' => true,
    //                 'delay' => 300,
    //                 'pluginOptions' => [
    //                     'showProgressbar' => true,
    //                     'placement' => [
    //                         'from' => 'top',
    //                         'align' => 'right',
    //                     ]
    //                 ]
    //             ]);

    //         }
    //         return static::findOne(['USERNAME' => $username]);
    //     } 
    // }
    
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
    //public function validatePassword($password)
    public function validatePassword($password=null,$pruser)
    {

        // var_dump($pruser);
        // exit();
        if (md5($password) == $pruser['USERPASSWORD']){ 
            //Data2 yang akan update di TBPengguna mengikut data paling latest di PRUser.
            $nokp  = $pruser['NIRC'];
            $name = $pruser['NAME'];
            $email = $pruser['EMAIL'];
            $userid = $pruser['USERID'];

            $ssql = "UPDATE C##EJKP.TBPENGGUNA SET NOKP = '$nokp',NAMA ='$name',EMAIL = '$email' WHERE ID='$userid'";
            $result = \Yii::$app->db->createCommand($ssql)->execute();
        }

        return (md5($password) == $pruser['USERPASSWORD']);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        
        $this->user->PRUSERPWD= $password;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->AUTH_KEY = Yii::$app->security->generateRandomString();
    }

    public function getRoles()
    {
        return \codetitan\helpers\RbacHelper::getRoles($this->id);
    }

    public function hasRoles($criterias, $operator = 'OR')
    {
        return \codetitan\helpers\RbacHelper::hasRoles($this->id, $criterias, $operator);
    }

    public function setRoles($roles)
    {
        \codetitan\helpers\RbacHelper::setRoles($this->id, $roles);
    }
  
    public static function getPruser($username)
    {
        $sql = "Select * from C##MAJLIS.PRUSER where USERNAME  =  '$username'";
        $get_data = \Yii::$app->db->createCommand($sql)->queryOne();
        return $get_data;
    }  
    
/**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        //return static::findOne(['nokp' => $username, 'status' => 1]);   
        return static::findOne(['USERNAME' => $username, 'STATUS' => 1]);
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
