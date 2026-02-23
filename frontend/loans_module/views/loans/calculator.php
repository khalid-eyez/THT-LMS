<?php
/**
 * _form.php – Loan calculator (forced horizontal, AJAX + PDF download)
 *
 * @var yii\web\View $this
 * @var frontend\loans_module\models\LoanCalculatorForm $model
 */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use common\models\CustomerLoan;
?>

<div class="wizard-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="wizard-wrap-int">

                    <?php $form = ActiveForm::begin([
                        'id' => 'loan-calculator-form',
                        'method' => 'post',
                        'options' => [
                            'class' => 'w-100',
                            'autocomplete' => 'off',
                        ],
                        'fieldConfig' => [
                            'options' => ['class' => 'm-0 p-0'],
                            'errorOptions' => ['class' => 'text-danger small mt-1'],
                        ],
                    ]); ?>

                    <div class="row gx-2 gy-1 align-items-start">
                        <div class="col-lg-3 col-md-6">
                            <?= $form->field($model, 'type', [
                                'template' => "{input}\n{error}",
                            ])->dropDownList(
                                $model->types(),
                                [
                                    'prompt' => '-- Select Loan Type --',
                                    'class' => 'form-control',
                                ]
                            )->label(false); ?>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <?= $form->field($model, 'repayment_frequency', [
                                'template' => "{input}\n{error}",
                            ])->dropDownList(
                                CustomerLoan::optsRepaymentFrequency(),
                                [
                                    'prompt' => '-- Select Repayment Frequency --',
                                    'class' => 'form-control',
                                ]
                            )->label(false); ?>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <?= $form->field($model, 'loan_duration', [
                                'template' => "{input}\n{error}",
                            ])->textInput([
                                'type' => 'number',
                                'min' => 1,
                                'placeholder' => 'Duration',
                                'class' => 'form-control',
                            ])->label(false); ?>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <?= $form->field($model, 'loan_amount', [
                                'template' => "{input}\n{error}",
                            ])->textInput([
                                'type' => 'number',
                                'step' => '0.01',
                                'min' => 0,
                                'placeholder' => 'Amount',
                                'class' => 'form-control',
                            ])->label(false); ?>
                        </div>

                        <!-- Buttons -->
                        <div class="col-lg-2 col-md-12">
                            <div class="d-flex gap-2">
                                <?= Html::submitButton(
                                    '<i class="fa fa-calculator me-1"></i> Calculate',
                                    [
                                        'id' => 'btn-loan-calc',
                                        'class' => 'btn btn-primary w-100',
                                        'type' => 'submit',
                                        'encode' => false,
                                    ]
                                ) ?>
                                <?php if(yii::$app->user->can("download_loan_calculator_report")){?>
                                <?= Html::button(
                                    '<i class="fa fa-file-pdf-o me-1"></i> PDF',
                                    [
                                        'id' => 'btn-loan-pdf',
                                        'class' => 'btn btn-primary w-100',
                                        'type' => 'button',
                                        'title' => 'Download PDF',
                                        'encode' => false,
                                    ]
                                ) ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>

                    <!-- Result container -->
                    <div class="container-fluid mt-3">
                        <div id="loan-calc-result" class="card border-0 shadow-sm">
                            <div class="card-body text-muted"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
/* ================= CSS ================= */
$this->registerCss("
    .wizard-wrap-int .row { display:flex; flex-wrap:wrap; }
    .wizard-wrap-int [class*='col-'] { padding-left:4px; padding-right:4px; }
    .wizard-wrap-int .form-control { padding:.375rem .5rem; }
    .wizard-wrap-int .text-danger { color:#dc3545 !important; font-size:.8rem; }

    /* Light bluish transparent table header */
    .repay-table thead th {
        background: rgba(13, 110, 253, 0.08);
        color: #0d3b66;
        font-weight: 600;
        border-bottom: 1px solid rgba(13, 110, 253, 0.2);
    }
");

/* ================= JS ================= */
$this->registerJs("
$(document).ready(function () {

    var formSelector   = '#loan-calculator-form';
    var resultSelector = '#loan-calc-result';
    var loaderSelector = '#global-loader';
    var btnSelector    = '#btn-loan-calc';
    var pdfBtnSelector = '#btn-loan-pdf';
    var pdfUrl         = '/loans/loans/loan-calculator-pdf';

    // Calculate (AJAX)
    $(formSelector).on('submit', function (e) {
        e.preventDefault();

        var url = $(this).attr('action');
        if (!url || url.length === 0) {
            url = window.location.href;
        }

        $(loaderSelector).show();
        $(btnSelector).prop('disabled', true);
        $(pdfBtnSelector).prop('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            success: function (html) {
                $(resultSelector).html(html);
            },
            error: function (xhr) {
                var msg = 'Request failed (' + xhr.status + '): ' + (xhr.statusText || 'Error');
                $(resultSelector).html(
                    '<div class=\"card border-0 shadow-sm\">' +
                        '<div class=\"card-body text-danger\">' + msg + '</div>' +
                    '</div>'
                );
            },
            complete: function () {
                $(loaderSelector).hide();
                $(btnSelector).prop('disabled', false);
                $(pdfBtnSelector).prop('disabled', false);
            }
        });
    });

    // PDF download (POST, real file)
    $(pdfBtnSelector).on('click', function () {

        $(loaderSelector).show();
        $(pdfBtnSelector).prop('disabled', true);

        if ($('#pdf-download-frame').length === 0) {
            $('body').append('<iframe id=\"pdf-download-frame\" name=\"pdf-download-frame\" style=\"display:none;\"></iframe>');
        }

        var tempForm = $('<form>', {
            method: 'POST',
            action: pdfUrl,
            target: 'pdf-download-frame'
        });

        var fields = $(formSelector).serializeArray();
        $.each(fields, function (i, field) {
            tempForm.append(
                $('<input>', { type: 'hidden', name: field.name, value: field.value })
            );
        });

        $('body').append(tempForm);
        tempForm.trigger('submit');
        tempForm.remove();

        setTimeout(function () {
            $(loaderSelector).hide();
            $(pdfBtnSelector).prop('disabled', false);
        }, 800);
    });

});
");
?>
