<?php

namespace frontend\modules\admin\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\RegisterUserForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\models\AuthItem;
use common\models\College;
use yii\data\ActiveDataProvider;
use common\models\Admin;
use common\models\User;
use Yii;

class UsersController extends \yii\web\Controller
{
    //public $layout = 'admin';
    public function behaviors()
    {
        return [
           'access' => [
               'class' => AccessControl::className(),
               'rules' => [
                   [
                       'actions' => [
                           'create',
                           'admin-list',
                           'update',
                           'lock',
                           'unlock',
                           'reset',
                           'delete'

                       ],
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
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionDashboard()
    {
        
    }
    public function actionCreate()
    {
        try {
            $model = new RegisterUserForm();
            if ($model->load(Yii::$app->request->post()) && $model->create()) {
                Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> Admin Registered Successfully');
                return $this->redirect(yii::$app->request->referrer);
            } else {
                Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> Admin Registration Failed!' . Html::errorSummary($model));
                return $this->redirect(yii::$app->request->referrer);
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Something went wrong!' . $e->getMessage());
            return $this->redirect(yii::$app->request->referrer);
        }
    }
    public function actionAdminList()
    {
        $users = Admin::find()->all();
        // $dataProvider = new ActiveDataProvider([
        //     'query'=>$query,
        //     'pagination'=>['pageSize'=>2],
        // ]);
        return $this->render('admin-list', ['users' => $users]);
    }
    public function actionReset($id)
    {
        $id = base64_decode(urldecode($id));
        $model = User::findOne($id);
        $password = 123456;

            $model->password = $password;

        if ($model->save()) {
            Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> Admin Password Reset successful, password defaults to 123456');
        } else {
            Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> Admin Password Reset failed ! ' . Html::errorSummary($model));
        }

            return $this->redirect("admin-list");
    }

    public function actionLock($id)
    {
        $id = base64_decode(urldecode($id));
        $model = User::findOne($id);


        if ($model->lock()) {
            Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> Admin Lock successful');
        } else {
            Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> Admin Lock failed ! ' . Html::errorSummary($model));
        }

            return $this->redirect("admin-list");
    }
    public function actionDelete()
    {
        $id = yii::$app->request->post('userid');
        $admin = (new Admin())->find()->where(['userID' => $id])->one();
        if ($admin->delete() && User::findOne($id)->setDeleted()) {
            return $this->asJson(['deleted' => 'Admin Deleted Successfully !']);
        } else {
            return $this->asJson(['failure' => 'Admin Deleting Failed ! ' . Html::errorSummary($model)]);
        }
    }
    public function actionUnlock($id)
    {
        $id = base64_decode(urldecode($id));
        $model = User::findOne($id);


        if ($model->unlock()) {
            Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> Admin Reactivation successful');
        } else {
            Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> Admin Reactivation failed ! ' . Html::errorSummary($model));
        }

            return $this->redirect("admin-list");
    }


    public function actionUpdate($admin)
    {
        $admin = base64_decode(urldecode($admin));
        $model = Admin::findOne($admin);
        if (yii::$app->request->isPost) {
            try {
                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> Admin Updated Successfully');
                    return $this->redirect(yii::$app->request->referrer);
                } else {
                    Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> Admin Updating Failed!' . Html::errorSummary($model));
                    return $this->redirect(yii::$app->request->referrer);
                }
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Something went wrong!' . $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        return $this->render('update', ['model' => $model]);
    }
}
