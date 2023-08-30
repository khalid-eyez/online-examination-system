<?php

namespace console\controllers;
use Yii;
use yii\console\Controller;
class RbacController extends Controller
{

        public function actionInit(){
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        //create SuperAdmin role
        $roles = ['SUPER_ADMIN', 'SYS_ADMIN','INSTRUCTOR', 'INSTRUCTOR & HOD', 'STUDENT', 'HOD'];
        foreach($roles as $role){
        $user_role = $auth->createRole($role);
        $auth->add($user_role);
        }
       
        
        }
    // public function actionIndex()
    // {
    //     return $this->render('index');
    // }

}
