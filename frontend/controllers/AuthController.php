<?php

namespace frontend\controllers;

use Yii;
use yii\caching\DbDependency;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\VerifyEmailForm;
use common\models\Academicyear;
use common\models\Session;
use yii\helpers\ArrayHelper;
use frontend\models\StudentRegistrationForm;
use frontend\models\ClassRoomSecurity;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\base\UserException;
use yii\base\Exception;
use yii\helpers\Html;
use common\components\StudentRegnoValidator;
use common\models\User;
use common\models\StudentCourse;

class AuthController extends \yii\web\Controller
{
    
    public $defaultAction = 'login';

        /**
         * {@inheritdoc}
         */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login','requestpasswordreset','reset-password','register','verify_email','success'],
                        'allow' => true,


                    ],
                    [
                        'actions' => ['logout', 'error','resendVerificationEmail'],
                        'allow' => true,
                        'roles' => ['@']

                    ],
                    [
                        'actions' => ['demo-student'],
                        'allow' => true,
                        'roles' => ['INSTRUCTOR','INSTRUCTOR & HOD']

                    ],



                ],
            ],
       



        ];
    }

        /**
         * {@inheritdoc}
         */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/portal/portal/dashboard']);
        }
        $this->layout = 'login';
       
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            try
            {
             return $this->redirect(['/portal/portal/dashboard']);
            }
            catch(UserException $l)
            {
                yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> ".$l->getMessage());
                yii::$app->user->logout();
               
            }
        }

        return $this->render('login', ['model' => $model]);
    }
     /**
      * Logs out the current user.
      *
      * @return mixed
      */
    public function actionLogout()
    {

        $session = Yii::$app->session;
        if ($session->isActive) {
            $session->destroy();
        }
        Yii::$app->user->logout();
        return $this->redirect(['auth/login']);
    }



    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestpasswordreset()
    {
        $this->layout = 'login';
        $model = new PasswordResetRequestForm();
        if (yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->sendEmail()) {
                    Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> Password Reset Link Sent, Check your email for further instructions.');
                    return $this->redirect(yii::$app->request->referrer);
                } else {
                    Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> Sorry, we are unable to send a password reset link to your email.');
                    return $this->redirect(yii::$app->request->referrer);
                }
            }
        }

        return $this->render(
            'requestPasswordResetToken',
            [
            'model' => $model,
            ]
        );
    }

    /**
     * Resets password.
     *
     * @param  string $token
     * @return mixed
     */
    public function actionResetPassword($token)
    {


        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {

            yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> '.$e->getMessage());
            throw new BadRequestHttpException($e->getMessage(),400);
        }
        if (yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())
                && $model->validate()
                && $model->resetPassword()
            ) {
                Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> Password Changed Successfully.');

                return $this->redirect(yii::$app->request->referrer);
            }
        }
        $this->layout = "login";
        return $this->render(
            'resetPassword',
            [
            'model' => $model,
            ]
        );
    }

 
    /**
     * Handles student registration
     *
     * @return mixed renders the registration form if no request sent
     * or redirects to the response view if a request has been sent
     * @author khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function actionRegister()
    {
        
        $model = new StudentRegistrationForm();
        //$this->layout ='register';
        if (yii::$app->request->isPost) {
            try {
                if ($model->load(Yii::$app->request->post())) {
                    if ($model->create() === true) {
                        yii::$app->session->setFlash('success','<i class="fa fa-information-circle"></i> Registration Successful! ');
                        return $this->redirect(yii::$app->request->referrer);
                    }
                    else
                    {
                        yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Registration failed! '.Html::errorSummary($model));
                        return $this->redirect(yii::$app->request->referrer);
                    }
                }
            }
       
            //known errors should be reported to the user
            catch (UserException $ux) {
                Yii::$app->session->setFlash('error', "<i class='fa fa-exclamation-triangle'></i> Registration failed ! " . $ux->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
                 //unknown errors should not be sent to the user
            //but should only be used for debugging
            catch (Exception $e) {
                Yii::$app->session->setFlash('error', "<i class='fa fa-exclamation-triangle'></i> Registration failed, an unknown error occurred, please try again!");
                return $this->redirect(yii::$app->request->referrer);
            }
        }
        
        return $this->render('student_registration', ['model' => $model]);
    }

}
