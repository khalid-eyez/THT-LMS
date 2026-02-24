<?php

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\panels\Panel;
use dosamigos\chartjs\ChartJs;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('audit', 'Audit Module');
$this->params['pageTitle'] = $this->title;

// IMPORTANT: do NOT use global canvas CSS like:
// canvas { width:100% !important; height:400px !important; }
// It will stretch ALL charts and reintroduce blur.

// Wrapper-based sizing (safe for canvas)
$this->registerCss(<<<CSS
.audit-chart-wrapper{
    width: 100%;
    height: 400px;     /* big chart height */
    position: relative;
}
.audit-chart-wrapper-sm{
    width: 100%;
    height: 200px;     /* small charts height */
    position: relative;
}

/* Optional: reduce extra spacing inside wells for tighter dashboard cards */
.audit-index .well {
    margin-bottom: 15px;
}
CSS);

// Make ALL ChartJS v2 charts render sharp on HiDPI (including panel->getChart())
$this->registerJs(<<<JS
if (window.Chart && Chart.defaults && Chart.defaults.global) {
    Chart.defaults.global.devicePixelRatio = window.devicePixelRatio || 1;
}
JS);
?>
<div class="audit-index">

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <h2><?= Html::a(Yii::t('audit', 'Entries'), ['/audit/entry/index']); ?></h2>

            <div class="well">
                <div class="audit-chart-wrapper">
                    <?php
                    echo ChartJs::widget([
                        'type' => 'line',

                        // NOTE: remove 'options' => ['height' => ...] when using a fixed-height wrapper
                        // to avoid attribute-vs-CSS height mismatch (which causes blur).

                        'clientOptions' => [
                            'responsive' => true,
                            'maintainAspectRatio' => false,

                            // HiDPI sharpness (also globally set above, but keeping here is fine)
                            'devicePixelRatio' => new \yii\web\JsExpression('window.devicePixelRatio'),

                            'animation' => false,

                            // UI (ChartJS v2 + plugins)
                            'plugins' => [
                                'legend' => ['display' => false],
                                'tooltip' => ['enabled' => false],
                            ],
                            'legend' => ['display' => false],
                            'tooltips' => ['enabled' => false],

                            'elements' => [
                                'line' => ['tension' => 0.3],
                                'point' => ['radius' => 2],
                            ],
                        ],

                        'data' => [
                            'labels' => array_keys($chartData),
                            'datasets' => [[
                                'backgroundColor' => 'rgba(167,199,250,.25)',
                                'borderColor' => 'rgba(4,52,128,.85)',
                                'pointBackgroundColor' => 'rgba(4,52,128,1)',
                                'pointBorderColor' => '#fff',
                                'borderWidth' => 2,
                                'fill' => true,
                                'data' => array_values($chartData),
                            ]],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>

        <?php foreach (Audit::getInstance()->panels as $panel): ?>
            <?php
            /** @var Panel $panel */
            $chart = $panel->getChart();
            if (!$chart) {
                continue;
            }
            $indexUrl = $panel->getIndexUrl();
            $indexUrl[0] = "/audit/" . $indexUrl[0];
            ?>
            <div class="col-md-3 col-lg-3">
                <h2><?= $indexUrl ? Html::a($panel->getName(), $indexUrl) : $panel->getName(); ?></h2>

                <div class="well">
                    <div class="audit-chart-wrapper-sm">
                        <?= $chart ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

</div>