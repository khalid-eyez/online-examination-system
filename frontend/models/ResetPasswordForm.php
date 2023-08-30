<?php

namespace frontend\models;

use yii\base\InvalidArgumentException;
use yii\base\Model;
use common\models\User;
use kartik\password\StrengthValidator;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    /**
     * @var string new user password
     */
    public $password;
    /**
     * @var string new password confirmation
     */
    public $password2;
    /**
     * @var string username used for password strength validation
     */
    public $username;
    /**
     * @var \common\models\User
     */
    private $_user;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password','password2'], 'required'],
            [
                'password2', 'compare', 'compareAttribute' => 'password',
                'message' => "Passwords don't match",
            ],
            [['password'], StrengthValidator::className(), 'preset' => 'normal', 'userAttribute' => 'username'],
        ];
    }
    /**
     * Creates a form model given a token.
     *
     * @param  string $token
     * @param  array  $config name-value pairs that will be used to initialize the object properties
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Invalid password reset token.');
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidArgumentException('Invalid password reset token.');
        }
        $this->username = $this->_user->username;
        parent::__construct($config);
    }



    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        $user->generateAuthKey();
        return $user->save(false);
    }
}
