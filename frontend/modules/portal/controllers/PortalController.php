<?php
namespace frontend\modules\portal\controllers;
use yii\web\Controller;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\ChangePasswordForm;
use yii\helpers\Url;



/*
@author khalid hassan 
*/
class PortalController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['dashboard'],
                'rules' => [

                    // all authenticated
                    [
                        'actions' => ['dashboard','changePassword','password-change-cancel','change-password-restrict','add_email','change-regno'],
                        'allow' => true,
                        'roles' => ['@']
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }
public function actionDashboard()
{
  if(Yii::$app->user->can('INSTRUCTOR & HOD') || Yii::$app->user->can('INSTRUCTOR')) {
        if (yii::$app->user->identity->hasDefaultPassword()) {
            return $this->redirect(Url::to(['change-password-restrict']));
        }
        return $this->redirect(Url::to(['/portal/instructor/dashboard']));
    }  else if (Yii::$app->user->can('STUDENT')) {
        if (yii::$app->user->identity->hasDefaultPassword()) {
            return $this->redirect(Url::to(['change-password-restrict']));
        }
        return $this->redirect(Url::to(['/portal/student/dashboard']));
    }
    else   if(Yii::$app->user->can('SUPER_ADMIN')) {
        if (yii::$app->user->identity->hasDefaultPassword()) {
            return $this->redirect(Url::to(['change-password-restrict']));
        }
        return $this->redirect(Url::to(['/admin/super-admin/dashboard']));
    }
    else if(Yii::$app->user->can('SYS_ADMIN'))
    {
        if (yii::$app->user->identity->hasDefaultPassword()) {
            return $this->redirect(Url::to(['/change-password-restrict']));
        }
        return $this->redirect(Url::to(['/admin/admin/dashboard'])); 
    }
    else
    {
       
        return $this->redirect(Url::to(['/auth/logout']));
    }
}

public function actionChangepassword()
    {


        $models = new ChangePasswordForm();

        // VarDumper::dump($models->changePassword());

        try {
            if ($models->load(Yii::$app->request->post())) {
               // VarDumper::dump($models->changePassword());
                if ($models->changePassword()) {
                    Yii::$app->user->logout();
                    $destroySession = true;
                    Yii::$app->session->setFlash('success', 'Password changed successfully, Now login with the new password!');
                    return $this->redirect(['auth']);
                } else {
                    Yii::$app->session->setFlash('error', 'The current password is wrong');
                    return $this->redirect(yii::$app->request->referrer);
                }
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Something went wrong! try again later');
            return $this->redirect(yii::$app->request->referrer);
        }

        return $this->render('changePassword', ['model' => $models]);
    }

    public function actionChangePasswordRestrict()
    {
        $models = new ChangePasswordForm();

        // VarDumper::dump($models->changePassword());
        $this->layout = '/restrictPasswordChange';
        try {
            if ($models->load(Yii::$app->request->post())) {
               // VarDumper::dump($models->changePassword());
                if ($models->changePassword()) {
                      Yii::$app->user->logout();
                      $destroySession = true;
                      Yii::$app->session->setFlash('success', 'Password changed successfully, Now login with the new password!');
                    return $this->redirect(['auth']);
                } else {
                    Yii::$app->session->setFlash('error', 'The current password is wrong');
                    return $this->redirect(yii::$app->request->referrer);
                }
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Something went wrong! try again later');
            return $this->redirect(yii::$app->request->referrer);
        }

        return $this->render('changePasswordrestrict', ['model' => $models]);
    }






}










?>