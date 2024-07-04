<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Goals */

$this->title = 'Update Goal';
$this->params['pageTitle']="Update Goal";
?>
<div class="container p-5">
<div class="container pr-5 pl-5">
    <?= $this->render('update_form', [
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