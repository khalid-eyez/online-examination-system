<?php

namespace frontend\modules\portal\controllers;
use Yii;


class InstructorController extends \yii\web\Controller
{
    //public $layout = 'instructor';
       /**
     * {@inheritdoc}
     */
//################################# public $layout = 'admin'; #####################################

    public $defaultAction = 'dashboard';

    public function actionDashboard()
    {
        return $this->redirect("/assessments/online-assessments/class-quizes");
    }
}
