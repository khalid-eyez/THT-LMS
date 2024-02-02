<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form ActiveForm */
$this->params['pageTitle'] ="Dashboard"; 
?>
<div class="container-fluid d-flex justify-content-center">
     <div class="container-fluid m-5 justify-content-center">
        <img class="m-5" src="/img/logo.png" style="width:150px;height:150px;margin-left:20%"></img>
        <span class="text-lg text-success text-bold">

        Welcome to THTU Management Information System
</span>
     </div>
</div>

<?php
$script = <<<JS
    $('document').ready(function(){

    $('.dashboard').addClass("active");
})
JS;
$this->registerJs($script);
?>

