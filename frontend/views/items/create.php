<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Budgetitem */

$this->title = 'Create Budget Item';
$this->params['pageTitle']= "Add Budget Item";
?>
<div class="container p-5">
<div class="container p-5 shadow">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
<?php
$script = <<<JS
    $('.bitems').addClass('active');
JS;
$this->registerJs($script);
?>