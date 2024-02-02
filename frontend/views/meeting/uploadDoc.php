<?php  
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use frontend\models\ReposUpload;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use kartik\file\FileInput;
use yii\widgets\Pjax;


AppAsset::register($this);
$this->params['pageTitle']="Upload Documents";
?>


    <div class="modal-content">
     <div class="modal-header bg-success pl-4 p-1"><div class="modal-title ml-1"><i class='fa fa-upload'></i> Upload Document</div></div>
      <div class="modal-body pl-4 pr-4">
    
      <?php 
       Pjax::begin(['id'=>'docform','timeout'=>'3000']);
      $form = ActiveForm::begin(['method'=>'post','options' => ['data-pjax' => true,'enctype'=>'multipart/form-data']]);
      ?>
        <div class="row">
        <div class="col-md-12">
        <?= $form->field($model, 'title')->textInput(['class'=>'form-control form-control-sm', 'placeholder'=>'Document Title'])->label(false)?>
        </div> 
        </div>
      <div class="row">
      <div class="col-md-12 p-0">
      <?php
   Pjax::begin(['id'=>'docloader1']);
   ?>


   <div class="overlay mt-0" id="docloading1" style="background-color:rgba(0,0,255,.1);color:#fff;display:none;position:absolute;height:75%;width:100%">
     <i class="fas fa-2x fa-sync-alt fa-spin text-white font-weight-bold"></i> Uploading...
   </div>
   <?php

   Pjax::end();
?>
<?= $form->errorSummary($model) ?>
      <?=

$form->field($model, 'file')->widget(FileInput::classname(),[
   'options' => ['multiple' => false],
    'pluginOptions' => [
        'showUpload' => true,
        'browseLabel' => 'Browse',
        'removeLabel' => 'Remove',
        'uploadClass' => ' mx-2 btn btn-success float-right',
        'browseClass' => 'btn btn-success float-right',
        'removeClass' => 'btn btn-default text-danger float-right',
        'removeIcon'=>'<i class="fa fa-trash"></i>',
        'uploadIcon'=>'<i class="fa fa-upload"></i>',
        'browseIcon'=>'<i class="fa fa-search"></i>'
    ]

])->label(false);

?>
       </div>
      
        <?php 
        ActiveForm::end();
        Pjax::end();
        ?>
        

</div>
        </div>
    </div>
    </div>
  </div>
<?php
$script = <<<JS
    $('document').ready(function(){
     $('.meetings').addClass("bg-success");
      $('#docform').on('pjax:send', function() {
       $('#docloading1').show();
       })
      $('#docform').on('pjax:complete', function() {
      $('#docloading1').hide();
            })
        })

    JS;
    $this->registerJs($script);
