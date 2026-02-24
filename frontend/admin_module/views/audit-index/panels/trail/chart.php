<?php
/* @var $panel TrailPanel */

use bedezign\yii2\audit\models\AuditTrail;
use bedezign\yii2\audit\panels\TrailPanel;
use dosamigos\chartjs\ChartJs;

//initialise defaults (0 entries) for each day
$defaults = [];
$startDate = strtotime('-6 days');
foreach (range(-6, 0) as $day) {
    $defaults[date('D: Y-m-d', strtotime($day . 'days'))] = 0;
}

$results = AuditTrail::find()
    ->select(["COUNT(DISTINCT id) as count", "created AS day"])
    ->where(['between', 'created',
        date('Y-m-d 00:00:00', $startDate),
        date('Y-m-d 23:59:59')])
    ->groupBy("day")->indexBy('day')->column();

// format dates properly
$formattedData = [];
foreach ($results as $date => $count) {
    $date = date('D: Y-m-d', strtotime($date));
    $formattedData[$date] = $count;
}
$results = $formattedData;

// replace defaults with data from db where available
$results = array_merge($defaults, $results);
echo ChartJs::widget([
    'type' => 'bar',

    // Base render height (ChartJS upscales internally)
    'options' => [
        'height' => 150,
    ],

    'clientOptions' => [
        'responsive' => true,
        'maintainAspectRatio' => true,

        // 🔥 Retina / 2K / 4K blur fix
        'devicePixelRatio' => new \yii\web\JsExpression('window.devicePixelRatio'),
        // Optional safe cap:
        // 'devicePixelRatio' => new \yii\web\JsExpression('Math.min(window.devicePixelRatio, 2)'),

        'animation' => false,

        // ChartJS v2 (dosamigos)
        'plugins' => [
            'legend' => ['display' => false],
            'tooltip' => ['enabled' => false],
        ],
        'legend' => ['display' => false],
        'tooltips' => ['enabled' => false],
    ],

    'data' => [
        'labels' => array_keys($chartData),
        'datasets' => [[
            'backgroundColor' => 'rgba(167, 199, 250,.8)',
            'borderColor' => 'rgba(151,187,205,1)',
            'borderWidth' => 1,
            'data' => array_values($chartData),
        ]],
    ],
]);
