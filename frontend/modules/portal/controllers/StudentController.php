<?php

namespace frontend\modules\portal\controllers;
use Yii;

class StudentController extends \yii\web\Controller
{
    public $generation_type;
    //public $layout = 'student';
    public $defaultAction = 'dashboard';

    public function actionDashboard()
    {
        return $this->redirect("/assessments/online-assessments/student-quizes");
    }

  
}
