<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Payabletransactions;

$model=new Payabletransactions;
?>
<div class="modal fade" id="paymentmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
     <div class="modal-header bg-success pl-4 p-1"><div class="modal-title ml-1"><i class='fa fa-plus-circle'></i> New Payment</div></div>
      <div class="modal-body pl-4 pr-4">

            <?php $form = ActiveForm::begin(['method'=>'post']);
       
             ?>

            <?= $form->field($model, 'quantity')->textInput(['placeholder' =>"Quantity | No. of units"])->label(false) ?>
        
            <div class="form-group">
                <?= Html::submitButton('<i class="fa fa-save"></i> Pay & Save', ['class' => 'btn col-sm-3 btn-success btn-sm float-right']) ?>
            </div>

            <?php ActiveForm::end(); ?>


</div>
</div>
</div>
</div>
