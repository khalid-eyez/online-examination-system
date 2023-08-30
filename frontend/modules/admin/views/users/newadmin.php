<?php
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\RegisterInstructorForm;
use yii\helpers\ArrayHelper;
use common\models\AuthItem;
use common\models\Department;
use common\models\RegisterUserForm;
use common\models\College;



$model = new RegisterUserForm();
$colleges = ArrayHelper::map(College::find()->all(), 'collegeID', 'college_name');


?>

    

<div class="modal fade" id="adminmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
     <div class="modal-header  pl-4 p-2"><div class="modal-title ml-1 text-primary"><i class='fa fa-plus-circle'></i> Add New Admin</div></div>
      <div class="modal-body">
        <div class="container-fluid">
       
        <!-- Main row -->
        <div class="row">
        <section class="col-md-12">
             
            
            
              <div class="row">
              <div class="col-sm-12">
      
              <?php $form = ActiveForm::begin(['method'=>'post','action'=>Url::to('create'),'enableClientValidation'=>true])?>
                 <div class="col-md-12">
                  <div class="row">
                  <div class="col-md-12">
                   <?= $form->field($model, 'full_name')->textInput(['class'=>'form-control form-control-sm','placeholder'=>'Full Name'])->label(false) ?>
                  </div>  
                 </div> 

                   <div class="row">
                   <div class="col-md-6">
                   <?= $form->field($model, 'username')->textInput(['class'=>'form-control form-control-sm','placeholder'=>'E-mail'])->label(false) ?>
                  </div>
                  <div class="col-md-6">
                   <?= $form->field($model, 'phone')->textInput(['class'=>'form-control form-control-sm','placeholder'=>'Phone Number'])->label(false) ?>
                  </div>  
                 </div>
                 <div class="row"> 
                 <div class="col-md-12">
                   <?= $form->field($model, 'college')->dropdownList($colleges, ['prompt'=>'--College--', 'class'=>['form-control form-control-sm']] )->label(false)?>
                  </div>
                 
                 </div>
            
               
                   <div class="row">
                    <div class="col-md-12">
                        
                     <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class'=>'btn btn-default btn-md float-right text-primary mr-0']) ?>
                
                    </div>
               
                  
                 </div>
             
                 </div>
               
                
                
             
                <?php ActiveForm::end() ?>
              
              </div>
          
              </div>
              </div>
                 
              </div>
       
        </section>
  
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->

    </div>
    </div>
</div>
</div><!-- /.container-fluid -->

</div>
</div>
<?php 
$script = <<<JS
 // Dropzone.autoDiscover = false;
$(document).ready(function(){
  //alert("Heloo JQQUERY");
  $("#file-input").fileinput({
    uploadClass:'btn btn-info',
    browseOnZoneClick: true,
    uploadIcon: '<i class="fa fa-upload"></i>'
    
  });

 
})
JS;
$this->registerJs($script);
?>
