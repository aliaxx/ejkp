<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "PRUSER".
 *
 * @property string $USERID
 * @property string $USERNAME
 * @property string $USERPASSWORD
 * @property string $USERGROUPCODE
 * @property string $USERTYPECODE
 * @property string $CUSTOMERID
 * @property string $DEPARTMENTCODE
 * @property string $OFFICERID
 * @property string $IMAGEFILE
 * @property string $CREATEDDATE
 * @property string $USERLOGINSTATUSCODE
 * @property string $STATUSCODE
 * @property string $USERLOGstringIMESTAMP
 * @property string $NIRC
 * @property string $NAME
 * @property string $CUSTOMERID
 * @property string $MOBILE_PHONE
 * @property string $OFFICE_PHONE
 * @property string $IMAGEFILE
 * @property string $OFFICE_EXT
 * @property string $HOME_PHONE
 * @property string $ADDRESS
 * @property string $POSCODE
 * @property string $STATE
 * @property string $CITY
 * @property string $JANTINA
 * @property string $BANGSA
 * @property string $AGAMA
 * @property string $LASTUPDATE
 * @property string $DOB
 * @property string $DESIGNATION
 * @property string $EMAIL
 * @property string $USERCHANGEPASSWORDTIMESTAMP
 * @property string $PWD_NEW
 * 
 */
class Pruser extends \yii\db\ActiveRecord  
{

    public $ID,$AUTH_KEY,$NOKP,$KATA_LALUAN,$SUBUNIT,$NAMA,$DATA_FILTER,$PERANAN,$STATUSPGNDAFTAR,$TRKHDAFTAR,$PGNAKHIR,$TRKHAKHIR;
    public $primaryKey = false;
    
    // get database
    public static function getDb() {
        return Yii::$app->db3;
         // second database
    }

    public static function tableName()
    {
        return '{{PRUSER}}';
    }

    //declare fake primary key
    public static function primaryKey()
    {
        return ['USERID'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['USERID'], 'string', 'max' => 50],
            [['USERNAME', 'USERPASSWORD'], 'string', 'max' => 100],
            [['USERGROUPCODE','USERTYPECODE','CUSTOMERID','DEPARTMENTCODE','OFFICERID'], 'string', 'max' => 20],
            [['IMAGEFILE', 'NAME'], 'string', 'max' => 100],
            [['CREATEDDATE','USERLOGstringIMESTAMP','DOB','USERCHANGEPASSWORDTIMESTAMP'], 'safe'],
            [['USERLOGINSTATUSCODE','STATUSCODE','NIRC','HOME_PHONE'], 'string', 'max' => 20],
            [['MOBILE_PHONE','OFFICE_PHONE'], 'string', 'max' => 20],
            [['OFFICE_EXT'], 'string', 'max' => 6],
            [['ADDRESS', 'EMAIL','PWD_NEW'], 'string', 'max' => 100],
            [['POSCODE'], 'string', 'max' => 10],
            [['STATE'], 'string', 'max' => 20],
            [['CITY'], 'string', 'max' => 60],
            [['LASTUPDATE'], 'string', 'max' => 12],
            [['DESIGNATION'], 'string', 'max' => 4],
            [['JANTINA','BANGSA','AGAMA'], 'string', 'max' => 2],
            [['AUTH_KEY'], 'string', 'max' => 30],
            //[['NO_AKAUN'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'USERID'=> 'ID',
            'USERNAME'=> 'Username',
            'USERPASSWORD'=> 'Kata Laluan',
            'USERGROUPCODE'=> 'Kod Kumpulan',
            'USERTYPECODE'=> 'Jenis Kod',
            'CUSTOMERID'=> 'ID Pelanggan',
            'DEPARTMENTCODE'=> 'Kod Jabatan',
            'OFFICERID'=> 'Ofis ID',
            'IMAGEFILE'=> 'Gambar',
            'CREATEDDATE'=> 'Tarikh Daftar',
            'USERLOGINSTATUSCODE'=> 'Login Status Kod',
            'STATUSCODE'=> 'Status Kod',
            'USERLOGstringIMESTAMP'=> 'Waktu Pengguna login',
            'NIRC'=> 'No.K/P Pengenalan',
            'NAME'=> 'Nama',
            'MOBILE_PHONE'=> 'No.Telefon',
            'OFFICE_PHONE'=> 'No.Telefon Ofis',
            'OFFICE_EXT'=> 'Ofis',
            'HOME_PHONE'=> 'No.Telefon Rumah',
            'ADDRESS'=> 'Alamat',
            'POSCODE'=> 'Poskod',
            'STATE'=> 'Negeri',
            'CITY'=> 'Bandar',
            'JANTINA'=> 'Jantina',
            'BANGSA'=> 'Bangsa',
            'AGAMA'=> 'Agama',
            'LASTUPDATE'=> 'Tarikh akhir Kemaskini',
            'DOB'=> 'Tarikh Lahir',
            'DESIGNATION'=> 'Jawatan',
            'EMAIL'=> 'Email',
            'USERCHANGEPASSWORDTIMESTAMP'=> 'Tarikh tukar kata laluan',
            'PWD_NEW'=> 'Kata Laluan Baru',
        ];
    }

}
