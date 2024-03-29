<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Meeting */
/* @var $form yii\widgets\ActiveForm */
$months=[
    'January'=>'January',
    'February'=>'February',
    'March'=>'March',
    'April'=>'April',
    'May'=>'May',
    'June'=>'June',
    'July'=>'July',
    'August'=>'August',
    'September'=>'September',
    'October'=>'October',
    'November'=>'November',
    'December'=>'December'
    ]
?>
<div class="income-form">

    <?php $form = ActiveForm::begin(['method'=>'post','action'=>'/finance/other-income']); ?>
    <?= $form->field($model, 'incomeType')->textInput(['placeholder' =>"Source Type"])->label(false) ?>
    <?= $form->field($model, 'amount')->textInput(['placeholder' =>"Received Amount (TZS)"])->label(false) ?>
    <?= $form->field($model, 'month')->dropDownList($months,['prompt' =>"--Month--"])->label(false) ?>
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
