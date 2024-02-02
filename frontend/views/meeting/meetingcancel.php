<?php  
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use frontend\models\ReposUpload;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use common\widgets\Alert;


AppAsset::register($this);
$this->params['pageTitle']="Cancel Meeting";
?>


    <div class="modal-content">
     <div class="modal-header bg-success pl-4 p-1"><div class="modal-title ml-1"><i class='fa fa-times  p-1'></i> Cancel Meeting</div></div>
      <div class="modal-body pl-4 pr-4">
    
      <?php 
      $form = ActiveForm::begin(['method'=>'post']);
      ?>
        <div class="row">
        <div class="col-md-12">
        <?= $form->field($model, 'reason')->textarea(['class'=>'form-control form-control-sm', 'placeholder'=>'Reason for cancellation'])->label(false)?>
        </div> 
        <div class="col-md-12">
        <?= Html::submitButton('<i class="fa fa-times"></i> Cancel', ['class' => 'btn btn-sm btn-success float-right mt-4 col-sm-2']) ?>
        </div> 
        </div>
        <?php 
        ActiveForm::end();
        ?>
        </div>
    </div>
    </div>
  </div>
<?php
$script = <<<JS
    $('document').ready(function(){
     $('.meetings').addClass("bg-success");

  
        })

    JS;
    $this->registerJs($script);
