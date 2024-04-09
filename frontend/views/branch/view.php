<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Branch */

$this->title = $model->branch_short;
\yii\web\YiiAsset::register($this);
$this->params['pageTitle']="Branch Details";
?>
<div class="branch-view">

    <?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete', 'id' => $model->branchID], [
            'class' => 'btn btn-danger float-right mb-2',
            'data' => [
                'confirm' => 'Are you sure you want to delete this branch? All Data related to this branch will also be deleted permanently !',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('<i class="fa fa-edit "></i> Update', ['update', 'id' => $model->branchID], ['class' => 'btn btn-primary float-right mr-1 mb-2']) ?>
    
  

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'branchName',
            'branch_short',
            'location',
            'email:email',
            'telphone',
            'fax',
            'website',
            'pobox',
        ],
    ]) ?>

</div>
<?php
$script = <<<JS
    $('.branches').addClass('active');
   
JS;
$this->registerJs($script);
?>