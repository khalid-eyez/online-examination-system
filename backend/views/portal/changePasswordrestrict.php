<?php
use yii\bootstrap4\Breadcrumbs;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use common\widgets\Alert;


AppAsset::register($this);



/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form ActiveForm */

?>
<div class="changePassword">

    <?php $form = ActiveForm::begin(["action"=>"/portal/portal/change-password-restrict"]); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3">
                </div>
                <div class="col-sm-6">
                    <div class="card shadow-lg" style="font-family:'Times New Roman', sans-serif">
                        <div class="card-header text-center bg-primary pt-1 pb-1 text-md">
                            <i class="fa fa-lock"></i> Change Password
                        </div>
                        <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?= $form->field($model, 'current_password')->passwordInput(['class'=>'form-control form-control-sm', 'placeholder'=>'Current Password'])->label(false) ?>
                                    </div>

                                    <div class="col-sm-12">
                                        <?= $form->field($model, 'new_password')->passwordInput(['class'=>'form-control form-control-sm', 'placeholder'=>'New Password'])->label(false) ?>
                                    </div>

                                    <div class="col-sm-12">
                                        <?= $form->field($model, 'confirm_new_password')->passwordInput(['class'=>'form-control form-control-sm', 'placeholder'=>'Confirm Password'])->label(false) ?>
                                    </div>
                                    
                                    <div class="col-sm-12">
                                        <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary btn-md float-right']) ?>
                                        <a href="<?=Url::to(['/home/password-change-cancel'])?>" class="btn btn-md btn-primary">Cancel</a>
                                    </div>
                                </div>    
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                </div>
            </div>
           
        </div>
    
    <?php ActiveForm::end(); ?>

</div><!-- changePassword -->
