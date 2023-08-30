<?php

namespace frontend\modules\admin\controllers;

use Yii;
use common\models\Student;
use common\models\User;
use common\models\StudentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\UploadStudentForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use frontend\models\RegisterInstructorForm;
use frontend\models\RegisterHodForm;
use common\models\Instructor;
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
use yii\data\Pagination;

/**
 * StudentmanageController implements the CRUD actions for Student model.
 */
class StudentmanageController extends Controller
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
                            'create-student',
                            'student-list',
                            'view',
                            'update',
                            'delete',
                            'reset',
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
     * Lists all Student models.
     * @return mixed
     */

    /**
     * Displays a single Student model.
     * @param string $id
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
     * Creates a new Student model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Student();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->reg_no]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Student model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        
        $id = base64_decode(urldecode($id));
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            try{
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> User Updated Successfully');
                return $this->redirect(yii::$app->request->referrer);
            } else {
                Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> User Update failed ! ' . Html::errorSummary($model));
                return $this->redirect(yii::$app->request->referrer);
            }
        }
        catch(\Exception $d)
        {
            Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> User Update failed ! ' . $d->getMessage());
            return $this->redirect(yii::$app->request->referrer); 
        }
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }


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

            return $this->redirect(yii::$app->request->referrer);
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

            return $this->redirect(yii::$app->request->referrer);
    }
    public function actionUnlock($id)
    {
        $id = base64_decode(urldecode($id));
        $model = new User();
        $model = User::findOne($id);


        if ($model->unlock()) {
            Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> User Reactivation successful');
        } else {
            Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> User Reactivation failed ! ' . Html::errorSummary($model));
        }

            return $this->redirect(yii::$app->request->referrer);
    }

    /**
     * Deletes an existing Student model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        $id = yii::$app->request->post('userid');
        $student = Student::findOne($id);
        if($student==null)
        {
            return $this->asJson(['failure' => 'User Deleting Failed!']); 
        }
        if ($student->delete()) {
            return $this->asJson(['deleted' => 'User Deleted Successfully !']);
        } else {
            return $this->asJson(['failure' => 'User Deleting Failed ! ' . Html::errorSummary($student)]);
        }
    }



    //get list of students

    public function actionStudentList()
    {
        
        $students = [];

        if (yii::$app->user->can('SUPER_ADMIN')) {
            $students = Student::find()->orderBy(['userID'=>SORT_DESC])->all();

             //= Student::find()->all();
             return $this->render('student_list', ['students' => $students]);
        } else {
            
            $adminCollege = Yii::$app->user->identity->admin->college;
            $query = Student::find()->joinWith('program',true)->joinWith('department',true)->joinWith('college',true)->where(['collegeID'=>$adminCollege]);
            //$query = Student::findBySql("select fname,prog_name from student,program,department where department.collegeID=$adminCollege->collegeID");
            $count = $query->count();
            $pagination = new Pagination(['totalCount' => $count]);
            $students = $query->offset($pagination->offset)
    ->limit($pagination->limit)
    ->all();
            return $this->render('student_list', ['students' => $students,'pagination'=>$pagination]);
        }

      
    }
    /**
     * Finds the Student model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Student the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Student::find()->where(['userID' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
