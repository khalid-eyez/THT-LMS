<?php

namespace frontend\loans_module\controllers;

class CustomerController extends \yii\web\Controller
{
    public function actionList()
    {
        return $this->render('list');
    }

}
