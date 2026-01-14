<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
    <div class="wizard-area">
        <div class="container p-5">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    
                    <div class="wizard-wrap-int" style="display: flex; flex-direction:column;align-items: center;justify-content: center;">
                        <p class="text-primary"><b>Loan Disbursement</b></p>
                        <div class="border border-muted p-5" style="border: solid 1px #ccc;padding:30px;margin:4px; width:70%;background-color:rgba(240,240,240,0.7);border-radius:4px">
                         <b>Loan Amount:</b> <?=yii::$app->formatter->asDecimal($loan->loan_amount,2) ?> TZS<br>
                         <b>Processing Fee:</b> <?= yii::$app->formatter->asDecimal($loan->processing_fee,2) ?> TZS<br>
                         <hr>
                         <b>Deposit Amount:</b> <?= yii::$app->formatter->asDecimal($loan->deposit_amount,2) ?> TZS
                        </div>
<?php $form = ActiveForm::begin([
    'id' => 'deposit-account-form',
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'options' => ['method'=>'post','action'=>'','enctype' => 'multipart/form-data','style'=>'width:70%'], // REQUIRED for file upload
]); ?>

<!-- Deposit Account -->
<?= $form->field($loan, 'deposit_account')
    ->textInput([
        'maxlength' => true,
        'placeholder' => 'Deposit account number',
    ])->label(false) ?>

<!-- Deposit Account Names -->
<?= $form->field($loan, 'deposit_account_names')
    ->textInput([
        'maxlength' => true,
        'placeholder' => 'Account holder name(s)',
    ])->label(false) ?>

<!-- Reference File Upload (DIFFERENT MODEL) -->
<?= $form->field($document, 'file',[
    'enableClientValidation' => false,
])->fileInput()->label('Reference Document') ?>

<div class="form-group">
    <?= Html::submitButton('Confirm & Record Payment', ['class' => 'btn btn-primary','style'=>'float:right']) ?>
</div>

<?php ActiveForm::end(); ?>
</div>
</div>
</div>
</div></div>
<?php $this->registerJs("
                    
                    $('document').ready(function(){
                        //$('.loans').addClass('active');
                         $('.notika-menu-wrap > li').each(function(){
                         $(this).removeClass('active')
                         })
                        
                       //$('input').wrap('<div class='col-lg-6'></div>'); 
                    })
                    "
                    );
                ?>