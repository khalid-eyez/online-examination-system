<?php
namespace frontend\modules\assessments\models;
use yii\base\Model;
use frontend\models\ClassRoomSecurity;
use yii;
use yii\base\UserException;
use Mpdf\Mpdf;
use common\models\Quiz;
use common\models\Course;
use common\models\User;
use common\models\StudentQuiz;
class Invigilator extends Model
{


public $invigilationstore="storage/invigilationFiles/";

public function recordVFP($vfp,$assessmentID)
{
  $assessmentID=($assessmentID!=null)?$assessmentID:"/unknown";
  $student=yii::$app->user->identity->id;
  $folder=$this->invigilationstore.$student."/vfps".$assessmentID."/";
  $filepath=$folder."vfp".$student.".vfp";

  if(!is_dir($folder))
  {
    mkdir($folder,0777,true);
  }
  
  return $this->vfpFileSaver($vfp,$filepath);
}
private function vfpFileSaver($vfp,$path)
{
  $vfpsBuffer=(file_exists($path))?file_get_contents($path):null;
  $vfpsBuffer=$this->vfpsBufferToArray($vfpsBuffer);
    
  //making sure the vfps index already exists

  if(!isset($vfpsBuffer["vfps"]))
  {
    $vfpsBuffer["vfps"]=[];
  }
    array_push($vfpsBuffer["vfps"],$vfp->to_array());
    $vfpsBuffer=$this->hideContent(json_encode($vfpsBuffer));
    if(file_put_contents($path,$vfpsBuffer)!=false)
    {
        return true;
    }

    return false; 
}
public function recordSubmission($submissionbuffer)
{
  $assessmentID=$submissionbuffer['quiz'];
  $student=yii::$app->user->identity->id;
  $folder=$this->invigilationstore.$student."/vfps".$assessmentID."/";
  $filepath=$folder."vfp".$student.".vfp";

  if(!is_dir($folder))
  {
    mkdir($folder,0777,true);
  }
 return $this->submissionContentSaver($filepath,$submissionbuffer);
}
private function submissionContentSaver($path,$submittedContent)
{
  $contentBuffer=(file_exists($path))?file_get_contents($path):null;
  $contentBuffer=$this->vfpsBufferToArray($contentBuffer);
  $contentBuffer["submission"]=$submittedContent;
  $hiddenContent=$this->hideContent(json_encode($contentBuffer));
    if(file_put_contents($path,$hiddenContent)!=false)
    {
        return true;
    }

    return false; 
}
   /**
     * unhides or decrypts the data 
     *
     * @param  string $data the string data to be decrypted
     * @return string the decrypted string data
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    private function RevealContent($data)
    {
        return ClassRoomSecurity::decrypt($data);
    }
     /**
     * encrypts data before saving to file
     *
     * @param  string $data the data to be encrypted
     * @return string the encrypted string data
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  3.0.0
     */
    private function hideContent($data)
    {
        return ClassRoomSecurity::encrypt($data);
    }
    /**
     * puts the vfps data in array format
     * @param string $vfpsBuffer a string containing encrypted vfps data
     * @return array an array buffer containing the transformed vfps data
     * @author khalid hassan <thewinner016@gmail.com>
     * @since 3.0.0
     */
    private function vfpsBufferToArray($vfpsBuffer)
    {
        if($vfpsBuffer==false && $vfpsBuffer==null){
            return [
                "vfps"=>[],
                "submission"=>[]
            ];
        }
        $vfpsBuffer=$this->RevealContent($vfpsBuffer);
        $vfpsBuffer=json_decode($vfpsBuffer,true);

        return $vfpsBuffer;
    }
    /**
     * finds student vfps from the vpfs database
     * @param string $student the userID of the student
     * @param int $assessment the assessment ID for which vfps needs to be found
     * @throws UserException when no vfps file found in the database
     * @author khalid hassan <thewinner016@gmail.com>
     * @since 3.0.0
     */
public function getStudentVfps($student,$assessment)
{
  $assessmentID=$assessment;
  $folder=$this->invigilationstore.$student."/vfps".$assessmentID."/";
  $filepath=$folder."vfp".$student.".vfp";
  
  if(!file_exists($filepath))
  {
    throw new UserException("No VFPs Found");
  }
  $vfps=file_get_contents($filepath);
  $vfps=$this->RevealContent($vfps);
  $vfps=json_decode($vfps,true);

  if($vfps==null)
  {
    throw new UserException("No VFPs Found");
  }

  return $vfps;
}

public function getMarkablesubmissions($student,$quiz)
{
  $submissions=$this->getStudentVfps($student,$quiz)['submission'];
  if($submissions==null)
  {
    throw new UserException("No submission found !");
  }
  foreach($submissions as $in=>$submission)
  {
    if($in=="_csrf-frontend" || $in=="quiz")
    {
      unset($submissions[$in]);
      continue;
    }
  }

  return $submissions;
}

public function downloadSubmissionsPdf($content,$student,$assessment)
{
  $this->quizSubmitFileDownloader($content, $assessment,$student);
}

private function quizSubmitFileDownloader($content, $assessment,$student)
{
  $assessment = Quiz::findOne($assessment);
 
    if ($content != null) {
        $mpdf = new Mpdf(['orientation' => 'P']);
        $mpdf->setFooter('{PAGENO}');
        $stylesheet = file_get_contents('css/capdf.css');
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetWatermarkText(yii::$app->params['appUrl'], 0.09);
        $mpdf->showWatermarkText = true;
        $regno=User::findOne($student)->username;
        $submitted=StudentQuiz::find()->where(['quizID'=>$assessment,'reg_no'=>$regno])->one();
        $score=$submitted->score;
        $submissionstatus=$submitted->status;
        $status=($submissionstatus=="marked" || ($submissionstatus=="submitted" && $submitted->markables==null))?"Fully Marked":"Partially Marked";
        $mpdf->WriteHTML('<div align="center"><img src="img/logo.png" width="110px" height="110px"/></div>', 2);
        $mpdf->WriteHTML('<p align="center"><font size=5>' .  $regno . '</font></p>', 3);
        $mpdf->WriteHTML('<p align="center"><font size=5>' . $assessment->quiz_title . '</font></p>', 3);
        $mpdf->WriteHTML("<table align='right' border='1' style='border-color:green' cellspacing=0 cellpadding=10  class='text-success'><tr style='border-color:green'><td style='border-color:green'><b>TOT SCORE</b></td><td style='border-color:green'><b>".$score."</b></td></tr></table>");
        $mpdf->WriteHTML("<p class='text-info' style='margin-top:2px;font-size:10px;color:blue;text-align:right'>".$status."</p>");
        $mpdf->WriteHTML('<hr width="80%" align="center" color="#000000">', 2);
        $mpdf->WriteHTML("$content", 3);
        $studentreg=str_replace("/","-", $regno);
        $studentreg=str_replace(" ","",$studentreg);
        $studentreg=str_replace("\\","-",$studentreg);
        $filename = $studentreg. ".pdf";
        $mpdf->Output($filename, "D");
        return null;
    } else {
        throw new UserException('No content',8);
    }
}





}











?>