<?php
use yii\bootstrap4\Breadcrumbs;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use kartik\password\PasswordInput;


AppAsset::register($this);



/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form ActiveForm */

?>
<style>
    .invalid-feedback
    {
      display:block;
      color:red;
    }
    input,
input::placeholder {
    font: 16px sans-serif;
}
</style>
<div class="changePassword">

    <?php $form = ActiveForm::begin(["action"=>"/portal/portal/change-password-restrict"]); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3">
                </div>
                <div class="col-sm-6">
                    <div class="card shadow" style="font-family:'Times New Roman', sans-serif">
                        <div class="card-header text-center bg-success pt-1 pb-1 text-md">
                            <i class="fa fa-lock"></i> Change Password
                        </div>
                        <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?= $form->field($model, 'current_password')->passwordInput(['class'=>'form-control form-control-sm', 'placeholder'=>'Current Password'])->label(false) ?>
                                    </div>

                                    <div class="col-sm-12">
                                    <?=$form->field($model, 'new_password')->widget(PasswordInput::classname(), [
                                    'size' => "md",
                                    'options' => [
                                    'placeholder' => "New Password"
                                    ],
                                    'pluginOptions' => ['toggleMask' => false,'placeholder' => "New Password"]
                                    ])->label(false);?>
                                    </div>

                                    <div class="col-sm-12">
                                        <?= $form->field($model, 'confirm_new_password')->passwordInput(['class'=>'form-control form-control-sm', 'placeholder'=>'Confirm New Password'])->label(false) ?>
                                    </div>
                                    
                                    <div class="col-sm-12">
                                        <?= Html::submitButton("<i class='fa fa-save'></i> Save Changes", ['class' => 'btn btn-success btn-md float-right']) ?>
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
