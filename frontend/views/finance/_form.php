<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Meeting */
/* @var $form yii\widgets\ActiveForm */
$months=[
    '1'=>'January',
    '2'=>'February',
    '3'=>'March',
    '4'=>'April',
    '5'=>'May',
    '6'=>'June',
    '7'=>'July',
    '8'=>'August',
    '9'=>'September',
    '10'=>'October',
    '11'=>'November',
    '12'=>'December'
    ]
?>
<div class="income-form">

    <?php $form = ActiveForm::begin(['method'=>'post','action'=>'/finance/new-income']); ?>

    <?= $form->field($model, 'receivedAmount')->textInput(['placeholder' =>"Received Amount (TZS)"])->label(false) ?>
    <?= $form->field($model, 'month')->dropDownList($months,['prompt' =>"--Month--"])->label(false) ?>
    <div class="container-fluid pt-0 mb-2 border border-default"><div class="row bg-success mb-2"><div class="col-sm-12">Special Contribution</div></div>
    <?= $form->field($model, 'contributionType')->textInput(['placeholder' =>"Contribution Type"])->label(false) ?>
    <?= $form->field($model, 'individualAmount')->textInput(['placeholder' =>"Contribution Factor (Amount/Member)"])->label(false) ?>
    </div>
   
    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn col-sm-3 btn-success btn-sm float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = <<<JS
    $('document').ready(function(){
})
JS;
$this->registerJs($script);
?>
