<?php

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\panels\Panel;
use dosamigos\chartjs\ChartJs;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('audit', 'Audit Module');
$this->params['pageTitle']= $this->title;

$this->registerCss('canvas {width: 100% !important;height: 400px;}');
?>
<div class="audit-index">

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <h2><?php echo Html::a(Yii::t('audit', 'Entries'), ['/audit/entry/index']); ?></h2>

            <div class="well">
                <?php

                echo ChartJs::widget([
                    'type' => 'line',
                    'options' => [
                        'height' => '220',
                        
                    ],
                    'clientOptions' => [
                        'legend' => ['display' => false],
                        'tooltips' => ['enabled' => false],
                      
                    ],
                    'data' => [
                        'labels' => array_keys($chartData),
                        'datasets' => [
                            [
                                'backgroundColor'=>'rgba(167, 199, 250,.2)',
                                'borderColor'=>'rgba(4, 52, 128,.3)',
                                'fillColor' => 'rgba(167, 199, 250,0.5)',
                                'strokeColor' => 'rgba(167, 199, 250,1)',
                                'pointColor' => 'rgba(151,187,205,1)',
                                'pointStrokeColor' => '#fff',
                                'data' => array_values($chartData),
                            ],
                        ],
                    ]
                ]);
                ?>
            </div>
        </div>

        <?php
        foreach (Audit::getInstance()->panels as $panel) {
            /** @var Panel $panel */
            $chart = $panel->getChart();
            if (!$chart) {
                continue;
            }
            $indexUrl = $panel->getIndexUrl();
            $indexUrl[0]="/admin/audit/".$indexUrl[0];
            ?>
            <div class="col-md-3 col-lg-3">
                <h2><?php echo $indexUrl ? Html::a($panel->getName(), $indexUrl) : $panel->getName(); ?></h2>

                <div class="well">
                    <?php echo $chart; ?>
                </div>
            </div>
        <?php } ?>

    </div>

</div>
