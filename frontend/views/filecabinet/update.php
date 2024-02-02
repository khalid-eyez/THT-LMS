<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->params['pageTitle']="Update Label";
?>
<div class="row p-3 m-3 text-center text-success text-lg"><span class="col"><i class="fa fa-info-circle"></i> All documents under this label will also be affected !</span></div>
  <div class="modal-dialog modal-lg" role="document">
  
    <div class="modal-content">
    
     <div class="modal-header bg-success pl-4 p-1"><div class="modal-title ml-1"><i class='fa fa-edit'></i>Update Label</div></div>
      <div class="modal-body pl-4 pr-4">

      <div class="meeting-form">

<?php $form = ActiveForm::begin(['method'=>'post']); ?>

<?= $form->field($model, 'prefix')->textInput(['placeholder' =>"Reference prefix (ex. THTU/HQ/AD.01/ )"])->label(false) ?>

<?= $form->field($model, 'name')->textInput(['placeholder' =>"Reference name (ex. general affairs)"])->label(false) ?>

<div class="form-group">
    <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn col-sm-3 btn-success btn-sm float-right']) ?>
</div>

<?php ActiveForm::end(); ?>

</div>

</div>
</div>
</div>
<?php
$script = <<<JS
    $('document').ready(function(){
    $('.cabinet').addClass("active");
    
  

   })
JS;
$this->registerJs($script);
?>

