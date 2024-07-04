<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Budget items';
$this->params['pageTitle'] = "Budget Items";
?>
<div class="budgetitem-index">

    <p>
        <?= Html::a('<i class="fa fa-plus-circle"></i> Add Budget Item', ['create'], ['class' => 'btn btn-success float-right mb-3']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'code',
                'label'=>'OFS Code',
                'value'=>function($model)
                {
                    return $model->code;
                }
            ],
            'name',
            [
                'label'=>'Projected'
            ],
            [
                'label'=>'Allocated'
            ],
            [
                'label'=>'Expenses'
            ],
            [
                'label'=>'Balance'
            ],
           

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
<?php
$script = <<<JS
    $('.bitems').addClass('active');
JS;
$this->registerJs($script);
?>