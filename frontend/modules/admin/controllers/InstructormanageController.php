<?php

namespace frontend\modules\admin\controllers;

use Yii;
use common\models\User;
use common\models\Instructor;
use common\models\InsructorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use frontend\modules\admin\models\RegisterInstructorForm;
use frontend\modules\admin\models\RegisterHodsForm;
use frontend\models\UploadStudentForm;
use common\models\Student;
use common\models\College;
use common\models\Department;
use common\models\Hod;
use common\models\Program;
use common\models\Course;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\models\AuthItem;
use yii\helpers\URL;
use common\models\Logs;
use yii\data\ActiveDataProvider;
use frontend\models\ClassRoomSecurity;

//use yii\web\Controller;
//use yii\filters\VerbFilter;
/**
 * InstructormanageController implements the CRUD actions for Instructor model.
 */
class InstructormanageController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],

            'access' => [
                'class' => AccessControl::className(),
                'rules' => [

                    [
                        'actions' => [
                            'instructor-list',
                            'hod-list',
                            'create-instructor',
                            'create-hods',
                            'view',
                            'update',
                            'reset',
                            'delete',
                            'create',
                            'lock',
                            'unlock'
                        ],
                        'allow' => true,
                        'roles' => ['SYS_ADMIN','SUPER_ADMIN'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Instructor models.
     * @return mixed
     */

     // password resseting
    public function actionReset($id)
    {
        $id = base64_decode(urldecode($id));
        $model = User::findOne($id);
        $password = 123456;

           $model->password = $password;

        if ($model->save()) {
            Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> User Password Reset successful, password defaults to 123456');
        } else {
            Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> User Password Reset failed ! ' . Html::errorSummary($model));
        }

           return $this->redirect("instructor-list");
    }

    public function actionLock($id)
    {
        $id = base64_decode(urldecode($id));
        $model = User::findOne($id);


        if ($model->lock()) {
            Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> User Lock successful');
        } else {
            Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> User Lock failed ! ' . Html::errorSummary($model));
        }

            return $this->redirect("instructor-list");
    }
    public function actionUnlock($id)
    {
        $id = base64_decode(urldecode($id));
        $model = User::findOne($id);


        if ($model->unlock()) {
            Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> User Reactivation successful');
        } else {
            Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> User Reactivation failed ! ' . Html::errorSummary($model));
        }

            return $this->redirect("instructor-list");
    }

    //Create instructor
    public function actionCreateInstructor()
    {
        $model = new RegisterInstructorForm();

        try {
            if ($model->load(Yii::$app->request->post())) {
                if ($model->createi()) {
                    Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> Instructor registered successfully');
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> Instructor registration failed ! ' . Html::errorSummary($model));
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Instructor registration failed !' . $e->getMessage());
            return $this->redirect(Yii::$app->request->referrer);
        }
    }


       //Create Hods
    public function actionCreateHods()
    {

        if (yii::$app->request->isPost) {
            $model = new RegisterHodsForm();
            try {
                if ($model->load(Yii::$app->request->post())) {
                    if ($model->create()) {
                        Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> HOD registered successfully !');
                        return $this->redirect(Yii::$app->request->referrer);
                    } else {
                        Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> HOD registration failed ! ' . Html::errorSummary($model));
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                }
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'HOD registration failed ! ' . $e->getMessage());
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
    }



    //get instructor list
    public function actionInstructorList()
    {
        $instructors = [];
        if (yii::$app->user->can('SUPER_ADMIN')) {
            $instructors = Instructor::find()->all();
        } else {
            $adminCollege = Yii::$app->user->identity->admin->college;
            $instructors = $adminCollege->getInstructors();
        }
        return $this->render('instructor_list', ['instructors' => $instructors]);
    }

    //get Hod list
    public function actionHodList()
    {
        $hods = Hod::find()->all();
        return $this->render('hod_list', ['hods' => $hods]);
    }
    /**
     * Displays a single Instructor model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Instructor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Instructor();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->instructorID]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Instructor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = base64_decode(urldecode($id));
        $model = $this->findModel($id);
        $model2 = $model->user->role;

        if ($model->load(Yii::$app->request->post()) && $model2->load(Yii::$app->request->post())) {
            if ($model-> updateit($model2)) {
                Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> User Updated Successfully !');
            } else {
                Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> User Updating Failed ! ' . Html::errorSummary($model));
            }

            return $this->redirect(yii::$app->request->referrer);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Instructor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        $id = yii::$app->request->post('userid');
        $instructor = (new Instructor())->find()->where(['userID' => $id])->one();
        if ($instructor->delete()) {
            return $this->asJson(['deleted' => 'User Deleted Successfully !']);
        } else {
            return $this->asJson(['failure' => 'User Deleting Failed ! ' . Html::errorSummary($instructor)]);
        }
    }

    /**
     * Finds the Instructor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Instructor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Instructor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
