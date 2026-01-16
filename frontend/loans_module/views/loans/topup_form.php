<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;


?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
   <div class="wizard-area">
        <div class="container p-5">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    
                    <div class="wizard-wrap-int" style="display: flex; flex-direction:column;align-items: center;justify-content: center;">
                        <h5 class="text-primary mt-3">Loan Top Up</h5>
<?php 
  $form = ActiveForm::begin([
    'id' => 'topup-form',
    'options' => ['enctype' => 'multipart/form-data','style'=>'width:70%'],
]);
?>
<?= $form->field($model, 'topup_mode')->dropDownList(
    [
        'tenure_retention' => 'Tenure Retention',
        'tenure_extension' => 'Tenure Extension',
    ],
    ['prompt' => 'Select Top-up Mode']
)->label(false)  ?>

<!-- Top-up Amount -->
<?= $form->field($model, 'topup_amount')->input('number', [
    'step' => '0.01',
    'min' => 0,
    'placeholder' => 'Enter top-up amount',
])->label(false) ?>

<!-- Reference Document Upload -->
<?= $form->field($model, 'reference_document')->fileInput([
])->label(false) ?>

<!-- Submit Button -->
<div class="form-group">
    <?= Html::submitButton('Submit Top-Up', [
        'class' => 'btn btn-primary pull-right',
    ]) ?>
</div>

<?php ActiveForm::end(); ?>
</div></div></div></div></div>