<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Member */

$this->title = "Member View";
$this->params['pageTitle']="Member View";
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="container text-sm">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'IndividualNumber',
            'fname',
            'mname',
            'lname',
            'email:email',
            'phone',
            'gender',
            [                      
                'label' => 'Branch',
                'value' => $model->branch0->branch_short
            ],
        ],
    ]) ?>

</div>
<?php
$script = <<<JS
    $('document').ready(function(){
    
    $('.members').addClass("active");
})
JS;
$this->registerJs($script);
?>
