<?php 
namespace common\widgets;
use yii\base\Widget;
class Course extends Widget{
    public $courseTitle;
    public function init(){
        Parent::init();
    }
    public function run(){
        Parent::run();
        return $this->courseTitle;
    }
}
?>