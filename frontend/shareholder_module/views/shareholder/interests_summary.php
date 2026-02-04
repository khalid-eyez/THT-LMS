<?php
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var frontend\shareholder_module\models\ShareholderInterestForm $model */
?>

<div class="breadcomb-area bg-white">
    <div class="container bg-white">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg-white">
                <div class="wizard-wrap-int">

                    <div class="loan-search-bar" style="margin-bottom:20px;">
                        <?php $form = ActiveForm::begin([
                            'id' => 'interests-summary-form',
                            'action' => Url::to(['/shareholder/shareholder/interests-summary-reporter']),
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
                                <a class="btn btn-primary rep"
                                   href="<?= Url::to(['/shareholder/shareholder/interest-summary-pdf']) ?>">
                                    <i class="fa fa-file-pdf-o"></i> PDF
                                </a>
                                <a class="btn btn-primary rep"
                                   href="<?= Url::to(['/shareholder/shareholder/interest-summary-excel']) ?>">
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

    var formSel  = '#interests-summary-form';
    var inputSel = 'input[name="ShareholderInterestForm[date_range]"]'; // ✅ FIXED

    // 🔁 AJAX search: POST to the SAME url that loaded this view
    $(document).on('submit', formSel, function (e) {
        e.preventDefault();

        var form = $(this);
        var url  = window.location.href.split('#')[0];

        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(), // ✅ now serializes ShareholderInterestForm[date_range]
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

        return false;
    });

    // 📤 Export buttons (POST with date range) - send ShareholderInterestForm[date_range]
    $(document).on('click', '.loan-search-bar .rep', function (e) {
        e.preventDefault();

        var btn  = $(this);
        var form = btn.closest('.loan-search-bar').find(formSel);
        var url  = btn.attr('href');

        var dateRange = $.trim(form.find(inputSel).val());

        var postData = {};
        postData[yii.getCsrfParam()] = yii.getCsrfToken();
        postData['ShareholderInterestForm[date_range]'] = dateRange; // ✅ FIXED

        var downloadForm = $('<form>', { method: 'POST', action: url });

        $.each(postData, function (name, value) {
            $('<input>', { type: 'hidden', name: name, value: value })
                .appendTo(downloadForm);
        });

        downloadForm.appendTo('body').submit().remove();

        return false;
    });

});
JS
);
?>
