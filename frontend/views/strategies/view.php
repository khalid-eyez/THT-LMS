<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Strategies */

$this->params['pageTitle'] = "View Strategy";
\yii\web\YiiAsset::register($this);
?>
<div class="strategies-view">

    <p>
        <?= Html::a('<i class="fa fa-edit"></i> Update', ['update', 'id' => $model->strID], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete', 'id' => $model->strID], [
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
                'attribute'=>'goal',
                'value'=>function($a)
                {
                    return $a->goal0->description;
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