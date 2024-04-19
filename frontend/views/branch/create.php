<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Branch */

?>
<div class="modal fade" id="branchmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
     <div class="modal-header bg-success pl-4 p-1"><div class="modal-title ml-1"><i class='fa fa-plus'></i> New Branch</div></div>
      <div class="modal-body pl-4 pr-4">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
    </div>
</div></div>
<?php
$script = <<<JS
    $('.branches').addClass('active');
   
JS;
$this->registerJs($script);
?>