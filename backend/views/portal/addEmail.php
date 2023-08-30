<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form ActiveForm */
$this->params['breadcrumbs'] = [
    ['label'=>'Add Email', 'url'=>Url::to(['/home/add_email'])],
    ['label'=>$this->title]
  ];
?>
<div class="add_email">

    <?php $form = ActiveForm::begin(); ?>
    <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3">
                </div>
                <div class="col-sm-6">
                    <div class="card shadow-lg" style="font-family:'Times New Roman', sans-serif">
                        <div class="card-header text-center bg-primary">
                            <h2>Change Password</h2>
                        </div>
                        <div class="card-body">
                                <div class="row">
                                <div class="col-sm-12">
                                        <?= $form->field($model, 'email')->input('email',['class'=>'form-control form-control-sm', 'placeholder'=>'Add Email'])->label(false) ?>
                                    </div>
                                    <div class="form-group">
                                        <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
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

</div><!-- add_email -->
