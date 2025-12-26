<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
$this->params['pageTitle']="Error";
?>
<div class="site-error">

    <h2 class="text-warning"><?= Html::encode($this->title) ?></h2>

    <div class="alert alert-danger" style="opacity:0.6">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p class="text-center">
        
        <i class="far fa-frown fa-8x text-warning"></i>  

    </p> 
  

</div>
