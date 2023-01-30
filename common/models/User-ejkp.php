<?php
namespace common\models;

use Yii;
use kartik\widgets\Growl;
use common\models\Pengguna;
/**
 * User model
 */
class User extends \common\models\Pengguna implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['ID' => $id, 'STATUS' => 1]);
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
    public static function findByUsername($username) //alia05042022
    {
        return static::findOne(['NOKP' => $username]);
    }

    public static function findByUsername1($username) //alia05042022
    {
        return static::findOne(['USERNAME' => $username]);
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
        return (md5($password) == $this->KATA_LALUAN);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->KATA_LALUAN = md5($password);
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

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedByUser()
    {
        return $this->hasOne(\common\models\User::className(), ['ID' => 'PGNDAFTAR']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedByUser()
    {
        return $this->hasOne(\common\models\User::className(), ['ID' => 'PGNAKHIR']);
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
