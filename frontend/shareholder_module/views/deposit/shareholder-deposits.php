<?php
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var common\models\DepositSearch $searchModel */
/** @var int $shareholderID */
?>

<?php
$this->registerCss(<<<CSS
#depositsStatementModal .breadcomb-area,
#depositsStatementModal .breadcomb-area .container,
#depositsStatementModal .breadcomb-area .row,
#depositsStatementModal .wizard-wrap-int {
    width: 100% !important;
    max-width: 100% !important;
}

#depositsStatementModal .breadcomb-area .container {
    padding-left: 10px !important;
    padding-right: 10px !important;
}

#depositsStatementModal .modal-body {
    overflow-x: hidden;
}

/* ✅ KEY FIX: make daterangepicker popup clickable above modal/backdrop */
#depositsStatementModal .daterangepicker {
    z-index: 200000 !important;
}
CSS);
?>

<div class="breadcomb-area bg-white">
    <div class="container bg-white">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg-white">
                <div class="wizard-wrap-int">

                    <!-- 🔍 SEARCH BAR -->
                    <div class="loan-search-bar" style="margin-bottom:20px;">
                        <?php $form = ActiveForm::begin([
                            'id'      => 'deposit-search-form',
                            'action'  => Url::to(['/shareholder/deposit/shareholder-deposits', 'shareholderID' => $shareholderID]),
                            'method'  => 'post',
                            'options' => ['class' => 'w-100'],
                        ]); ?>

                        <div class="row">

                            <!-- Date Range Picker -->
                            <div class="col-md-9 col-sm-12 mb-2">
                                <?= $form->field($searchModel, 'deposit_date')->widget(DateRangePicker::classname(), [
                                    'convertFormat' => true,
                                    'pluginOptions' => [
                                        'locale' => [
                                            'format' => 'Y-m-d',
                                            'separator' => ' - ',
                                        ],
                                        'opens' => 'left',
                                        'autoUpdateInput' => true,
                                        'autoApply' => true,

                                        // ✅ keep popup inside modal
                                        'parentEl' => '#depositsStatementModal .modal-body',
                                    ],
                                    'options' => [
                                        'class' => 'form-control',
                                        'placeholder' => 'Select date range',
                                        'autocomplete' => 'off',
                                    ],
                                ])->label(false); ?>
                            </div>

                            <!-- Search Button -->
                            <div class="col-md-1 col-sm-12 mb-2">
                                <?= Html::submitButton('<i class="fa fa-search"></i>', [
                                    'class' => 'btn btn-primary w-100 pull-left',
                                ]) ?>
                            </div>

                            <?php ActiveForm::end(); ?>

                            <!-- Export Buttons -->
                            <div class="col-md-2 col-sm-12">
                                <!-- ✅ PDF points to your PDF report action -->
                                <a class="btn btn-primary rep pull-right" style="margin-left:3px"
                                   href="<?= Url::to(['/shareholder/deposit/shareholder-deposits-pdf-report', 'shareholderID' => $shareholderID]) ?>">
                                    <i class="fa fa-file-pdf-o"></i>
                                </a>

                                <!-- ✅ EXCEL NOW points to YOUR new Excel controller action -->
                                <a class="btn btn-primary rep pull-right"
                                   href="<?= Url::to(['/shareholder/deposit/shareholder-deposits-excel-report', 'shareholderID' => $shareholderID]) ?>">
                                    <i class="fa fa-file-excel-o"></i>
                                </a>
                            </div>

                        </div>
                    </div>

                    <!-- 📄 RESULTS AREA -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg-white">
                            <div class="cashbook">
                                <div class="alert alert-info">Search results</div>
                            </div>
                        </div>
                    </div>

                </div><!-- wizard-wrap-int -->
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
$(document).ready(function () {

    function ensureDatePickerEnabled() {
        var \$input = $('input[name="DepositSearch[deposit_date]"]');

        \$input.prop('disabled', false);
        \$input.prop('readonly', false);

        var drp = \$input.data('daterangepicker');
        if (drp && $('#depositsStatementModal .modal-body').length) {
            drp.parentEl = $('#depositsStatementModal .modal-body');
            drp.container.appendTo(drp.parentEl);
        }
    }

    // 🔁 Search via AJAX (POST)
    $(document).on('submit', '.loan-search-bar form', function (e) {
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
                ensureDatePickerEnabled();
            }
        });
    });

    // 📤 Export buttons (POST with date range)
    $(document).on('click', '.loan-search-bar .rep', function (e) {
        e.preventDefault();

        var btn  = $(this);
        var form = btn.closest('.loan-search-bar').find('form');
        var url  = btn.attr('href');

        var dateInput = form.find('input[name="DepositSearch[deposit_date]"]');
        var dateRange = $.trim(dateInput.val());

        var postData = {};
        postData[yii.getCsrfParam()] = yii.getCsrfToken();
        postData['DepositSearch[deposit_date]'] = dateRange;

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
