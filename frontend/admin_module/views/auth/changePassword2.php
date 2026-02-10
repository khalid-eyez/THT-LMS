<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
?>
 <div class="wizard-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="wizard-wrap-int">

    <div class="card shadow-sm">
        <div class="card-body p-5" style="padding:50px; padding-top:25px">
    <div style="background-color:rgba(4, 4, 164, 0.1); padding:10px; margin-bottom:10px;">
  <h5 class="text-primary mb-0">
    <i class="fa fa-key"></i> Change Password
  </h5>
</div>

    <?php $form = ActiveForm::begin(); ?>
        
            
                                        <?= $form->field($model, 'current_password')->passwordInput(['class'=>'form-control form-control-sm', 'placeholder'=>'Current Password'])->label(false) ?>
                                   
                                        <?= $form->field($model, 'new_password')->passwordInput(['class'=>'form-control form-control-sm', 'placeholder'=>'New Password'])->label(false) ?>
                                   
                                        <?= $form->field($model, 'confirm_new_password')->passwordInput(['class'=>'form-control form-control-sm', 'placeholder'=>'Confirm Password'])->label(false) ?>
                                   
                                    
                                    
                                        <?= Html::submitButton(Yii::t('app', '<i class="fa fa-save"></i> Save Changes'), ['class' => 'btn btn-primary btn-md pull-right']) ?>
                                   
                      
               
    
    <?php ActiveForm::end(); ?>

</div><!-- changePassword -->
</div></div></div></div></div></div>
