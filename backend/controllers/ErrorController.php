<?php

namespace backend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
class ErrorController extends Controller
{
    /**
     * Displays storage information from a linux server
     * @return array
     * @author khalid <thewinner016@gmail.com>
     * @since 1.0.0
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'view' => 'error.php',
            ]
        ];
    }

}
