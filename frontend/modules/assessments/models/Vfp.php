<?php
namespace frontend\modules\assessments\models;
use yii\base\Model;
use yii;

class Vfp extends Model
{
    /**
     * @var string $time the time on which the action was attempted
     */
   public $time;
   /**
    * @var string $action the action that was attempted
    */
   public $action;
   /**
    * @var string $status the status of the action wheither successful or failed
    */
   public $status;
   /**
    * @var string $errormessage the error message that was shown to the user
    */
   public $errormessage;
   /**
    * @var string $source the source or cause of the error if it is the system itself or the user
    */
   public $source;
   /**
    * @var string $description the description of the error telling about how it happens and the possible causes
    */
   public $description;
   /**
    * @var array an array buffer containing all possible errors descriptions and their codes
    */
    public $errors=[
        "0"=>"The system could not complete the process due to an unrecognized error",
        "1"=>"User tries to attempt the assessment before the due time",
        "2"=>"User tries to attempt an expired assessment",
        "3"=>"User tries to attempt an assessment without a required access token",
        "4"=>"User tries to submit an assessment after the first submission has been successful",
        "5"=>"User tries to submit an assessment after the allocated time is over",
        "6"=>"The system could not find the required assessment, probably deleted or updated by the owning instructor at the time the user was already trying to access it",
        "7"=>"The system could not find the required assessment file, probably deleted by the instructor, corrupt or lost",
        "8"=>"The user submitted an empty assessment response buffer",
        "9"=>"The user tries to attempt a massively-attempted assessment after the maximum delay time (20 min)",
        "10"=>"The user has clicked the submit button or the system has triggered the submission automatically",
        "11"=>"The submission was successful and the user has got a success message message including the score",
        "12"=>"The submission failed and the user has got a failure message",
        "13"=>"The time for submission is over and the user has got an automatic submission warning",
        "14"=>"The user has delayed the submission of the assessment for 10 seconds and the system has triggered an automatic submission",
        "15"=>"The user has gone out of focus either by opening another tab, window, or another application and has got an 'automatic submission' warning",
        "16"=>"The user has gone out of the focus for the second time and the system triggered an automatic submission",
        "17"=>"The user has tried to close the browser window, reload the browser content"
    ];

   public function __construct($action=null,$errormessage=null,
   $source=null,$errorcode=null,$config=[])
   {
     $this->action=$action;
     $this->errormessage=$errormessage;
     $this->time=date("d-m-Y H:i:s");
     $errorcode=($errorcode!=null)?strval($errorcode):null;
     $this->source=$source;
     $errordesc=(isset($this->errors[$errorcode]))?$this->errors[$errorcode]:null;
     $this->description=$errordesc;
     $this->status=($errormessage!=null)?"Failed":"Successful";
    parent::__construct($config);
   }
   /**
    * Builds and return the verbose footprints as an array buffer
    * @return array an array buffer containing all vfp information
    * @author khalid hassan <thewinner016@gmail.com>
    * @since 3.0.0
    */
   public function to_array()
   {
    return [
        "Time"=>$this->time,
        "Action"=>$this->action,
        "Status"=>$this->status,
        "Error Message"=>$this->errormessage,
        "Source"=>$this->source,
        "Description"=>$this->description

    ];
   }


}














?>