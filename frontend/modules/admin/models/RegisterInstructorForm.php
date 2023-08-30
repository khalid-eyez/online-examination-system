<?php

namespace frontend\modules\admin\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Instructor;

/**
 * Signup form
 */
class RegisterInstructorForm extends Model
{
    public $full_name;
    public $phone;
    public $gender;
    public $username;
    public $password = "123456";
    public $role = "INSTRUCTOR";
    public $email;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['full_name', 'phone', 'role', 'gender'], 'required'],
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'email','message' => 'Invalid Email Address.'],
            ['phone', 'unique', 'targetClass' => '\common\models\Instructor', 'message' => 'phone number already taken.'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This Email has already been taken.'],
            ['phone', 'k-phone','countryValue' => 'TZ'],
            ['username', 'string', 'min' => 2, 'max' => 255],


        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function createi()
    {
        //get authManager instance
        $auth = Yii::$app->authManager;
        if (!$this->validate()) {
            return false;
        }

        $user = new User();
        $instructor = new Instructor();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user->username = $this->username;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->generatePasswordResetToken();
            $user->generateEmailVerificationToken();
            if ($user->save()) {
                //Now insert data to hod table
                $instructor->full_name = $this->full_name;
                $instructor->email = $this->username;
                $instructor->phone = $this->phone;
                $instructor->gender = $this->gender;
                $instructor->userID = $user->getId();
                if ($instructor->save()) {
                    //now assign role to this newlly created user========>>
                        $userRole = $auth->getRole($this->role);
                    $auth->assign($userRole, $user->getId());
                    $transaction->commit();
                    return true;
                } else {
                    return false;
                }
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
        return false;
    }
}
