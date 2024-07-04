<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Budgetitem */

$this->title = $model->name;
$this->params['pageTitle']= "View Budget Item";
\yii\web\YiiAsset::register($this);
?>
<div class="budgetitem-view p-2">

    <p>
        <?= Html::a('<i class="fa fa-edit"></i> Update', ['update', 'id' => $model->name], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete', 'id' => $model->name], [
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
            'name',
            'code',
            'createdAt',
            'updatedAt',
        ],
    ]) ?>

</div>
<?php
$script = <<<JS
    $('.bitems').addClass('active');
JS;
$this->registerJs($script);
?>