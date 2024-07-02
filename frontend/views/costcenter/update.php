<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Costcenter */

$this->title = 'Update Costcenter: ' . $model->name;
$this->params['pageTitle'] = 'Update Cost Center';
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
    $('.costcenter').addClass('active');
JS;
$this->registerJs($script);
?>