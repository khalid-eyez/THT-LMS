<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
 <div class="wizard-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="wizard-wrap-int">
<div class="loan-search container-fluid p-0">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row no-gutters w-100">
        <!-- INPUT -->
        <div class="col-10 col-md-11">
            <?= Html::activeTextInput(
                $model,
                'keyword',
                [
                    'class' => 'form-control form-control-lg w-100',
                    'placeholder' => 'Search by Loan ID, Customer Name, or NIN',
                    'autocomplete' => 'off',
                ]
            ) ?>
        </div>

        <!-- BUTTON -->
        <div class="col-2 col-md-1">
            <?= Html::submitButton(
                '<i class="fa fa-search"></i>',
                [
                    'class' => 'btn btn-primary w-100',
                    'title' => 'Search',
                ]
            ) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<div class="results">

</div>
</div>
</div></div></div></div>
<?php
$this->registerJs(<<<JS
$(document).on('submit', '.loan-search form', function (e) {
    e.preventDefault();

    var form = $(this);
    var results = $('.results');

    $.ajax({
        url: '/loans/loans/loan-search-two',
        type: 'POST',
        data: form.serialize(),

        beforeSend: function () {
          $('#global-loader').show();
        },

        success: function (response) {
            results.html(response);
        },

        error: function () {
            results.html(
                '<div class="alert alert-danger">' +
                    'An error occurred while searching. Please try again.' +
                '</div>'
            );
        },

        complete: function () {
            $('#global-loader').hide();
        }
    });
});
JS
);
?>
