<?php

namespace frontend\modules\admin\controllers;

use yii\filters\AccessControl;
use common\models\Instructor;
use common\models\Student;
use common\models\User;
use yii;

class SuperAdminController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['dashboard'],
                        'allow' => true,
                        'roles' => ['SUPER_ADMIN']

                    ],



                ],
            ],
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         'logout' => ['post'],
            //     ],
            // ],
        ];
    }
    public function actionDashboard()
    {
        $instructors = count(Instructor::find()->all());
        $students = count(Student::find()->all());
        $users = count(User::find()->all());

        return $this->render('index', [
            'instructors' => $instructors,
            'students' => $students,
            'users' => $users,
        ]);
    }
}
