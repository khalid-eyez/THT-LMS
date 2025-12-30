<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\YourModel */

$form = ActiveForm::begin([
    'id' => 'loan-form',
    'options' => ['class' => 'needs-validation'],
    'layout' => 'horizontal', // optional Bootstrap 4 horizontal form
]); 
?>

<div class="form-group row">
    <?= Html::activeLabel($model, 'full_name', ['class' => 'col-sm-2 col-form-label']) ?>
    <div class="col-sm-10">
        <?= $form->field($model, 'full_name')->textInput()->label(false) ?>
    </div>
</div>

<div class="form-group row">
    <?= Html::activeLabel($model, 'birthDate', ['class' => 'col-sm-2 col-form-label']) ?>
    <div class="col-sm-10">
        <?= $form->field($model, 'birthDate')->input('date')->label(false) ?>
    </div>
</div>

<div class="form-group row">
    <?= Html::activeLabel($model, 'gender', ['class' => 'col-sm-2 col-form-label']) ?>
    <div class="col-sm-10">
        <?= $form->field($model, 'gender')->dropDownList([
            'Male' => 'Male',
            'Female' => 'Female',
            'Other' => 'Other'
        ])->label(false) ?>
    </div>
</div>

<h5>Address</h5>
<?php foreach ($model->address ?? ['street' => '', 'city' => '', 'region' => ''] as $key => $value): ?>
    <div class="form-group row">
        <?= Html::activeLabel($model, "address[$key]", ['class' => 'col-sm-2 col-form-label']) ?>
        <div class="col-sm-10">
            <?= $form->field($model, "address[$key]")->textInput(['value' => $value])->label(false) ?>
        </div>
    </div>
<?php endforeach; ?>

<h5>Emails</h5>
<?php 
$emails = $model->emails ?? ['','']; // at least 1 or 2
foreach ($emails as $i => $email): ?>
    <div class="form-group row">
        <?= Html::activeLabel($model, "emails[$i]", ['class' => 'col-sm-2 col-form-label']) ?>
        <div class="col-sm-10">
            <?= $form->field($model, "emails[$i]")->textInput(['value' => $email])->label(false) ?>
        </div>
    </div>
<?php endforeach; ?>

<h5>Phones</h5>
<?php 
$phones = $model->phones ?? ['','']; // at least 1 or 2
foreach ($phones as $i => $phone): ?>
    <div class="form-group row">
        <?= Html::activeLabel($model, "phones[$i]", ['class' => 'col-sm-2 col-form-label']) ?>
        <div class="col-sm-10">
            <?= $form->field($model, "phones[$i]")->textInput(['value' => $phone])->label(false) ?>
        </div>
    </div>
<?php endforeach; ?>

<div class="form-group row">
    <?= Html::activeLabel($model, 'NIN', ['class' => 'col-sm-2 col-form-label']) ?>
    <div class="col-sm-10">
        <?= $form->field($model, 'NIN')->textInput()->label(false) ?>
    </div>
</div>

<div class="form-group row">
    <?= Html::activeLabel($model, 'TIN', ['class' => 'col-sm-2 col-form-label']) ?>
    <div class="col-sm-10">
        <?= $form->field($model, 'TIN')->textInput()->label(false) ?>
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-10 offset-sm-2">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>