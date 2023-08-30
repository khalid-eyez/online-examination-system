<?php
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\modules\admin\models\RegisterHodsForm;
use yii\helpers\ArrayHelper;
use common\models\AuthItem;
use common\models\Department;


$model = new RegisterHodsForm;
?>

    

<div class="modal fade" id="hodmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
     <div class="modal-header text-primary pl-4"><div class="modal-title ml-1"><i class='fa fa-plus-circle'></i> Register HOD</div></div>
      <div class="modal-body">
        <div class="container-fluid">
       
        <!-- Main row -->
        <div class="row">
        <section class="col-md-12">
             
            
            
              <div class="row">
              <div class="col-sm-12">
      
              <?php $form = ActiveForm::begin(['action'=>"create-hods",'method'=>'post'])?>
               <div class="col-sm-12"><?=$form->errorSummary($model)?></div>
                 <div class="col-sm-12">
                  <div class="row">
                  <div class="col-sm-12">
                   <?= $form->field($model, 'full_name')->textInput(['class'=>'form-control form-control-sm','placeholder'=>"full name"])->label(false) ?>
                  </div>  
                 </div> 

                   <div class="row">
                   <div class="col-sm-6">
                   <?= $form->field($model, 'username')->textInput(['class'=>'form-control form-control-sm','placeholder'=>"email"])->label(false) ?>
                  </div>
                  <div class="col-sm-6">
                   <?= $form->field($model, 'phone')->textInput(['class'=>'form-control form-control-sm','placeholder'=>'Phone number'])->label(false) ?>
                  </div>  
                 </div>
                 <div class="row">
                 <div class="col-sm-6">
                
                   <?= $form->field($model, 'gender')->dropdownList(['M'=>'MALE', 'F'=>'FEMALE'], ['prompt'=>'--Gender--', 'class'=>'form-control form-control-sm'] )->label(false) ?>
                
                 </div>
                 
                 </div>
               
                   <div class="row">
                    <div class="col-sm-12">
                        
                     <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class'=>'btn btn-default btn-md float-right mr-0 text-primary']) ?>
                
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
