<?php

namespace frontend\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\base\Behavior;

/*
A model class for behaviours to be attached to appropriate active records
created by khalid hassan, 05/1/2022, 0755189736, thewinner016@gmail.com
*/

class ClassRoomBehaviours extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }


    public function beforeDelete($event)
    {
        $owner = $this->owner->className();
        switch ($owner) {
            case "common\models\Material":
                                                                                                                                                                                                                                                                                                                                                                                                                                                           $file = $this->owner->fileName;
                if (file_exists("storage/temp/" . $file)) {
                    unlink("storage/temp/" . $file);
                }

                break;
            case "common\models\Submit":
                                                                                                                                                                                                                                                                                                                                                                                                                                                       $file = $this->owner->fileName;
                if (file_exists("storage/submit/" . $file)) {
                    unlink("storage/submit/" . $file);
                }

                break;
            case "common\models\GroupAssignmentSubmit":
                                                                                                                                                                                                                                                                                                                                                                                                                                                       $file = $this->owner->fileName;
                if (file_exists("storage/submit/" . $file)) {
                    unlink("storage/submit/" . $file);
                }

                break;
            case "common\models\Module":
                                                                                                                                                                                                                                                                                                                                                                                                                                                       $materials = $this->owner->materials;
                foreach ($materials as $material) {
                    $file = $material->fileName;
                    if (file_exists("storage/temp/" . $file)) {
                        unlink("storage/temp/" . $file);
                    }
                }

                break;
            case "common\models\Assignment":
                                                                                                                                                                                                                                                                                                                                                                                                                                                       $file = $this->owner->fileName;
                $submits = null;
                $assType = $this->owner->assType;
                //submits or group submits?
                if ($assType == "allstudents" || $assType == "students") {
                    $submits = $this->owner->submits;
                } else {
                    $submits = $this->owner->groupAssignmentSubmits;
                }
                if (file_exists("storage/temp/" . $file)) {
                    unlink("storage/temp/" . $file);
                }

                //deleting submits
                if (!empty($submits) || $submits != null) {
                    foreach ($submits as $submit) {
                        $submitfile = $submit->fileName;
                        if (file_exists("storage/submit/" . $submitfile)) {
                                unlink("storage/submit/" . $submitfile);
                        }
                    }
                }



                break;
            default:
                                                                                                                                                                                                                                                                                                                                                                                                                                                       $file = $this->owner->fileName;
                if (file_exists("storage/temp/" . $file)) {
                    unlink("storage/temp/" . $file);
                }


                break;
        }
    }
}
