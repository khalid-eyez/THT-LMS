<?php
/**
 * Loan Types list (Bootstrap 4) – AJAX add + EDIT + DELETE with CSRF
 *
 * @var yii\web\View $this
 * @var common\models\LoanType[] $loanTypes
 * @var common\models\LoanType $model
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;

use common\models\LoanType;
use common\models\LoanCategory;

$addUrl     = Url::to(['/loans/loans/loantype-add']);          // JSON (ADD)
$updateUrl  = Url::to(['/loans/loans/loantype-update']);       // JSON (EDIT)
$deleteUrl  = Url::to(['/loans/loans/loantype-delete']);       // JSON (DELETE)
$refreshUrl = Url::to(['/loans/loans/categories']);             // HTML (partial renderAjax)

$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->getCsrfToken();

// Dropdown: id => categoryName
$categoryMap = ArrayHelper::map(
    LoanCategory::find()->orderBy(['categoryName' => SORT_ASC])->all(),
    'id',
    'categoryName'
);

// Ensure $model exists
if (!isset($model) || $model === null) {
    $model = new LoanType();
}
?>

<!-- TOP BAR -->
<div class="row mb-3 align-items-center no-gutters" style="margin-top:10px;">
    <div class="col-8 col-md-9 pr-1">
        <input
            type="text"
            id="loantype-filter"
            class="form-control form-control-lg"
            placeholder="Search loan types..."
            autocomplete="off"
        >
    </div>

    <div class="col-4 col-md-3 pl-1">
        <button
            type="button"
            class="btn btn-primary btn-block waves-effect"
            id="btn-add-loantype"
            data-toggle="modal"
            data-target="#loanTypeModal"
        >
            <i class="fa fa-plus mr-1"></i> Add Loan Type
        </button>
    </div>
</div>

<!-- TABLE -->
<div class="table-responsive">
    <table class="table table-striped table-hover mb-0" id="loantypes-table">
        <thead>
        <tr>
            <th style="width:70px;">#</th>
            <th>Loan Type</th>
            <th>Category</th>
            <th class="text-right">Interest</th>
            <th class="text-right">Topup</th>
            <th class="text-right">Penalty</th>
            <th class="text-right">Proc. Fee</th>
            <th class="text-right">Grace Days</th>
            <th style="width:140px;" class="text-right">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($loanTypes)): ?>
            <tr>
                <td colspan="9" class="text-center text-muted py-4">
                    No loan types found.
                </td>
            </tr>
        <?php else: ?>
            <?php $i = 1; foreach ($loanTypes as $t): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= Html::encode($t->type) ?></td>
                    <td><?= Html::encode($t->category ? $t->category->categoryName : '-') ?></td>
                    <td class="text-right"><?= Html::encode($t->interest_rate) ?></td>
                    <td class="text-right"><?= Html::encode($t->topup_rate) ?></td>
                    <td class="text-right"><?= Html::encode($t->penalty_rate) ?></td>
                    <td class="text-right"><?= Html::encode($t->processing_fee_rate) ?></td>
                    <td class="text-right"><?= Html::encode($t->penalty_grace_days) ?></td>

                    <td class="text-right">
                        <?php if(yii::$app->user->can("manage_loan_types")){?>
                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary btn-edit-loantype"
                            data-toggle="modal"
                            data-target="#loanTypeModal"
                            data-id="<?= (int)$t->id ?>"
                            data-categoryid="<?= (int)$t->categoryID ?>"
                            data-type="<?= Html::encode($t->type) ?>"
                            data-interest="<?= Html::encode($t->interest_rate) ?>"
                            data-topup="<?= Html::encode($t->topup_rate) ?>"
                            data-penalty="<?= Html::encode($t->penalty_rate) ?>"
                            data-processing="<?= Html::encode($t->processing_fee_rate) ?>"
                            data-gracedays="<?= (int)$t->penalty_grace_days ?>"
                            title="Edit"
                        >
                            <i class="fa fa-edit"></i>
                        </button>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-danger ml-1 btn-delete-loantype"
                            data-id="<?= (int)$t->id ?>"
                            title="Delete"
                        >
                            <i class="fa fa-trash"></i>
                        </button>
                        <?php } ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- MODAL -->
<div class="modal fade animated rubberBand" id="loanTypeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white" style="padding-top:2px;padding-bottom:2px">
                <span id="loanTypeModalTitle">New Loan Type</span>
                <button type="button" class="close text-white" data-dismiss="modal" style="opacity:1;">
                    <span>&times;</span>
                </button>
            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'loantype-form',
                'action' => $addUrl,
                'method' => 'post',

                // keep as-is (no frontend validation)
                'enableClientScript' => false,
                'enableClientValidation' => false,
                'enableAjaxValidation' => false,

                'fieldConfig' => [
                    'errorOptions' => ['class' => 'help-block text-danger'],
                ],
            ]); ?>

            <div class="modal-body">

                <!-- ID used for UPDATE -->
                <input type="hidden" id="loantype-id" name="id" value="">

                <!-- Category dropdown (LoanType[categoryID]) -->
                <?= $form->field($model, 'categoryID')->dropDownList(
                    $categoryMap,
                    [
                        'id' => 'loantype-category',
                        'prompt' => 'Select Category...',
                    ]
                )->label('Category') ?>

                <?= $form->field($model, 'type')->textInput([
                    'id' => 'loantype-type',
                    'placeholder' => 'Enter loan type...',
                    'autocomplete' => 'off',
                ])->label('Loan Type') ?>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'interest_rate')->textInput([
                            'id' => 'loantype-interest',
                            'placeholder' => 'e.g. 12.5',
                            'autocomplete' => 'off',
                        ])->label('Interest Rate') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'topup_rate')->textInput([
                            'id' => 'loantype-topup',
                            'placeholder' => 'e.g. 2.0',
                            'autocomplete' => 'off',
                        ])->label('Topup Rate') ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'penalty_rate')->textInput([
                            'id' => 'loantype-penalty',
                            'placeholder' => 'e.g. 5.0',
                            'autocomplete' => 'off',
                        ])->label('Penalty Rate') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'processing_fee_rate')->textInput([
                            'id' => 'loantype-processing',
                            'placeholder' => 'e.g. 1.0',
                            'autocomplete' => 'off',
                        ])->label('Processing Fee Rate') ?>
                    </div>
                </div>

                <?= $form->field($model, 'penalty_grace_days')->textInput([
                    'id' => 'loantype-gracedays',
                    'placeholder' => 'e.g. 1',
                    'autocomplete' => 'off',
                ])->label('Penalty Grace Days') ?>

                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    Close
                </button>

                <?= Html::button(
                    '<i class="fa fa-save"></i> Save',
                    ['class' => 'btn btn-primary pull-right', 'id' => 'loantype-submit', 'encode' => false, 'type' => 'button']
                ) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<?php
$this->registerCss("
    .help-block,
    .help-block-error { color:#dc3545 !important; }
    .has-error .form-control { border-color:#dc3545; }

    #toast-container.toast-center-center{
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        right: auto !important;
        bottom: auto !important;
    }
");

$this->registerJs("
(function () {

    if (window.toastr && !window.__loanTypesToastrConfigured) {
        window.__loanTypesToastrConfigured = true;
        toastr.options = {
            timeOut: 1500,
            extendedTimeOut: 1500,
            positionClass: 'toast-center-center',
            newestOnTop: true,
            closeButton: false,
            progressBar: false
        };
    }

    var addUrl     = " . json_encode($addUrl) . ";
    var updateUrl  = " . json_encode($updateUrl) . ";
    var deleteUrl  = " . json_encode($deleteUrl) . ";
    var refreshUrl = " . json_encode($refreshUrl) . ";

    var csrfParam = " . json_encode($csrfParam) . ";
    var csrfToken = " . json_encode($csrfToken) . ";

    $.ajaxSetup({ headers: { 'X-CSRF-Token': csrfToken } });

    var isSubmitting = false;

    function toastOnce(type, msg) {
        if (!window.toastr) return;
        toastr.clear();
        toastr.remove();
        toastr[type](msg);
    }

    function closeModalSafe() {
        var m = $('#loanTypeModal');
        if ($.fn.modal) {
            m.modal('hide');
        } else {
            m.removeClass('show').hide().attr('aria-hidden', 'true');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        }
    }

    function refreshLoanTypesTab() {
        // This view is typically injected into a tab pane like #loan-types
        $('#loan-types').load(refreshUrl + ' #loan-types > *');
    }

    function withCsrf(data) {
        if (typeof data === 'string') {
            if (data.indexOf(encodeURIComponent(csrfParam) + '=') === -1) {
                data += (data.length ? '&' : '') + encodeURIComponent(csrfParam) + '=' + encodeURIComponent(csrfToken);
            }
            return data;
        }
        data = data || {};
        if (data[csrfParam] === undefined) data[csrfParam] = csrfToken;
        return data;
    }

    // AJAX-loaded view: avoid duplicate bindings
    $(document).off('.loanTypes');

    // FILTER
    $(document).on('keyup.loanTypes', '#loantype-filter', function () {
        var q = ($(this).val() || '').toLowerCase().trim();
        $('#loantypes-table tbody tr').each(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(q) !== -1);
        });
    });

    // OPEN ADD MODAL
    $(document).on('click.loanTypes', '#btn-add-loantype', function () {
        $('#loanTypeModalTitle').text('New Loan Type');

        $('#loantype-id').val('');
        $('#loantype-category').val('');
        $('#loantype-type').val('');
        $('#loantype-interest').val('');
        $('#loantype-topup').val('');
        $('#loantype-penalty').val('');
        $('#loantype-processing').val('');
        $('#loantype-gracedays').val('1');

        $('#loantype-submit')
            .html('<i class=\"fa fa-save\"></i> Save')
            .data('mode', 'add');
    });

    // OPEN EDIT MODAL (prefill)
    $(document).on('click.loanTypes', '.btn-edit-loantype', function () {
        $('#loanTypeModalTitle').text('Edit Loan Type');

        $('#loantype-id').val($(this).data('id'));
        $('#loantype-category').val($(this).data('categoryid'));
        $('#loantype-type').val($(this).data('type'));
        $('#loantype-interest').val($(this).data('interest'));
        $('#loantype-topup').val($(this).data('topup'));
        $('#loantype-penalty').val($(this).data('penalty'));
        $('#loantype-processing').val($(this).data('processing'));
        $('#loantype-gracedays').val($(this).data('gracedays'));

        $('#loantype-submit')
            .html('<i class=\"fa fa-save\"></i> Update')
            .data('mode', 'edit');
    });

    // prevent normal submit
    $(document).on('submit.loanTypes', '#loantype-form', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        return false;
    });

    // SUBMIT (ADD/EDIT)
    $(document).on('click.loanTypes', '#loantype-submit', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        if (isSubmitting) return false;

        var mode = $(this).data('mode') || 'add';
        var url  = (mode === 'edit') ? updateUrl : addUrl;

        if (mode === 'edit' && !($('#loantype-id').val() || '').toString().trim()) {
            toastOnce('error', 'Missing loan type id for update');
            return false;
        }

        isSubmitting = true;
        $('#global-loader').show();
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: withCsrf($('#loantype-form').serialize()),
            success: function (res) {
                if (res && res.success == true) {
                    $('#global-loader').hide();
                    toastOnce('success', res.message || (mode === 'edit' ? 'Loan type updated' : 'Loan type added'));
                    closeModalSafe();
                    refreshLoanTypesTab();
                } else {
                    $('#global-loader').hide();
                    toastOnce('error', (res && (res.message || res.error)) ? (res.message || res.error) : 'Request failed');
                }
            },
            error: function () {
                $('#global-loader').hide();
                toastOnce('error', 'Request failed');
            },
            complete: function () {
                $('#global-loader').hide();
                isSubmitting = false;
            }
        });

        return false;
    });

    // DELETE
    $(document).on('click.loanTypes', '.btn-delete-loantype', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var id = $(this).data('id');

        if (!id) {
            toastOnce('error', 'Missing loan type id for delete');
            return false;
        }

        if (!confirm('Are you sure you want to delete this loan type?')) {
            return false;
        }

        if (isSubmitting) return false;
        isSubmitting = true;
        $('#global-loader').show();
        $.ajax({
            url: deleteUrl,
            type: 'POST',
            dataType: 'json',
            data: withCsrf({ id: id }),
            success: function (res) {
                if (res && res.success == true) {
                $('#global-loader').hide();
                    toastOnce('success', res.message || 'Loan type deleted');
                    refreshLoanTypesTab();
                } else {
                     $('#global-loader').hide();
                    toastOnce('error', (res && (res.message || res.error)) ? (res.message || res.error) : 'Delete failed');
                }
            },
            error: function (res) {
             $('#global-loader').hide();
                toastOnce('error', 'Request failed');
            },
            complete: function () {
             $('#global-loader').hide();
                isSubmitting = false;
            }
        });

        return false;
    });

})();
");
?>
