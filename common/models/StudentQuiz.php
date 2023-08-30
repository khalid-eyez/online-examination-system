<?php

namespace common\models;
use frontend\userlogs\behaviors\AuditEntryBehaviors;
use Yii;
use frontend\modules\assessments\models\QuizManager;
use yii\base\UserException;

/**
 * This is the model class for table "student_quiz".
 *
 * @property int $SQ_ID
 * @property string $reg_no
 * @property int $quizID
 * @property float|null $score
 * @property string|null $status
 * @property int|null $markables
 * @property string|null $file
 * @property string $attempt_time
 * @property string|null $submit_time
 * @property Quiz $quiz
 * @property Student $regNo
 */
class StudentQuiz extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student_quiz';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reg_no', 'quizID'], 'required'],
            [['quizID','markables'], 'integer'],
            [['score'], 'number'],
            [['reg_no'], 'string', 'max' => 20],
            [['file'], 'string', 'max' => 30],
            [['quizID'], 'exist', 'skipOnError' => true, 'targetClass' => Quiz::className(), 'targetAttribute' => ['quizID' => 'quizID']],
            [['reg_no'], 'exist', 'skipOnError' => true, 'targetClass' => Student::className(), 'targetAttribute' => ['reg_no' => 'reg_no']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'SQ_ID' => 'Sq  ID',
            'reg_no' => 'Reg No',
            'quizID' => 'Quiz ID',
            'score' => 'Score',
        ];
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

    /**
     * Gets query for [[RegNo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegNo()
    {
        return $this->hasOne(Student::className(), ['reg_no' => 'reg_no']);
    }

    public function isRegistered($student,$quiz)
    {
        $registered=$this->find()->where(["reg_no"=>$student,"quizID"=>$quiz])->one();

        return $registered!=null;
    }

    public function isSubmitted($quiz)
    {
        $student=yii::$app->user->identity->username;
        $submitted=$this->find()->where(["reg_no"=>$student,"quizID"=>$quiz])->one();
        
        if($submitted==null){return false;}
        
        return ($submitted->status=="submitted" || $submitted->status=="marked");
    }
    public function getStudentScore($quiz)
    {
        $student=yii::$app->user->identity->student->reg_no;
        $submitted=$this->find()->where(["reg_no"=>$student,"quizID"=>$quiz])->one();

        return $submitted->score;
    }
    public function getStudentSubmittedFile($quizID)
    {
        $student=yii::$app->user->identity->username;
      $file=$this->find()->where(['quizID'=>$quizID,'reg_no'=>$student])->one();
      $file=$file->file!=null?$file->file:null;
      return $file;
    }
    public function isSubmitTimeOver($quiz,$submit_time)
    {
        $student=yii::$app->user->identity->student->reg_no;
        $registered=$this->find()->where(["reg_no"=>$student,"quizID"=>$quiz])->one();
        $attempt_time=null;
        $duration=$registered->quiz->duration;
        if($registered->quiz->attempt_mode=="massive")
        {
            $attempt_time=$registered->quiz->start_time;
        }
        else
        {
            $attempt_time=$registered->attempt_time;
        }
        $start=new \DateTime($attempt_time);
        $start->modify ("+{$duration} minutes");
        $legal_submitTime=strtotime($start->format('Y-m-d H:i:s'));
        $legal_submitTime=$legal_submitTime+40; // 40 seconds for submitting

        $submit_time=strtotime($submit_time);

        return $submit_time>$legal_submitTime;
    }
    public function isFullyMarked()
    {
        return $this->status=="marked";
    }
    public function getMarkableNum($quiz)
    {
        $reg=yii::$app->user->identity->username;
        $submit=$this->find()->where(['quizID'=>$quiz,'reg_no'=>$reg])->one();
        if($submit->isFullyMarked()){ return null;}
        return (new QuizManager(null,$submit->quiz->instructorID,[]))->getmarkables_num($quiz,$submit->SQ_ID);
    }
    public function scorePerc()
    {
        $score=$this->score;
        $max=$this->quiz->total_marks;
        if($max==0){return 0;}
        $perc=($score*100)/$max;

        return $perc;
    }
    public function getQuizScorePerc($quiz)
    {
        $student=yii::$app->user->identity->username;
        $submit=$this->find()->where(['quizID'=>$quiz,'reg_no'=>$student])->one();
        if($submit==null)
        {
            throw new UserException("Missing Assessment Found !");
        }
        if(!$submit->isFullyMarked() && $submit->markables!=null)
        {
            throw new UserException("One of Assessments is not fully marked, you need to wait until marking is done !");
        }
        if($submit->score==0){ return 0;}
        $score=$submit->score;
        $max=$submit->quiz->total_marks;
        if($max==0){return 0;}
        $perc=($score*100)/$max;

        return $perc;
    }
}
