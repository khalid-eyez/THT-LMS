<?php
namespace frontend\assets;

use yii\web\JqueryAsset;

class Jquery3Asset extends JqueryAsset
{
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js', // your required version
    ];
     public $depends = [];
}