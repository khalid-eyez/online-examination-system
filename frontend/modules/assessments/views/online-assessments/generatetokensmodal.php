<?php  
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;
?>
<link href="/css/select2.min.css" rel="stylesheet" />


<div class="modal fade" id="tokensmodal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success pt-1 pb-1">
        <span class="modal-title"><i class="fa fa-cogs"></i> Generate Tokens</span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <?php 
      Pjax::begin(['id'=>'groupsform','timeout'=>'300000']);
      $form= ActiveForm::begin(['method'=>'post','options' => ['data-pjax' => true ],'id'=>'assform'])?>
   
        <div class="row">
        <div class="col-md-12">
        <?= $form->field($tokenizer, 'expiredate')->input('date', ['class'=>'form-control form-control form-control-sm'])->label('Expire Date')?>
        </div>
       </div>
       <div class="row">
        <div class="col-md-12">
        <?= $form->field($tokenizer, 'expiretime')->input('time', ['class'=>'form-control form-control form-control-sm'])->label('Expire time')?>
        </div>
       </div>
       <div class="row">
        <div class="col-md-12">
        <?= $form->field($tokenizer, 'num')->textInput(['class'=>'form-control form-control-sm', 'placeholder'=>'Eg: 23'])->label("Quantity")?>
        </div> 
        </div>
  
        <div class="row">
        <div class="col-md-12">
        <?= Html::submitButton('<i class="fa fa-cogs"></i> Generate', ['class'=>'btn btn-success btn-md float-right ml-2']) ?>
        </div>
        </div>
        <?php 
       
        ActiveForm::end();
        Pjax::end();
        ?>
              <?php
   Pjax::begin(['id'=>'loader']);
   ?>
      <div class="overlay" id="loading" style="background-color:rgba(0,0,255,.3);color:#fff;display:none">
     <i class="fas fa-2x fa-sync-alt fa-spin"></i>Generating...
</div>
<?php

Pjax::end();
?>
    </div>
    </div>
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


