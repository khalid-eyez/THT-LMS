<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class DashboardAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    "css/bootstrap.min.css",
    "css/font-awesome.min.css",
    "css/owl.carousel.css",
    "css/owl.theme.css",
    "css/owl.transitions.css",
    "css/meanmenu/meanmenu.min.css",
    "css/notika-custom-icon.css",
    "css/scrollbar/jquery.mCustomScrollbar.min.css",
    "css/animate.css",
    "css/normalize.css",
    "css/wave/waves.min.css",
    "css/wave/button.css",
    "css/main.css",
    "css/style.css",
    "css/responsive.css"
   
        
      
        
    ];
    public $js = [
    "js/vendor/jquery-1.12.4.min.js",
    "js/bootstrap.min.js",
    "js/wow.min.js",
    "js/jquery-price-slider.js",
    "js/owl.carousel.min.js",
    "js/jquery.scrollUp.min.js",
    "js/meanmenu/jquery.meanmenu.js",
    "js/counterup/jquery.counterup.min.js",
    "js/counterup/waypoints.min.js",
    "js/counterup/counterup-active.js",
    "js/sparkline/jquery.sparkline.min.js",
    "js/sparkline/sparkline-active.js",
    "js/knob/jquery.knob.js",
    "js/knob/jquery.appear.js",
    "js/knob/knob-active.js",
    "js/scrollbar/jquery.mCustomScrollbar.concat.min.js",
    "js/flot/jquery.flot.js",
    "js/flot/jquery.flot.resize.js",
    "js/flot/jquery.flot.time.js",
    "js/flot/jquery.flot.tooltip.min.js",
    "js/flot/analtic-flot-active.js",
    "js/wave/waves.min.js",
    "js/wave/wave-active.js",
    "js/plugins.js",
    "js/main.js",
 
      
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
       
    ];
}
