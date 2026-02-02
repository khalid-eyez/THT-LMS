<?php

namespace frontend\shareholder_module;

/**
 * shareholder module definition class
 */
class Shareholder extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\shareholder_module\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        \Yii::$app->errorHandler->errorAction = '/loans/loans/error';
        // custom initialization code goes here
    }
}
