<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\User;
use kartik\validators\PhoneValidator;

/**
 * Signup form
 */
class RegisterUserForm extends Model
{
    public $full_name;
    public $phone;
    public $college;
    public $username;
    public $password = "123456";
    public $role="SYS_ADMIN";


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['full_name', 'phone', 'college', 'role'], 'required'],
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'email'],
            ['phone', 'unique', 'targetClass' => '\common\models\Admin', 'message' => 'phone number already taken.'],
            ['phone', 'k-phone','countryValue' => 'TZ'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],


        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function create()
    {
        //get authManager instance
     $auth = Yii::$app->authManager;
         if (!$this->validate()) {
             return false;
        }
        
        $user = new User();
        $admin = new Admin();
        $transaction = Yii::$app->db->beginTransaction();
        try{
        $user->username = $this->username;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        if(!$user->save())
        {
            throw new \Exception("Could not save user details");
        }
        //Now insert data to admin table
        $admin->full_name = $this->full_name;
        $admin->email = $this->username;
        $admin->phone = $this->phone;
        $admin->collegeID = $this->college;
        $admin->userID = $user->getId();
        if(!$admin->save())
        {
            throw new \Exception("Could not save admin details");  
        }
        //now assign role to this newlly created user========>>
        $userRole = $auth->getRole($this->role);
        $auth->assign($userRole, $user->getId());
        $transaction->commit();
        return true;
    
}catch(\Exception $e){
    $transaction->rollBack();
    throw $e;
    return false;
}
        

    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    // protected function sendEmail($user)
    // {
    //     return Yii::$app
    //         ->mailer
    //         ->compose(
    //             ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
    //             ['user' => $user]
    //         )
    //         ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
    //         ->setTo($this->email)
    //         ->setSubject('Account registration at ' . Yii::$app->name)
    //         ->send();
    // }
}
