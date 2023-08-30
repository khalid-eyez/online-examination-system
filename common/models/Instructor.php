<?php

namespace common\models;
use frontend\userlogs\behaviors\AuditEntryBehaviors;
use Yii;
use kartik\validators\PhoneValidator;

/**
 * This is the model class for table "instructor".
 *
 * @property int $instructorID
 * @property int|null $userID
 * @property string $full_name
 * @property string $email
 * @property string $gender
 * @property string|null $PP
 * @property string|null $phone
 * @property User $user
 */
class Instructor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
 
    public static function tableName()
    {
        return 'instructor';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userID'], 'integer'],
            [['full_name', 'gender','email'], 'required'],
            [['full_name'], 'string', 'max' => 255],
            [['gender'], 'string', 'max' => 7],
            [['PP'], 'string', 'max' => 10],
            [['phone'], 'string', 'max' => 30],
            [['phone'], 'unique'],
            ['phone', 'k-phone','countryValue' => 'TZ'],
            ['email', 'email','message' => 'Invalid Email Address.'],
            ['email', 'unique'],
            [['userID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'instructorID' => 'Instructor ID',
            'userID' => 'User ID',
            'full_name' => 'Full Name',
            'gender' => 'Gender',
            'PP' => 'Pp',
            'phone' => 'Phone',
        ];
    }
    public function beforeSave($insert)
    {

      if($insert==false && $this->isAttributeChanged('email'))
      {
          $userID=$this->userID;
          $user=User::findOne($userID);
          $user->username=$this->email;
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userID']);
    }
    public function updateit($role)
    {
        $connection=yii::$app->db;
        $transact=$connection->beginTransaction();

        try
        {
        if($this->save() && $role->save())
        {
            $transact->commit();
            return true;
        }
        else
        {
            $transact->rollBack(); 
            return false;
        }
       }
       catch(\Exception $s)
       {
        $transact->rollBack(); 
        return false;
       }
    }
    
}
