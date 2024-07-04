<?php

use common\models\Objectives;
use common\models\Strategies;
use common\models\Targets;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use practically\chartjs\Chart;
use common\models\Goals;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form ActiveForm */
$this->params['pageTitle'] ="Dashboard"; 
?>
<div class="container-fluid d-flex justify-content-center">
     <?php
         $goals=Goals::find()->all();
         $goals=ArrayHelper::map($goals,'description','completionstatus');
        // print(Goals::findOne(7)->getCompletionstatus()); return null;
     ?>
     <div class="container">
        <div class="row"><div class="col-sm-7">
 <?=Chart::widget([
    'type' => 'horizontalBar',
    'options' => [
      //'indexAxis'=>'y',
],
    'datasets' => [
        [
            'data' => $goals
        ]
    ]
]);
?>
</div>
<div class="col-sm-5 pt-3">
<?=Chart::widget([
    'type' => 'doughnut',
    'options' => [
      //'indexAxis'=>'y',
],
    'datasets' => [
        [
            'data' => [
                'AVG completion status'=>(new Goals())->getAVGCompletionStatus(),
                'Underway'=>100-((new Goals())->getAVGCompletionStatus())
            ]
        ]
    ]
]);
?>
</div>
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

