<?php

namespace common\models;
use frontend\userlogs\behaviors\AuditEntryBehaviors;
use Yii;
use kartik\validators\PhoneValidator;

/**
 * This is the model class for table "admin".
 *
 * @property int $adminID
 * @property int|null $userID
 * @property int|null $collegeID
 * @property string $full_name
 * @property string $email
 * @property string|null $phone
 *
 * @property College $college
 * @property User $user
 */
class Admin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin';
    }
  
    public function behaviors()
    {
        return [
            'auditEntryBehaviors' => [
                'class' => AuditEntryBehaviors::class
             ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userID', 'collegeID'], 'integer'],
            [['full_name', 'email'], 'required'],
            [['full_name'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 30],
            [['email'], 'unique'],
            [['phone'], 'unique'],
            ['phone', 'k-phone','countryValue' => 'TZ'],
            [['collegeID'], 'exist', 'skipOnError' => true, 'targetClass' => College::className(), 'targetAttribute' => ['collegeID' => 'collegeID']],
            [['userID'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'adminID' => 'Admin ID',
            'userID' => 'User ID',
            'collegeID' => 'College ID',
            'full_name' => 'Full Name',
            'email' => 'Email',
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

    /**
     * Gets query for [[College]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCollege()
    {
        return $this->hasOne(College::className(), ['collegeID' => 'collegeID']);
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



}
