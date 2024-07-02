<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Costcenter */

$this->title = $model->name;
$this->params['pageTitle']= "View Cost Center";
\yii\web\YiiAsset::register($this);
?>
<div class="costcenter-view">


    <p>
        <?= Html::a('<i class="fa fa-edit"></i> Update', ['update', 'id' => $model->centerID], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete', 'id' => $model->centerID], [
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
            [
              'attribute'=>'authority' ,
              'value'=>$model->getAuthority() 
            ]
            
        ],
    ]) ?>

</div>
<?php
$script = <<<JS
    $('.costcenter').addClass('active');
JS;
$this->registerJs($script);
?>