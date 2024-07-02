<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use practically\chartjs\Chart;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form ActiveForm */
$this->params['pageTitle'] ="Dashboard"; 
?>
<div class="container-fluid d-flex justify-content-center">
     
 <?=Chart::widget([
    'type' => 'horizontalBar',
    'options' => [
      //'indexAxis'=>'y',
],
    'datasets' => [
        [
            'data' => [
                'Label 1' => 15,
                'Label 2' => 20,
                'Label 3' => 30
            ]
        ]
    ]
]);
?>
</div>

<?php
$script = <<<JS
    $('document').ready(function(){

    $('.dashboard').addClass("active");
})
JS;
$this->registerJs($script);
?>

