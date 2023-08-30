<?php

namespace common\models;

use Yii;
use frontend\userlogs\behaviors\AuditEntryBehaviors;
use frontend\models\ClassRoomBehaviours;
/**
 * This is the model class for table "module".
 *
 * @property int $moduleID
 * @property string $moduleName
 * @property int|null $instructorID
 */
class Module extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    
    public function behaviors()
    {
        return [
             'classroombehaviours' => [
                'class' => ClassRoomBehaviours::class
             ]
        ];
    }
    public static function tableName()
    {
        return 'module';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['moduleName'], 'required'],
            [['moduleName'], 'string', 'max' => 200],
            [['instructorID'], 'integer'],
            [['instructorID'],'default','value'=>yii::$app->user->identity->instructor->instructorID],

           
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'moduleID' => 'Module ID',
            'moduleName' => 'Module Name',
        ];
    }

}
