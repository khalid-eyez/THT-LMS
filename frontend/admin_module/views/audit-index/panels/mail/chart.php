<?php
/* @var $panel MailPanel */

use bedezign\yii2\audit\panels\MailPanel;
use dosamigos\chartjs\ChartJs;

echo ChartJs::widget([
    'type' => 'bar',

    // Base render height (ChartJS will upscale internally)
    'options' => [
        'height' => 150,
    ],

    'clientOptions' => [
        'responsive' => true,
        'maintainAspectRatio' => true,

        // 🔥 Fix blur on Retina / 2K / 4K
        'devicePixelRatio' => new \yii\web\JsExpression('window.devicePixelRatio'),
        // Optional performance cap:
        // 'devicePixelRatio' => new \yii\web\JsExpression('Math.min(window.devicePixelRatio, 2)'),

        // Performance
        'animation' => false,

        // ChartJS v2 compatibility (dosamigos)
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
?>