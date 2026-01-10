<?php
use yii\bootstrap4\Breadcrumbs;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use common\widgets\Alert;


AppAsset::register($this);



/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form ActiveForm */


?>


    <?php $form = ActiveForm::begin(["action"=>"/admin/auth/change-password-restrict"]); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3">
                </div>
                <div class="col-sm-6">
                    <div class="card shadow-lg" style="">
                        <div class="card-header text-center bg-primary pt-1 pb-1 text-md">
                            <i class="fa fa-lock"></i> Change Password
                        </div>
                        <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?= $form->field($model, 'current_password')->passwordInput(['class'=>'form-control ', 'placeholder'=>'Current Password'])->label(false) ?>
                                    </div>

                                    <div class="col-sm-12">
                                        <?= $form->field($model, 'new_password')->passwordInput(['class'=>'form-control ', 'placeholder'=>'New Password'])->label(false) ?>
                                    </div>

                                    <div class="col-sm-12">
                                        <?= $form->field($model, 'confirm_new_password')->passwordInput(['class'=>'form-control ', 'placeholder'=>'Confirm Password'])->label(false) ?>
                                    </div>
                                    
                                    <div class="col-sm-12">
                                        <?= Html::submitButton(Yii::t('app', '<i class="fa fa-paper-plane"></i> Submit'), ['class' => 'btn btn-primary btn-md float-right']) ?>
                                       
                                    </div>
                                </div>    
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                </div>
            </div>
           
        </div>
    
    <?php ActiveForm::end(); ?>

<!-- changePassword -->
