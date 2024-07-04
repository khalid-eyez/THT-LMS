<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\CBudgetItem;
use common\models\Objectives;
use yii\helpers\ArrayHelper;
use common\models\Budgetitem;
$model=new CBudgetItem();
$objectives=ArrayHelper::map(Objectives::find()->all(),'objID','description');
$items=ArrayHelper::map(Budgetitem::find()->all(),'name','label');
?>
<div class="modal fade" id="budgetitemmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
     <div class="modal-header bg-success pl-4 p-1"><div class="modal-title ml-1"><i class='fa fa-plus-circle'></i> New Budget Item</div></div>
      <div class="modal-body pl-4 pr-4">

            <?php $form = ActiveForm::begin(['method'=>'post','action'=>'/finance/new-center-item?center='.$_GET['center']]); ?>
            <?= $form->field($model, 'objective')->dropDownList($objectives,['prompt' =>"--Activity--"])->label(false) ?>
            <?= $form->field($model, 'budgetItem')->dropDownList($items,['prompt' =>"--Budget Item--"])->label(false) ?>
            <?= $form->field($model, 'projected_amount')->textInput(['placeholder' =>"Projected Amount (TZS)"])->label(false) ?>

            <div class="form-group">
                <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn col-sm-3 btn-success btn-sm float-right']) ?>
            </div>

            <?php ActiveForm::end(); ?>


</div>
</div>
</div>
</div>
