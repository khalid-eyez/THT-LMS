<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Targets */

$this->params['pageTitle']= "View Target";
\yii\web\YiiAsset::register($this);
?>
<div class="targets-view">

    <p>
        <?= Html::a('<i class="fa fa-edit"></i> Update', ['update', 'id' => $model->targetID], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete', 'id' => $model->targetID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'code',
            'description:ntext',
            [
                'attribute'=>'strategy',
                'value'=>function($a)
                {
                    return $a->strategy0->description;
                }
            ],
        ],
    ]) ?>

</div>
<?php
$script = <<<JS
    $('.monitor').addClass('active');
JS;
$this->registerJs($script);
?>