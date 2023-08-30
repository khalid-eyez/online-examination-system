<?php
namespace frontend\modules\assessments\models;
use Yii;
use yii\base\Model;
use common\models\Assignment;
use common\models\StudentExtAssess;
use common\models\ExtAssess;
use common\models\Student;
use common\models\ProgramCourse;
use common\models\StudentCourse;
class AddAssessRecord extends Model{

    public $regno;
    public $score;
    public $assessid;
    public function rules(){
        return [
           ['score','double'],
           ['score','required'],
           ['regno','string'],
           ['regno','required']
        ];

    }
    public function add_new_record(){

      if(!$this->validate()){
         return false;
     }
     $error_rec=[];
     $assmark=ExtAssess::findOne($this->assessid)->total_marks;
     
     if($this->score>$assmark){$error_rec[$this->regno]="score greater than the maximum"; return $error_rec;}

     $assessmodel=new StudentExtAssess();
     $student=Student::findOne($this->regno);
     if($student==null)
     {
      $error_rec[$this->regno]="is Invalid reg. no, might have not registered";
      return $error_rec;
     }
     $studentProgram=$student->programCode;
     $programcourse=ProgramCourse::findOne(['programCode'=>$studentProgram,'course_code'=>yii::$app->session->get('ccode')]); //for regular courses;
     $studentcourse=StudentCourse::find()->where(['reg_no'=>$this->regno,'course_code'=>yii::$app->session->get('ccode')])->one(); //may be a carry over course
     
     if($programcourse==null && $studentcourse==null)
     {
      $error_rec[$this->regno]="Does not take this course";
      return $error_rec;
     }
     $assessmodel->reg_no=$this->regno;
     $assessmodel->score=$this->score;
     $assessmodel->assessID=$this->assessid;

     if($assessmodel->save()){
       return $error_rec;
      }
     else{ 
       $error_rec[$this->regno]=$assessmodel->getErrors()['reg_no'][0];
       return $error_rec;
      }

   }

   public function editrecord($recid)
   {
    if(!$this->validate()){
      return false;
  }
  $error_rec=[];
  $assmark=ExtAssess::findOne($this->assessid)->total_marks;
  
  if($this->score>$assmark){$error_rec[$this->regno]="score greater than the maximum"; return $error_rec;}

  $assessmodel=StudentExtAssess::findOne($recid);
  $assessmodel->reg_no=$this->regno;
  $assessmodel->score=$this->score;
  $assessmodel->assessID=$this->assessid;

  if($assessmodel->save()){
    return $error_rec;
   }
  else{ 
    $error_rec[$this->regno]=$assessmodel->getErrors()['reg_no'][0];
    return $error_rec;
   }




   }


}
        
?>