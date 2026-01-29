<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Customer $model */
/** @var yii\widgets\ActiveForm $form */

$updateUrl = Url::to(['/loans/customer/update', 'id' => $model->id]);
$formId = 'customer-update-form';
?>

<div class="customer-form" style="padding-bottom:40px">

    <?php $form = ActiveForm::begin([
        'id' => $formId,
        'action' => $updateUrl,
        'method' => 'post',
        'enableClientScript' => false,
        'enableClientValidation' => false,
        'enableAjaxValidation' => false,
    ]); ?>

    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

    <!-- Row: Birthdate + Gender + Status -->
    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'birthDate')->textInput([
                'id' => 'birthdate',
                'class' => 'form-control',
                'autocomplete' => 'off',
                'placeholder' => 'YYYY-MM-DD',
            ]) ?>
        </div>

        <div class="col-sm-4">
            <?= $form->field($model, 'gender')->dropDownList([
                'Female' => 'Female',
                'Male'   => 'Male',
            ], ['prompt' => 'Select gender']) ?>
        </div>

        <div class="col-sm-4">
            <?= $form->field($model, 'status')->dropDownList([
                'active'   => 'Active',
                'inactive' => 'Inactive',
            ], ['prompt' => 'Select status']) ?>
        </div>
    </div>

    <!-- Row: Address + Contacts -->
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'address')->textInput() ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'contacts')->textInput() ?>
        </div>
    </div>

    <!-- Row: NIN + TIN -->
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'NIN')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'TIN')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::submitButton(
            '<i class="fa fa-save"></i> Save Changes',
            [
                'class' => 'btn btn-primary',
                'encode' => false,
                'id' => 'customer-save-btn',
            ]
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs(<<<JS
(function () {

    /* ------------------------------
       DATE INPUT / DATEPICKER
    --------------------------------*/
    var \$birth = $('#birthdate');
    if (\$birth.length) {
        try {
            var test = document.createElement('input');
            test.setAttribute('type', 'date');

            if (test.type === 'date') {
                \$birth.attr('type', 'date');
            } else if (\$.fn.datepicker && !\$birth.data('__hasDatepicker')) {
                \$birth.datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true,
                    yearRange: 'c-100:c+0'
                });
                \$birth.data('__hasDatepicker', true);
            }
        } catch (e) {}
    }

    /* ------------------------------
       AJAX SUBMIT (BIND ONCE)
    --------------------------------*/
    if (!window.__customerUpdateAjaxBound) {
        window.__customerUpdateAjaxBound = true;

        \$(document).on('submit.customerUpdateAjax', '#$formId', function (e) {
            e.preventDefault();

            var \$form = \$(this);
            var url = '$updateUrl';
            var \$btn = \$('#customer-save-btn');

            \$btn.prop('disabled', true);
            if (\$('#global-loader').length) \$('#global-loader').show();

            \$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: \$form.serialize(),

                success: function (res) {
                    if (window.toastr) {
                        toastr.clear();
                        toastr.remove();
                    }

                    if (res && (res.success === true || res.success === 'true' || res.success == 1)) {
                        if (window.toastr) {
                            toastr.success(res.message || 'Saved successfully');
                        } else {
                            alert(res.message || 'Saved successfully');
                        }

                        if (\$('#customerUpdateModal').length && \$.fn.modal) {
                            \$('#customerUpdateModal').modal('hide');
                        }

                        window.location.reload();
                    } else {
                        var msg = (res && (res.message || res.error)) ? (res.message || res.error) : 'Save failed';
                        if (window.toastr) {
                            toastr.error(msg);
                        } else {
                            alert(msg);
                        }
                    }
                },

                error: function (xhr) {
                    if (window.toastr) {
                        toastr.clear();
                        toastr.remove();
                    }

                    var ct = xhr.getResponseHeader ? (xhr.getResponseHeader('content-type') || '') : '';
                    if (ct.indexOf('text/html') !== -1 && xhr.responseText) {
                        if (\$('#customerUpdateModalBody').length) {
                            \$('#customerUpdateModalBody').html(xhr.responseText);
                        } else {
                            \$form.closest('.customer-form').html(xhr.responseText);
                        }
                        return;
                    }

                    if (window.toastr) {
                        toastr.error('Request failed');
                    } else {
                        alert('Request failed');
                    }
                },

                complete: function () {
                    if (\$('#global-loader').length) \$('#global-loader').hide();
                    \$btn.prop('disabled', false);
                }
            });

            return false;
        });
    }

})();
JS
);
?>
