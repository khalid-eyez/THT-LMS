<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->params['pageTitle']="Update Document";
?>
  <div class="modal-dialog modal-lg" role="document">
  
    <div class="modal-content">
    
     <div class="modal-header bg-success pl-4 p-1"><div class="modal-title ml-1"><i class='fa fa-edit'></i> Update Document - <?=$model->getReference()?></div></div>
      <div class="modal-body pl-4 pr-4">

      <div class="meeting-form text-sm text-muted">

<?php $form = ActiveForm::begin(['method'=>'post']); ?>

<?= $form->field($model, 'docTitle')->textInput(['placeholder' =>"Document Title"])->label("Document Title") ?>

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

