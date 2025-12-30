<?php

namespace frontend\controllers;
use frontend\models\LoanCreateModel;

class LoansController extends \yii\web\Controller
{
    public $layout="user_dashboard";
    public function actionLoans()
    {
        return $this->render('loans');
    }

    public function actionCreateLoan(){
        return $this->render('loancreate2',['model'=>new LoanCreateModel()]);
    }

}
