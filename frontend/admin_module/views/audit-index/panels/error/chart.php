<?php
/* @var $panel ErrorPanel */

use bedezign\yii2\audit\panels\ErrorPanel;
use dosamigos\chartjs\ChartJs;


echo ChartJs::widget([
    'type' => 'bar',

    // base render height (ChartJS will upscale internally)
    'options' => [
        'height' => 20,
    ],

    'clientOptions' => [
        'responsive' => true,
        'maintainAspectRatio' => false,

        // Fix blur on Retina / 2K / 4K
        'devicePixelRatio' => new \yii\web\JsExpression('window.devicePixelRatio'),
        // optional cap if you want: new \yii\web\JsExpression('Math.min(window.devicePixelRatio, 2)'),

        // performance
        'animation' => false,

        // ChartJS v2 + plugin UI
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

