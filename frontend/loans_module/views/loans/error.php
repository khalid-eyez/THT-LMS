<?php
use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var \yii\web\HttpException $exception */
?>
<center>
 <div class="wizard-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="wizard-wrap-int">
 <h2 class="text-warning">Error</h2>

    <div class="alert alert-danger">
        <b><?= nl2br(Html::encode($message)) ?></b>
    </div>

    <p class="text-center">
        
        <i class="fa fa-exclamation-triangle fa-3x text-warning"></i>  

    </p>
</div></div></div></div></div>
</center>