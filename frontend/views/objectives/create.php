<?php

use yii\helpers\Html;
$this->params['pageTitle']="Add New Objective";
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