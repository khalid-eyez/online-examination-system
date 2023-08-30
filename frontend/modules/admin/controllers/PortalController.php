<?php
namespace frontend\modules\admin\controllers;
use yii\web\Controller;
use frontend\models\ChangeRegNoForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\User;
use frontend\models\ChangePasswordForm;
use frontend\models\AddEmailForm;
use yii\helpers\Url;
use yii\helpers\VarDumper;


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

                    //student permissions
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['STUDENT']
                    ],

                     //instructor permissions
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['INSTRUCTOR']
                    ],
                    //HOD permissions

                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['HOD']
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
    if(Yii::$app->user->can('SUPER_ADMIN')) {
        if (yii::$app->user->identity->hasDefaultPassword()) {
            return $this->redirect(Url::to(['change-password-restrict']));
        }
        return $this->redirect(Url::to(['/super-admin/dashboard']));
    }
    else if(Yii::$app->user->can('SYS_ADMIN'))
    {
        if (yii::$app->user->identity->hasDefaultPassword()) {
            return $this->redirect(Url::to(['/change-password-restrict']));
        }
        return $this->redirect(Url::to(['/admin/dashboard'])); 
    }
    else if(Yii::$app->user->can('STUDENT'))
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