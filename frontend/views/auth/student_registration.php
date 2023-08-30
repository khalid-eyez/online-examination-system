<?php

use Amp\Internal\Placeholder;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use frontend\models\UploadStudentForm;
use kartik\password\PasswordInput;
use yii\widgets\Pjax;

/* @var $this yii\web\View */

$this->title = 'Student Registration';
?>

<div class="site-index">

    <style>
    .invalid-feedback
    {
      display:block;
      color:red;
    }
    input,
input::placeholder {
    font: 18px sans-serif;
}
.grecaptcha-badge { 
    z-index:30; 
}

</style>

    <div class="body-content">
            <!-- Content Wrapper. Contains page content -->
     
    
        <div class="container-fluid mt-md-4 mt-lg-4 mt-sm-3">

       
        <!-- Main row -->
        <div class="row d-flex justify-content-center">

        <section class="col-sm-12 col-12 col-md-12 col-lg-8 col-xl-8">
     
              <div class="card shadow-sm" style="font-family:'Times New Roman', sans-serif; border-radius: 9px;">
              <div class="card-header bg-success text-center p-1">
                <div class="row">
           
                <div class="col-md-12 col-sm-12 col-lg-12">
                 <h5 class="text-md text-bold"><span><i class="fa fa-user-plus"></i></span><span> Register Student</span></h5>
                </div>
                </div>
                </div>
              <div class="card-body">
              <div class="row">
              <div class="col-md-12">
              <?php
                Pjax::begin(['id' => 'groupsform','timeout' => '300000']);
                $form = ActiveForm::begin(['id' => 'regform',
                                  'enableClientValidation' => true,
                                  'validateOnSubmit' => true,
                                  'options' => ['data-pjax' => true ]
                                  ])?>
             
                  <div class="row">
                  <div class="col-md-4">
                   <?= $form->field($model, 'fname')->textInput(['class' => 'form-control ','placeholder' => 'First Name'])->label(false) ?>
                  </div> 
                  <div class="col-md-4">
                   <?= $form->field($model, 'mname')->textInput(['class' => 'form-control ','placeholder' => 'Middle Name'])->label(false) ?>
                  </div> 
                  <div class="col-md-4">
                   <?= $form->field($model, 'lname')->textInput(['class' => 'form-control ','placeholder' => 'Last Name'])->label(false) ?>
                  </div>  
                 </div> 

                   <div class="row">
                   <div class="col-md-6">
                   <?= $form->field($model, 'username')->textInput(['class' => 'form-control','placeholder' => 'Registration Number'])->label(false) ?>
                  </div>
           
                 </div>
                 <div class="row">
                   <div class="col-md-6">
                   <?= $form->field($model, 'email')->input('email', ['class' => 'form-control ','placeholder' => 'Email Address'])->label(false) ?>
                  </div>
                  <div class="col-md-6">
                   <?= $form->field($model, 'phone')->textInput(['class' => 'form-control ', 'placeholder' => 'Phone Number'])->label(false) ?>
                  </div>  
                 </div>
                 <div class="row"> 
          
                  <div class="col-md-4">
                
                <?= $form->field($model, 'gender')->dropdownList(['M' => 'MALE', 'F' => 'FEMALE'], ['prompt' => '--Select Gender--', 'class' => 'form-control '])->label(false) ?>
           
               </div>
                   </div>
                 <div class="row"> 
                
                 

                  <div class="col-sm-12">
                  <?=$form->field($model, 'password')->widget(PasswordInput::classname(), [
                    'size' => "md",
                    'options' => [
                    'placeholder' => "Password"
                    ],
                    'pluginOptions' => ['toggleMask' => false,'placeholder' => "Password"]
                    ])->label(false);?>
                
                  </div>

                  <div class="col-sm-12">

                  <?= $form->field($model, 'password2')->passwordInput(['class' => 'form-control form-control-sm', 'placeholder' => 'Re-type Password'])->label(false) ?>
                  </div>
                 </div>
                   <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                     <?= Html::submitButton('<i class="fa fa-user-plus" aria-hidden="true"></i>  Register', ['class' => 'btn btn-success btn-md  col-sm-12 col-md-6 col-lg-3 float-right mr-0']) ?>
                
                    </div>
               
                  
                 </div>
             
             
               
                
                
             
                <?php
                ActiveForm::end();
                Pjax::end();
                ?>
                             <?php
                                Pjax::begin(['id' => 'loader']);
                                ?>


   <div class="overlay font-weight-bold" id="loading" style="background-color:rgba(0,0,255,.2);color:#fff;display:none;position:absolute;top:35%;left:40%;height:30%;width:20%;border-radius:40px">
     <i class="fas fa-2x fa-sync-alt fa-spin text-white font-weight-bold"></i>Processing...
   </div>
   <?php

    Pjax::end();
    ?>
              </div>

 
          
              </div>
                 
              </div>
            </div>
            <!-- /.card -->

            <!-- /.card -->
          </div>
        </section>
         
       </div>
            <!-- /.card -->
      
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
        
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->

    </div>
</div>

<?php
$script = <<<JS
    $('document').ready(function(){

      $('#groupsform').on('pjax:send', function() {
       $('#loading').show();
       })
      $('#groupsform').on('pjax:complete', function() {
      $('#loading').hide();
            })
        })

    JS;
$this->registerJs($script);
