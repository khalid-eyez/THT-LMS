<?php
/* @var $panel JavascriptPanel */

use bedezign\yii2\audit\panels\JavascriptPanel;
use dosamigos\chartjs\ChartJs;


echo ChartJs::widget([
    'type' => 'bar',

    // base render height (ChartJS will upscale internally)
    'options' => [
        'height' => 150,
    ],

    'clientOptions' => [
        'responsive' => true,
        'maintainAspectRatio' => false,

        // Fix blur on Retina / 2K / 4K
        'devicePixelRatio' => new \yii\web\JsExpression('window.devicePixelRatio'),

        // performance
        'animation' => false,

        // ChartJS v2 (dosamigos) UI
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

            // ✅ correct: values, not keys
            'data' => array_values($chartData),
        ]],
    ],
]);