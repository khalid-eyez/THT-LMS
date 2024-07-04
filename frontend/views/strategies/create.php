<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Strategies */

$this->title = 'Create Strategies';
$this->params['pageTitle'] = "Add New Strategy";
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
    $('.monitor').addClass('active');
JS;
$this->registerJs($script);
?>