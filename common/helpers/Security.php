<?php
namespace common\helpers;
use Yii;
class Security {
  
    public  static function encrypt($data){
     $key = 'juuIASSsLCTjQIevzZdXpV2sNrl5OFwq';
        return urldecode(Yii::$app->security->encryptByKey($data, $key));
    }
    public static function decrypt($data){
        $key = 'juuIASSsLCTjQIevzZdXpV2sNrl5OFwq';
        return urldecode(Yii::$app->security->decryptByKey($data, $key));

    }
  
}
?>