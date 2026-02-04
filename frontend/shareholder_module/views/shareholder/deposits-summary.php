<?php
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var frontend\shareholder_module\models\DepositsSummaryForm $model */
?>

<div class="breadcomb-area bg-white">
    <div class="container bg-white">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg-white">
                <div class="wizard-wrap-int">

                    <div class="loan-search-bar" style="margin-bottom:20px;">
                        <?php $form = ActiveForm::begin([
                            'id' => 'deposits-summary-form',
                            'action' => Url::to(['/shareholder/shareholder/deposits-summary-reporter']),
                            'method' => 'post',
                            'options' => ['class' => 'w-100'],
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
                                        'autocomplete' => 'off',
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
                                <a class="btn btn-primary rep" href="<?= Url::to(['/shareholder/shareholder/deposits-summary-pdf']) ?>">
                                    <i class="fa fa-file-pdf-o"></i> PDF
                                </a>
                                <a class="btn btn-primary rep" href="<?= Url::to(['/shareholder/shareholder/deposits-summary-excel']) ?>">
                                    <i class="fa fa-file-excel-o"></i> EXCEL
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="cashbook">
                        <div class="alert alert-info">Search results</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
$(document).ready(function () {

    // AJAX search
    $(document).on('submit', '#deposits-summary-form', function (e) {
        e.preventDefault();

        var form = $(this);
        var url  = form.attr('action');

        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            beforeSend: function () {
                if ($('#global-loader').length) $('#global-loader').show();
            },
            success: function (response) {
                $('.cashbook').html(response);
            },
            error: function (xhr) {
                $('.cashbook').html('<div class="alert alert-danger">Failed to load data.</div>');
                console.error(xhr.responseText);
            },
            complete: function () {
                if ($('#global-loader').length) $('#global-loader').hide();
            }
        });
    });

    // Export buttons (POST with date range)
    $(document).on('click', '.loan-search-bar .rep', function (e) {
        e.preventDefault();

        var btn  = $(this);
        var form = btn.closest('.loan-search-bar').find('form');
        var url  = btn.attr('href');

        var dateInput = form.find('input[name="DepositsSummaryForm[date_range]"]');
        var dateRange = $.trim(dateInput.val());

        var postData = {};
        postData[yii.getCsrfParam()] = yii.getCsrfToken();
        postData['DepositsSummaryForm[date_range]'] = dateRange;

        var downloadForm = $('<form>', { method: 'POST', action: url });

        $.each(postData, function (name, value) {
            $('<input>', { type: 'hidden', name: name, value: value }).appendTo(downloadForm);
        });

        downloadForm.appendTo('body').submit().remove();
    });

});
JS
);
?>
