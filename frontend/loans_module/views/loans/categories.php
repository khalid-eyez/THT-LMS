<?php
/**
 * Loan Categories list (Bootstrap 4) – AJAX add + EDIT + DELETE with CSRF fix
 *
 * @var yii\web\View $this
 * @var common\models\LoanCategory[] $categories
 * @var common\models\LoanCategory $model
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;

$addCategoryUrl = Url::to(['/loans/loans/add-category']);           // JSON (ADD)
$refreshUrl     = Url::to(['/loans/loans/categories']);            // HTML (partial)
$updateUrl      = Url::to(['/loans/loans/category-update']);       // JSON (EDIT)
$deleteUrl      = Url::to(['/loans/loans/category-delete']);       // JSON (DELETE)

// CSRF token for AJAX POST (fixes 400 Unable to verify your data submission)
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->getCsrfToken();
?>

<!-- TOP BAR -->
<div class="row mb-3 align-items-center no-gutters" style="margin-top:10px;">
    <div class="col-8 col-md-9 pr-1">
        <input
            type="text"
            id="category-filter"
            class="form-control form-control-lg"
            placeholder="Search categories..."
            autocomplete="off"
        >
    </div>

    <div class="col-4 col-md-3 pl-1">
        <button
            type="button"
            class="btn btn-primary btn-block waves-effect"
            id="btn-add-category"
            data-toggle="modal"
            data-target="#categoryModal"
        >
            <i class="fa fa-plus mr-1"></i> Add Category
        </button>
    </div>
</div>

<!-- TABLE -->
<div class="table-responsive">
    <table class="table table-striped table-hover mb-0" id="categories-table">
        <thead>
        <tr>
            <th style="width:70px;">#</th>
            <th>Category Name</th>
            <th style="width:140px;" class="text-right">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($categories)): ?>
            <tr>
                <td colspan="3" class="text-center text-muted py-4">
                    No categories found.
                </td>
            </tr>
        <?php else: ?>
            <?php $i = 1; foreach ($categories as $cat): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= Html::encode($cat->categoryName) ?></td>
                    <td class="text-right">
                         <?php if(yii::$app->user->can("manage_loan_categories")){?>
                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary btn-edit-category"
                            data-toggle="modal"
                            data-target="#categoryModal"
                            data-id="<?= (int)$cat->id ?>"
                            data-name="<?= Html::encode($cat->categoryName) ?>"
                            title="Edit"
                        >
                            <i class="fa fa-edit"></i>
                        </button>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-danger ml-1 btn-delete-category"
                            data-id="<?= (int)$cat->id ?>"
                            title="Delete"
                        >
                            <i class="fa fa-trash"></i>
                        </button>
                        <?php }?>

                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- MODAL -->
<div class="modal fade animated rubberBand" id="categoryModal" tabindex="-1" role="dialog" style="z-index: index 99999;!important">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white" style="padding-top:2px;padding-bottom:2px">
                <span id="categoryModalTitle">New Category</span>
                <button type="button" class="close text-white" data-dismiss="modal" style="opacity:1;">
                    <span>&times;</span>
                </button>
            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'category-form',
                'action' => $addCategoryUrl,
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
                <input type="hidden" id="category-id" name="id" value="">

                <!-- Keep ActiveForm naming (LoanCategory[categoryName]) -->
                <?= $form->field($model, 'categoryName')
                    ->textInput([
                        'id' => 'category-name',
                        'placeholder' => 'Enter category name...',
                        'autocomplete' => 'off',
                    ])
                    ->label(false) ?>

                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    Close
                </button>

                <?= Html::button(
                    '<i class="fa fa-save"></i> Save',
                    ['class' => 'btn btn-primary pull-right', 'id' => 'category-submit', 'encode' => false, 'type' => 'button']
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

    if (window.toastr && !window.__loanCatsToastrConfigured) {
        window.__loanCatsToastrConfigured = true;
        toastr.options = {
            timeOut: 1500,
            extendedTimeOut: 1500,
            positionClass: 'toast-center-center',
            newestOnTop: true,
            closeButton: false,
            progressBar: false
        };
    }

    var addUrl     = " . json_encode($addCategoryUrl) . ";
    var updateUrl  = " . json_encode($updateUrl) . ";
    var deleteUrl  = " . json_encode($deleteUrl) . ";
    var refreshUrl = " . json_encode($refreshUrl) . ";

    // CSRF (fix for 400 Unable to verify your data submission)
    var csrfParam = " . json_encode($csrfParam) . ";
    var csrfToken = " . json_encode($csrfToken) . ";

    // Set CSRF header + param for ALL ajax requests
    $.ajaxSetup({
        headers: { 'X-CSRF-Token': csrfToken }
    });

    var isSubmitting = false;

    function toastOnce(type, msg) {
        if (!window.toastr) return;
        toastr.clear();
        toastr.remove();
        toastr[type](msg);
    }

    function closeModalSafe() {
        var m = $('#categoryModal');

        if ($.fn.modal) {
            m.modal('hide');
        } else {
            m.removeClass('show').hide().attr('aria-hidden', 'true');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        }
    }

    function refreshCategoriesTab() {
        $('#loan-categories').load(refreshUrl + ' #loan-categories > *');
    }

    // AJAX-loaded view: avoid duplicate bindings
    $(document).off('.loanCats');

    // FILTER
    $(document).on('keyup.loanCats', '#category-filter', function () {
        var q = ($(this).val() || '').toLowerCase().trim();
        $('#categories-table tbody tr').each(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(q) !== -1);
        });
    });

    // ADD modal
    $(document).on('click.loanCats', '#btn-add-category', function () {
        $('#categoryModalTitle').text('New Category');
        $('#category-id').val('');
        $('#category-name').val('');
        $('#category-submit')
            .html('<i class=\"fa fa-save\"></i> Save')
            .data('mode', 'add');
    });

    // EDIT modal prefill
    $(document).on('click.loanCats', '.btn-edit-category', function () {
        $('#categoryModalTitle').text('Edit Category');
        $('#category-id').val($(this).data('id'));
        $('#category-name').val($(this).data('name'));
        $('#category-submit')
            .html('<i class=\"fa fa-save\"></i> Update')
            .data('mode', 'edit');
    });

    // prevent normal submit
    $(document).on('submit.loanCats', '#category-form', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        return false;
    });

    // helper: add csrf param to serialized data if needed
    function withCsrf(data) {
        // If data is querystring, append
        if (typeof data === 'string') {
            if (data.indexOf(encodeURIComponent(csrfParam) + '=') === -1) {
                data += (data.length ? '&' : '') + encodeURIComponent(csrfParam) + '=' + encodeURIComponent(csrfToken);
            }
            return data;
        }
        // If data is object, add field
        data = data || {};
        if (data[csrfParam] === undefined) data[csrfParam] = csrfToken;
        return data;
    }

    // SUBMIT (ADD uses addUrl, EDIT uses updateUrl)
    $(document).on('click.loanCats', '#category-submit', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        if (isSubmitting) return false;

        var mode = $(this).data('mode') || 'add';
        var url = (mode === 'edit') ? updateUrl : addUrl;

        if (mode === 'edit' && !($('#category-id').val() || '').toString().trim()) {
            toastOnce('error', 'Missing category id for update');
            return false;
        }

        isSubmitting = true;
        $('#global-loader').show();

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: withCsrf($('#category-form').serialize()),
            success: function (res) {
                if (res && res.success == true) {
                    $('#global-loader').hide();
                    toastOnce('success', res.message || (mode === 'edit' ? 'Category updated' : 'Category added'));
                    closeModalSafe();
                    refreshCategoriesTab();
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

    // DELETE (AJAX) -> /loans/loans/category-delete (POST with id + CSRF)
    $(document).on('click.loanCats', '.btn-delete-category', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var id = $(this).data('id');

        if (!id) {
            toastOnce('error', 'Missing category id for delete');
            return false;
        }

        if (!confirm('Are you sure you want to delete this category?')) {
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
                    toastOnce('success', res.message || 'Category deleted');
                    refreshCategoriesTab();
                } else {
                    $('#global-loader').hide();
                    toastOnce('error', (res && (res.message || res.error)) ? (res.message || res.error) : 'Delete failed');
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

})();
");
?>
