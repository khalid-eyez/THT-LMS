<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var \frontend\shareholder_module\models\InterestPay $model */
/** @var string $actionUrl */
?>
<div class="container-fluid">
<?php $form = ActiveForm::begin([
    'id' => 'interest-pay-form',
    'method' => 'post',
    'options' => ['enctype' => 'multipart/form-data'],
]); ?>

    <?= $form->field($model, 'payment_date')->input('date') ?>

    <?= $form->field($model, 'payment_doc')->fileInput([
        'accept' => '.jpg,.png,.pdf',
    ]) ?>

    <div class="form-group" style="margin-top:10px;">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save Record', ['class' => 'btn btn-primary pull-right']) ?>
    </div>

<?php ActiveForm::end(); ?>
</div>
