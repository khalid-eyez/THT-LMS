<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Targets */

$this->title = 'Update Targets';
$this->params['pageTitle'] = 'Update Target';
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