<?php
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="breadcomb-area bg-white">
    <div class="container bg-white">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg-white">
                <div class="wizard-wrap-int">
                    <div class="loan-search-bar" style="margin-bottom:20px;">
                        <?php $form = ActiveForm::begin([
                            'options' => ['class' => 'w-100'], // full width
                        ]); ?>

                        <div class="row">
                            <div class="col-md-9 col-sm-12 mb-2">
                                <?= $form->field($model, 'date_range')->widget(DateRangePicker::classname(), [
                                    'convertFormat' => true,
                                    'pluginOptions' => [
                                        'locale' => [
                                            'format' => 'Y-m-d',
                                            'separator' => ' - ',
                                        ],
                                        'opens' => 'left',
                                        'autoUpdateInput' => true,
                                        'autoApply' => true,
                                    ],
                                    'options' => [
                                        'class' => 'form-control',
                                        'placeholder' => 'Select date range',
                                    ],
                                ])->label(false); ?>
                            </div>

                            <div class="col-md-1 col-sm-12 mb-2">
                                <?= Html::submitButton('<i class="fa fa-search"></i> Search', [
                                    'class' => 'btn btn-primary w-100',
                                ]) ?>
                            </div>
                       

                        <?php ActiveForm::end(); ?>
                        <div class="col-md-2 col-sm-12">
                        <a class="btn btn-primary rep" href="/loans/loans/excutive-summary-pdf"><i class="fa fa-file-pdf-o"></i> PDF</a>
                        <a class="btn btn-primary rep" href="/loans/loans/excutive-summary-excel"><i class="fa fa-file-excel-o"></i> EXCEL</a>
                        </div>
                         </div>
                    </div>
                    <div class="cashbook">


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJs(<<<JS
$(document).ready(function () {

    // Handle search form submit via AJAX
    $('.loan-search-bar form').on('submit', function (e) {
        e.preventDefault(); // stop normal submit

        var form = $(this);
        var url  = form.attr('action') 
                    ? form.attr('action') 
                    : '/loans/loans/excutive-summary-reporter';

        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            beforeSend: function () {
                $('#global-loader').show();
            },
            success: function (response) {
                $('.cashbook').html(response);
            },
            error: function (xhr) {
                $('.cashbook').html(
                    '<div class="alert alert-danger">Failed to load data.</div>'
                );
                console.error(xhr.responseText);
            },
            complete:function(xhr)
            {
                 $('#global-loader').hide();
            }
        });
    });
$('.loan-search-bar').on('click', '.rep', function (e) {
    e.preventDefault();

    var btn  = $(this);
    var form = btn.closest('.loan-search-bar').find('form');
    var url   = btn.attr('href');

    // ALWAYS read from the form itself
    var dateInput = form.find('input[name="ExcutiveSummary[date_range]"]');
    var dateRange  = $.trim(dateInput.val());

    // if (!dateRange) {
    //     alert('Please select a date range first.');
    //     return;
    // }

    // Build Yii-compatible POST payload
    var postData = {};
    postData[yii.getCsrfParam()] = yii.getCsrfToken();
    postData['ExcutiveSummary[date_range]'] = dateRange;

    // Hidden form for download
    var downloadForm = $('<form>', {
        method: 'POST',
        action: url
    });

    $.each(postData, function (name, value) {
        $('<input>', {
            type: 'hidden',
            name: name,
            value: value
        }).appendTo(downloadForm);
    });

    downloadForm.appendTo('body').submit().remove();
});



});
JS
);
?>
