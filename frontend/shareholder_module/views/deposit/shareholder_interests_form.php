<?php
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var \common\models\ShareholderInterestForm $model */
/** @var int $shareholderID */
?>

<?php
$this->registerCss(<<<CSS
/* container sizing inside modal */
#interestStatementModal .breadcomb-area,
#interestStatementModal .breadcomb-area .container,
#interestStatementModal .breadcomb-area .container-fluid,
#interestStatementModal .breadcomb-area .row,
#interestStatementModal .wizard-wrap-int {
    width: 100% !important;
    max-width: 100% !important;
}

#interestStatementModal .breadcomb-area .container,
#interestStatementModal .breadcomb-area .container-fluid {
    padding-left: 10px !important;
    padding-right: 10px !important;
}

#interestStatementModal .modal-body {
    overflow-x: hidden;
}

/* ensure daterangepicker above modal */
#interestStatementModal .daterangepicker {
    z-index: 200000 !important;
}

/* ensure input clickable */
#interestStatementModal input[name="ShareholderInterestForm[date_range]"]{
    pointer-events: auto !important;
    background: #fff !important;
    cursor: pointer !important;
}
CSS);
?>

<div class="breadcomb-area bg-white">
    <div class="container-fluid bg-white">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg-white">
                <div class="wizard-wrap-int">

                    <!-- 🔍 SEARCH BAR -->
                    <div class="loan-search-bar" style="margin-bottom:20px;">
                        <?php $form = ActiveForm::begin([
                            'id'      => 'shareholder-interest-search-form',
                            // fallback; JS will sync to the URL that loaded the modal
                            'action'  => Url::to(['/shareholder/deposit/shareholder-interest-statement', 'shareholderID' => $shareholderID]),
                            'method'  => 'post',
                            'options' => ['class' => 'w-100'],
                        ]); ?>

                        <div class="row">

                            <!-- Date Range Picker -->
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
                                        'parentEl' => '#interestStatementModal .modal-body',
                                    ],
                                    'options' => [
                                        'class' => 'form-control',
                                        'placeholder' => 'Select date range',
                                        'autocomplete' => 'off',
                                        'readonly' => false,
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
                                <!-- ✅ PDF posts to interest-statement-pdf with date_range -->
                                <a class="btn btn-primary rep pull-right" style="margin-left:3px"
                                   href="<?= Url::to(['/shareholder/deposit/shareholder-interest-statement-pdf', 'shareholderID' => $shareholderID]) ?>">
                                    <i class="fa fa-file-pdf-o"></i>
                                </a>

                                <a class="btn btn-primary rep pull-right"
                                   href="<?= Url::to(['/shareholder/deposit/shareholder-interest-statement-excel', 'shareholderID' => $shareholderID]) ?>">
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
(function () {

    var formSel     = '#shareholder-interest-search-form';
    var inputSel    = 'input[name="ShareholderInterestForm[date_range]"]';
    var modalSel    = '#interestStatementModal';
    var bodySel     = modalSel + ' .modal-body';
    var cashbookSel = modalSel + ' .cashbook';

    function ensureDatePickerEnabled() {
        var \$input = \$(modalSel + ' ' + inputSel);
        \$input.prop('disabled', false);
        \$input.prop('readonly', false);

        var drp = \$input.data('daterangepicker');
        if (drp && \$(bodySel).length) {
            drp.parentEl = \$(bodySel);
            if (drp.container && drp.container.length) {
                drp.container.appendTo(drp.parentEl);
            }
        }
    }

    // sync form action to the URL that loaded the modal
    function syncFormActionToLoadedUrl() {
        var loadedUrl = \$(modalSel).data('loaded-url');
        if (loadedUrl) {
            \$(formSel).attr('action', loadedUrl);
        }
    }

    \$(document).ready(function () {
        ensureDatePickerEnabled();
        syncFormActionToLoadedUrl();
    });

    \$(document).on('shown.bs.modal', modalSel, function () {
        ensureDatePickerEnabled();
        syncFormActionToLoadedUrl();
    });

    // 🔁 AJAX search: POST to the same URL that loaded the modal; render into .cashbook
    \$(document).off('submit.shareholderInterestSearch');
    \$(document).on('submit.shareholderInterestSearch', formSel, function (e) {
        e.preventDefault();

        var \$form = \$(this);
        var url = \$(modalSel).data('loaded-url') || \$form.attr('action');

        \$.ajax({
            url: url,
            type: 'POST',
            data: \$form.serialize(),
            beforeSend: function () {
                if (\$('#global-loader').length) \$('#global-loader').show();
            },
            success: function (html) {
                \$(cashbookSel).html(html);
            },
            error: function (xhr) {
                \$(cashbookSel).html('<div class="alert alert-danger">Failed to load data.</div>');
                console.error(xhr.responseText);
            },
            complete: function () {
                if (\$('#global-loader').length) \$('#global-loader').hide();
                ensureDatePickerEnabled();
            }
        });

        return false;
    });

    // 📤 Export buttons (POST + date_range)
    \$(document).off('click.shareholderInterestExport');
    \$(document).on('click.shareholderInterestExport', modalSel + ' .loan-search-bar .rep', function (e) {
        e.preventDefault();

        var btn  = \$(this);
        var url  = btn.attr('href'); // PDF or Excel endpoint
        var \$form = \$(formSel);

        var dateRange = \$.trim(\$form.find(inputSel).val());

        var postData = {};
        postData[yii.getCsrfParam()] = yii.getCsrfToken();
        postData['ShareholderInterestForm[date_range]'] = dateRange;

        var downloadForm = \$('<form>', { method: 'POST', action: url });

        \$.each(postData, function (name, value) {
            \$('<input>', { type: 'hidden', name: name, value: value }).appendTo(downloadForm);
        });

        downloadForm.appendTo('body').submit().remove();
        return false;
    });

})();
JS
);
?>
