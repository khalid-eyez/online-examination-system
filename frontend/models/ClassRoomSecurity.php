<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class ClassRoomSecurity extends Model
{
    public static function encrypt($value)
    {
        $secretKey = Yii::$app->params['app.dataEncryptionKey'];
        $value = Yii::$app->getSecurity()->encryptByPassword($value, $secretKey);
        return $value;
    }
    public static function decrypt($value)
    {
        $secretKey = Yii::$app->params['app.dataEncryptionKey'];
        $value = Yii::$app->getSecurity()->decryptByPassword($value, $secretKey);
        return $value;
    }
}
