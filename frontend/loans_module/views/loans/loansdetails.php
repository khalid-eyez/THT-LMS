<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Loan */

$form = ActiveForm::begin([
    'id' => 'loan-form',
    'options' => ['class' => 'needs-validation'],
    'layout' => 'horizontal', // optional Bootstrap 4 horizontal form
]); 
$loanTypes=['kula','kunywa'];
?>

<div class="form-group row">
    <?= Html::activeLabel($model, 'loan_type_ID', ['class' => 'col-sm-2 col-form-label']) ?>
    <div class="col-sm-10">
        <?= $form->field($model, 'loan_type_ID')->dropDownList(
            $loanTypes, // array of loan types [id => name]
            ['prompt' => 'Select loan type']
        )->label(false) ?>
    </div>
</div>

<div class="form-group row">
    <?= Html::activeLabel($model, 'amount', ['class' => 'col-sm-2 col-form-label']) ?>
    <div class="col-sm-10">
        <?= $form->field($model, 'amount')->input('number', ['step' => '0.01'])->label(false) ?>
    </div>
</div>

<div class="form-group row">
    <?= Html::activeLabel($model, 'repayment_frequency', ['class' => 'col-sm-2 col-form-label']) ?>
    <div class="col-sm-10">
        <?= $form->field($model, 'repayment_frequency')->dropDownList([
            'Daily' => 'Daily',
            'Weekly' => 'Weekly',
            'Monthly' => 'Monthly',
            'Yearly' => 'Yearly',
        ])->label(false) ?>
    </div>
</div>

<div class="form-group row">
    <?= Html::activeLabel($model, 'loan_duration_units', ['class' => 'col-sm-2 col-form-label']) ?>
    <div class="col-sm-10">
        <?= $form->field($model, 'loan_duration_units')->input('number')->label(false) ?>
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-10 offset-sm-2">
        <?= Html::submitButton('Submit Loan', ['class' => 'btn btn-primary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>