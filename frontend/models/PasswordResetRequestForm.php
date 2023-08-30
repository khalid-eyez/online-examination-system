<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    /**
     * @var string the username for the user, for instructors and admins
     * should be their email address, for students should be their
     * registration number
     */
    public $username;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'No account associated with this username !'
            ],

        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne(
            [
            'status' => User::STATUS_ACTIVE,
            'username' => $this->username,
            ]
        );
        if (!$user) {
            return false;
        }


            $user->generatePasswordResetToken();
        if (!$user->save()) {
            return false;
        }

        //sending the email
        return Yii::$app->ClassRoomMailer->sendMail("PASSWORD_RESET", $user);
    }
}
