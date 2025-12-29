<?php

namespace frontend\controllers;

class LoansController extends \yii\web\Controller
{
    public function actionLoans()
    {
        return $this->render('loans');
    }

    public function actionCreateLoan(){
        return $this->render('loancreate');
    }

}
