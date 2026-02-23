<?php

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\panels\Panel;
use dosamigos\chartjs\ChartJs;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('audit', 'Audit Module');
$this->params['pageTitle']= $this->title;

//$this->registerCss('canvas {width: 100% !important;height: 400px;}');

$this->registerCss(<<<CSS
.audit-chart-wrapper{
    width: 100%;
    height: 400px;     /* controls visible height */
    position: relative;
}
CSS);
?>
<div class="audit-index">

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <h2><?php echo Html::a(Yii::t('audit', 'Entries'), ['/audit/entry/index']); ?></h2>

            <div class="well">
<div class="audit-chart-wrapper">
<?php
echo ChartJs::widget([
    'type' => 'line',

    // base render height (ChartJS will upscale internally)
    'options' => [
        'height' => 150,
    ],

    'clientOptions' => [
        'responsive' => true,
        'maintainAspectRatio' => false,

        // THE MAGIC: match screen density (fixes blur on 2K/4K/Retina)
        'devicePixelRatio' => new \yii\web\JsExpression('window.devicePixelRatio'),

        // performance
        'animation' => false,

        // UI
        'plugins' => [
            'legend' => ['display' => false],
            'tooltip' => ['enabled' => false],
        ],

        // ChartJS v2 compatibility (dosamigos uses v2)
        'legend' => ['display' => false],
        'tooltips' => ['enabled' => false],

        // make lines nicer
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

        <?php
        foreach (Audit::getInstance()->panels as $panel) {
            /** @var Panel $panel */
            $chart = $panel->getChart();
            if (!$chart) {
                continue;
            }
            $indexUrl = $panel->getIndexUrl();
            $indexUrl[0]="/audit/".$indexUrl[0];
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
