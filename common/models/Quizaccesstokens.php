<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use common\components\ExpireDateValidator;
use yii\base\UserException;

/**
 * This is the model class for table "quizaccesstokens".
 *
 * @property int $tokenID
 * @property int $quizID
 * @property string $token
 * @property string $created_at
 * @property string $expires_on
 * @property string|null $consumed_by
 *
 * @property Student $consumedBy
 * @property Quiz $quiz
 */
class Quizaccesstokens extends \yii\db\ActiveRecord
{

    public $num;
    public $expiretime;
    public $expiredate;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quizaccesstokens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quizID', 'token'], 'required'],
            [['quizID','num'], 'integer'],
            ['num',
             'compare', 
             'compareValue' => 1000, 
             'operator' => '<=',
             'message'=>'quantity is a number less than or equal to 1000; you are still allowed to generate as many times as you want'],
            [['created_at', 'expires_on','expiretime','expiredate'], 'safe'],
            ['expiredate',ExpireDateValidator::className()],
            [['token'], 'string', 'max' => 255],
            [['token'], 'unique'],
            [['consumed_by'], 'string', 'max' => 20],
            [['consumed_by'], 'exist', 'skipOnError' => true, 'targetClass' => Student::className(), 'targetAttribute' => ['consumed_by' => 'reg_no']],
            [['quizID'], 'exist', 'skipOnError' => true, 'targetClass' => Quiz::className(), 'targetAttribute' => ['quizID' => 'quizID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tokenID' => 'Token ID',
            'quizID' => 'Quiz ID',
            'token' => 'Token',
            'num'=>'quantity',
            'created_at' => 'Created At',
            'expires_on' => 'Expire Date',
            'consumed_by' => 'Consumed By',
        ];
    }

    /**
     * Gets query for [[ConsumedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConsumedBy()
    {
        return $this->hasOne(Student::className(), ['reg_no' => 'consumed_by']);
    }

    /**
     * Gets query for [[Quiz]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuiz()
    {
        return $this->hasOne(Quiz::className(), ['quizID' => 'quizID']);
    }

    public function generateTokens($quiz)
    { 
       if((Quiz::findOne($quiz))->isExpired())
       {
        throw new UserException("Cannot generate tokens for an expired assessment !");
       }
       $count=0;
       for($i=0;$i<$this->num;$i++)
       {
        try
        {
         $token=base64_encode(uniqid().$quiz);
         $this->token=$token;
         $this->created_at=date("Y-m-d H:i:s");
         $this->expires_on=$this->expiredate." ".$this->expiretime;
         $this->quizID=$quiz;
         if(!$this->save())
         {
            throw new \Exception(Html::errorSummary($this));
         }
         else
         {
            $count++;
         }
          $this->isNewRecord=true;
          $this->tokenID=null;
        }
        catch(\Exception $t)
        {
            throw $t;
            continue;
        }
       }

       return $count;
    }
    public function isExpired()
    {
        $now=strtotime(date("Y-m-d H:i:s"));
        $expiredate=strtotime($this->expires_on);

        return $expiredate<$now;
    }
    public function isUsed()
    {
        return $this->consumed_by!=null;
    }

    public function isValid()
    {
        if($this->isExpired() || $this->isUsed())
        {
            return false;
        }

        return true;
    }

}
