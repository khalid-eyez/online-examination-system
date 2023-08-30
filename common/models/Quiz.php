<?php

namespace common\models;
use frontend\userlogs\behaviors\AuditEntryBehaviors;
use Yii;
use frontend\modules\assessments\models\QuizManager;
use yii\base\UserException;
use yii\helpers\Html;
/**
 * This is the model class for table "quiz".
 *
 * @property int $quizID
 * @property int $total_marks
 * @property string $attempt_mode
 * @property int $duration
 * @property string|null $quiz_file
 * @property string $viewAnswers
 * @property string|null $date_created
 * @property string $start_time
 * @property string|null $end_time
 * @property int $num_questions
 * @property string $quiz_title
 * @property string $status
 * @property int $instructorID
 * @property Instructor $instructor
 * @property StudentQuiz[] $studentQuizzes
 * @property Quizaccesstokens[] accesstokens
 */
class Quiz extends \yii\db\ActiveRecord
{
    public $startdate;
    public $starttime;
    public $enddate;
    public $endtime;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz';
    }
 
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['duration','viewAnswers','quiz_title', 'start_time', 'status','total_marks','attempt_mode'], 'required'],
            [['total_marks', 'duration','num_questions'], 'integer'],
            [['date_created', 'start_time','enddate','endtime','startdate','starttime'], 'safe'],
            [['attempt_mode'], 'string', 'max' => 15],
            [['quiz_file'], 'string', 'max' => 25],
            [['status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'quizID' => 'Quiz ID',
            'total_marks' => 'Total Marks',
            'duration' => 'Duration',
            'quiz_file' => 'Quiz File',
            'date_created' => 'Date Created',
            'start_time' => 'Start Time',
            'status' => 'Status',
        ];
    }

 

    public function getInstructor()
    {
        return $this->hasOne(Instructor::className(), ['instructorID' => 'instructorID']);
    }

    /**
     * Gets query for [[StudentQuizzes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentQuizzes()
    {
        return $this->hasMany(StudentQuiz::className(), ['quizID' => 'quizID']);
    }

     /**
     * Gets query for [[Quizaccesstokens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAccesstokens()
    {
        return $this->hasMany(Quizaccesstokens::className(), ['quizID' => 'quizID']);
    }

    public function isNew()
    {
        date_default_timezone_set('Africa/Dar_es_Salaam');
        $time=strtotime($this->date_created);
        $lastlogin=yii::$app->user->identity->last_login;
        $lastlogin=strtotime($lastlogin);

        return $lastlogin<$time;
    }
    public function isExpired()
    {
        date_default_timezone_set('Africa/Dar_es_Salaam');
        $now=strtotime(date("Y-m-d H:i:s"));
        if($this->attempt_mode=="individual")
        {
        $end=strtotime($this->end_time);
        return $end<$now;
        }
        else
        {
           

            $start=new \DateTime($this->start_time);
            $start->modify ("+{$this->duration} minutes");
            $end=strtotime($start->format('Y-m-d H:i:s'));
           

            return $end<$now; 
        }

       
    }

    public function isAttemptingTimeOver()
    {
        date_default_timezone_set('Africa/Dar_es_Salaam');
        $start=new \DateTime($this->start_time);
        $start->modify ("+20 minutes");
        $latestart=strtotime($start->format('Y-m-d H:i:s'));
        $now=strtotime(date('Y-m-d H:i:s'));
        return $now>$latestart;
    }
    public function hasStarted()
    {
        date_default_timezone_set('Africa/Dar_es_Salaam');

        $now=strtotime(date("Y-m-d H:i:s"));
        $start=strtotime($this->start_time);

        return $start<$now;
    }

    public function isReadyTaking()
    {
        date_default_timezone_set('Africa/Dar_es_Salaam');

        $now=strtotime(date("Y-m-d H:i:s"));
        $start=strtotime($this->start_time);

        return $start<=$now;
    }
    public function isSubmitted()
    {
        return (new StudentQuiz)->isSubmitted($this->quizID);
    }
    public function getScore()
    {
        if($this->isSubmitted())
        {
            return (new StudentQuiz)->getStudentScore($this->quizID);
        }

        return null;
    }
    public function getStudentSubmittedFile()
    {
        if($this->isSubmitted())
        {
            return (new StudentQuiz)->getStudentSubmittedFile($this->quizID);
        }
        return null;
    }
    public function getMarkableNum()
    {
        if(!$this->isSubmitted()){return null;}
        return (new StudentQuiz)->getMarkableNum($this->quizID);
    }

    public function updateQuiz()
    {
        date_default_timezone_set('Africa/Dar_es_Salaam');
        $this->start_time=$this->startdate.' '.$this->starttime;
        $this->end_time=($this->attempt_mode=="individual")?$this->enddate.' '.$this->endtime:null;
        $this->date_created=date("Y-m-d H:i:s");
        $this->viewAnswers="off";
        if($this->attempt_mode=="individual")
        {
            $this->total_marks=$this->num_questions;
        }
       

        return $this->save();
    }
    public function hasSubmits()
    {
        return $this->studentQuizzes!=null;
    }

    public function hasTokens()
    {
      return $this->accesstokens!=null;
    }
    public function isAccessRestricted()
    {
       return $this->accesstokens!=null; 
    }
    public function isAccessGranted()
    {
       $granted=yii::$app->session->get("quizaccess");
       if($granted==null){return false;}
        return $granted==$this->quizID;
    }

    public function grantAccess($token)
    {
        $trans=yii::$app->db->beginTransaction();

        try
        {
        if($this->isAccessGranted())
        {
            return true;
        }
        $tokensmodel=Quizaccesstokens::find()->where(['quizID'=>$this->quizID,'token'=>$token])->one();

        if($tokensmodel==null)
        {
            throw new UserException("Invalid Token, try again",3);
        }
        if(!$tokensmodel->isValid())
        {
            throw new UserException("Invalid Token, try again",3);
        }
        $tokensmodel->consumed_by=yii::$app->user->identity->username;

        if(!$tokensmodel->save())
        {
            throw new \Exception("could not update access token status!".Html::errorSummary($tokensmodel));  
        }
        $sessionid = $this->quizID;
        $quizsession = yii::$app->session->get($sessionid);
        $student = yii::$app->user->identity->username;
        if ($quizsession == null && (new StudentQuiz())->isRegistered($student, $this->quizID)) {
            throw new \Exception("You are not allowed to do this Assessment more than one time !",4);
        }
        (new QuizManager(null,$this->instructorID,[]))->registerStudent($this->quizID);
        yii::$app->session->set("quizaccess",$this->quizID);
        $trans->commit();
        return true;
        }
        catch(UserException $t)
        {
          $trans->rollBack();
          throw $t;
        }
        catch(\Exception $f)
        {
            $trans->rollBack();
            throw $f;
        }
        

       
    }

}
