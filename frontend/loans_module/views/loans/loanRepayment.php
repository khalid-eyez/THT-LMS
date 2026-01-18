<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
    <div class="wizard-area">
        <div class="container p-5">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    
                    <div class="wizard-wrap-int payme" style="display: flex; flex-direction:column;align-items: center;justify-content: center;">
                        <p class="text-primary"><b>Loan Repayment</b></p>
                         <div class="border border-muted p-5 overdues" style="border: solid 1px #ccc;padding:30px;margin:4px; width:70%;background-color:rgba(240,240,240,0.7);border-radius:4px">
                         
                        </div>
                       
<?php $form = ActiveForm::begin([
    'id' => 'loan-repayment-form',
    'options' => ['enctype' => 'multipart/form-data','style'=>'width:70%'], // required for file upload
    'fieldConfig' => [
        //'template' => "{input}\n{error}", // remove labels
        'inputOptions' => ['class' => 'form-control'],
    ],
]); ?>
    <!-- Payment Date -->
    <?= $form->field($model, 'payment_date')->input('date', ['class'=>'paymentdate form-control'
        
    ])->label('Payment Date')?>
    <!-- Paid Amount -->
    <?= $form->field($model, 'paid_amount')->textInput([
        'type' => 'number',
        'step' => '0.01',
        'min' => 0,
        'placeholder' => 'Enter Paid Amount'
    ]) ?>

    <!-- Payment Document Upload -->
    <?= $form->field($model, 'payment_doc')->fileInput([
        'class' => 'form-control'
    ]) ?>

    <!-- Submit Button -->
    <div class="form-group mt-3">
        <?= Html::submitButton('Submit Repayment', ['class' => 'btn btn-primary pull-right']) ?>
    </div>

<?php ActiveForm::end(); ?>
</div>
</div>
</div>
</div></div>
<?php $this->registerJs("
$(document).ready(function(){

    // Helper: get query param from URL
    function getQueryParam(param) {
        var urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    // When the date input changes
    $('body').on('change','.paymentdate', function(){
        var payment_date = $(this).val(); // format: YYYY-MM-DD
        var loanID = getQueryParam('loanID');
          if(!payment_date || !loanID){
            console.log('Missing payment_date or loanID');
            return;
        }
            console.log(payment_date+' '+loanID);
        // Load content via AJAX into .ovedues
        $.get('/loans/loans/repayment-overdues', {
        payment_date: payment_date,
        loanID: loanID
        }, function(html){
        $('.overdues').html(html);
        });
    });

    // Trigger change on page load to load default date
    $('.paymentdate').trigger('change');

});
");

$this->registerJs(<<<JS
$(document).ready(function () {

    $('body').on('submit', '#loan-repayment-form', function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        // Preserve loanID from query string (same as page load)
        var params = new URLSearchParams(window.location.search);
        if (params.has('loanID')) {
            formData.append('loanID', params.get('loanID'));
        }

        $.ajax({
            url: window.location.href, // SAME URL that loaded the page
            type: 'POST',
            data: formData,
            dataType: 'html',
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('#global-loader').show();
            },
            success: function (response) {
                // Replace the parent container with returned HTML
                $('.wizard-wrap-int.payme').html(response);
            },
            error: function (xhr) {
                alert('Submission failed: ' + xhr.status + ' ' + xhr.statusText);
            },
            complete: function () {
                $('#global-loader').hide();
            }
        });

        return false;
    });

});
JS);
 ?>
