    <?php

    use yii\helpers\Html;
    use yii\bootstrap4\ActiveForm;
    use yii\helpers\Url;

    ?>
    <div class="container d-flex justify-content-center">
    <div class="card card-default shadow border-none bg-white loginbox" style="font-family:'Lucida Bright';margin:auto;border-radius:4px!important">
    <div class="card-body text-center">
      <i class="fa fa-user fa-2x text-success mb-4"></i>
    <?php $form = ActiveForm::begin() ?>
       <div class="container" >
         <div class="row">
           <div class="col-md-12">
            
               <?= $form->field($model, 'username')->textInput(['class' => 'form-control form-control-sm', 'placeholder' => 'Username'])->label(false) ?>
           </div>
        <div class="col-md-12">
            
               <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control form-control-sm', 'placeholder' => 'Password'])->label(false) ?>
           </div>
    
  
            
           <div class="col-md-12 mr-auto ml-auto">
             <?= Html::submitButton('<i class="fa fa-sign-in-alt"></i> Login', ['class' => 'btn btn-success btn-sm col-12 col-sm-12 col-md-6 col-lg-6 p-1'])?>
           </div>


           </div>
         </div>
        
       </div>
    <?php ActiveForm::end() ?>
 
    </div>
   
  </div>
  </div>
  <?php
    $script = <<<JS
$(document).ready(function(){


 
})
JS;
    $this->registerJs($script);
    ?>
