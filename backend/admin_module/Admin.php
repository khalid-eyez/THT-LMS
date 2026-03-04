<?php

namespace backend\admin_module;
use Exception;
use yii\web\ForbiddenHttpException;
use yii;

/**
 * admin module definition class
 */
class Admin extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\admin_module\controllers';

        public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // Check if user is logged in
        if (Yii::$app->user->isGuest) {
             return true;
        }
        $route = $action->uniqueId;
        if (in_array($route, ['admin/auth/login', 'admin/auth/logout'], true)) {
            return true;
        }
        // Check role
        if (!Yii::$app->user->can('ADMIN')) {
            throw new ForbiddenHttpException('Access Denied !');
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
