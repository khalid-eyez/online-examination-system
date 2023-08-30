<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Student;
use yii\base\Exception;
use kartik\validators\PhoneValidator;
use kartik\password\StrengthValidator;
use kartik\validators\EmailValidator;
use common\components\RegnoValidator;
use yii\base\UserException;
use yii\helpers\Html;

/**
 * Signup form for student
 */
class StudentRegistrationForm extends Model
{
    /**
     *
     * @var string the first name of the student
     */
    public $fname;
    /**
     * @var string|null the middle name of the student
     */
    public $mname = null;
    /**
     * @var string the last name of the student
     */
    public $lname;
    /**
     * @var string the email address of the student
     */
    public $email;

    public $phone;
    /**
     * @var string the gender of the student
     */
    public $gender;
    /**
     * @var string the registration number of the student used as the username
     * for his/her account
     */
    public $username;
    /**
     * @var string the password chosen by the student
     */
    public $password;
    /**
     * @var string the confirmation password, should be identical to the previous one
     */
    public $password2;
    /**
     * @var string the role to be assigned to the student, defaults to STUDENT
     */
    public $role = 'STUDENT';
    public function rules()
    {
        return [
            [['role', 'gender','password','phone'], 'required'],
            ['password2','required','message' => 're-type password'],
            ['fname','required','message' => 'first name cannot be blank'],
            ['lname','required','message' => 'last name cannot be blank'],
            [['fname', 'mname', 'lname'], 'string', 'max' => 60],
            [['username','email'], 'trim'],
            ['username', 'required'],
            ['username','k-email','message' => 'Invalid Email Address'],
            ['email','required'],
            [
                'password2', 'compare', 'compareAttribute' => 'password',
                'message' => "Passwords don't match",
            ],
            ['email','k-email','message' => 'Invalid Email Address'],
            [['password'], StrengthValidator::className(), 'preset' => 'normal', 'userAttribute' => 'username'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'User already exists.'],
            ['email', 'unique', 'targetClass' => '\common\models\Student', 'message' => 'This email is already taken.'],
            ['phone', 'unique', 'targetClass' => '\common\models\Student', 'message' => 'Phone number is already taken.'],
            ['phone', 'k-phone','countryValue' => 'TZ']

        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     * @throws UserException in case of data validation failure or some data saving failure
     * @throws Exception in case of any unexpected exception
     * @author Khalid hassan <thewinner016@gmail.com>
     * @since  2.0.0
     */
    public function create()
    {
        
        try {
            $transaction = Yii::$app->db->beginTransaction();
            $auth = Yii::$app->authManager;//get authManager instance
            
            if (!$this->validate()) {
                return false;
            }

            $user = new User();
            $user->status = 10;
            $student = new Student();
            $user->username = $this->username;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->generatePasswordResetToken();
            $user->generateEmailVerificationToken();
            if ($user->save()) {
                //Now insert data to student table
                $student->fname = $this->fname;
                $student->mname = $this->mname;
                $student->lname = $this->lname;
                $student->reg_no = $this->username;
                $student->email = $this->email;
                $student->phone = $this->phone;
                $student->gender = $this->gender;
                $student->DOR = date('Y-m-d H:i:s');
                $student->userID = $user->getId();
                if ($student->save()) {
                    //now assign role to this newly created user========>>

                    $userRole = $auth->getRole($this->role);
                    $auth->assign($userRole, $user->getId());
                    $transaction->commit();
                    return true;
                    //everything is ok
                } else {
                    throw new UserException("Could not save student details".Html::errorSummary($student));
                }
            } else {
                throw new UserException("Could not create user account");
            }
        } catch (UserException $ex) {
            $transaction->rollBack();
            throw new UserException($ex->getMessage());
        }catch (\Exception $e) {
            $transaction->rollBack();
            throw new Exception($e->getMessage());
        } 
    }
}
