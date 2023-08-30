<?php

namespace common\components;
use yii\validators\Validator;

class ExpireDateValidator extends Validator
{


public function init()
{
    parent::init();
    $this->message="Expire date  must be greater than the current time";  
}

public function validateAttribute($model, $attribute)
{
    $now=strtotime(date("Y-m-d"));
    $expdate=strtotime($model->$attribute);

    if($now>$expdate)
    {
        $this->addError($attribute,$this->message);
    }

}
public function clientValidateAttribute($model, $attribute, $view)
{

    $message = json_encode($this->message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    return <<<JS
    var UserDate=$.trim($("#quizaccesstokens-expires_on").val());
    var ToDate = new Date();

    if (new Date(UserDate).getDate() < ToDate.getDate()) {
        messages.push($message);
      
     }
JS;
}




}










?>