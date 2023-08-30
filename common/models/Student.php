<?php

namespace common\models;
use Yii;
use kartik\validators\PhoneValidator;
use kartik\validators\EmailValidator;
use common\components\StudentRegnoValidator;

/**
 * This is the model class for table "student".
 *
 * @property string $reg_no
 * @property int|null $userID
 * @property string $fname
 * @property string $mname
 * @property string $lname
 * @property string $gender
 * @property string $email
 * @property string $DOR
 * @property string|null $phone
 * @property User $user
 */
class Student extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return 'student';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reg_no', 'fname', 'lname', 'gender', 'DOR','email'], 'required'],
            [['userID'], 'integer'],
            [['DOR'], 'safe'],
            [['reg_no'], 'string', 'max' => 100],
            [['gender'], 'string', 'max' => 7],
            [['phone'], 'string', 'max' => 30],
            ['email', 'unique', 'targetClass' => '\common\models\Student', 'message' => 'This email has already been taken.'],
            ['email','k-email','message' => 'Invalid Email Address'],
            ['phone', 'unique', 'targetClass' => '\common\models\Student', 'message' => 'phone number already taken.'],
            ['phone', 'k-phone','countryValue' => 'TZ'],
            [['reg_no'], 'unique'],
            [['reg_no'], 'trim'],
            [['reg_no'], 'k-email'],
            [['userID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'reg_no' => 'Reg. No',
            'userID' => 'User ID',
            'fname' => 'First name',
            'email'=>'E-mail',
            'mname' => 'Middle name',
            'lname' => 'Last name',
            'gender' => 'Gender',
            'DOR' => 'Dor',
            'phone' => 'Phone'
        ];
    }

    public function beforeSave($insert)
    {

      if($insert==false && $this->isAttributeChanged('reg_no'))
      {
          $userID=$this->userID;
          $user=User::findOne($userID);
          $user->username=$this->reg_no;
          $user->save();
      }


        return parent::beforeSave($insert);
    }
     
    public function beforeDelete()
    {

        $userID = $this->userID;
        $user = User::findOne($userID);

        if($user==null)
        {
            return true;
        }
        if(!$user->delete())
        {
            return false;
        }
        return parent::beforeDelete();
    }




    /**
     * Gets query for [[ProgramCode0]].
     *
     * @return \yii\db\ActiveQuery
     */

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userID']);
    }
    //get student full name
    public function getFullName(){
        return ucwords(strtolower(" ".$this->lname." ".$this->mname." ".$this->fname));
    }

    /**
     * {@inheritdoc}
     */
    public static function findReg_no($reg_no)
    {
        return static::findOne(['reg_no' => $reg_no]);
    } 

}
