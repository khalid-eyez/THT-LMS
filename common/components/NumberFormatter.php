<?php
    namespace common\components;
    use yii\base\Component;
    use yii;


    class NumberFormatter extends Component
    {
        public function format($number)
        {
           $currency=yii::$app->params['currency'];
           $locale=yii::$app->params['locale'];
           $formatter=new \NumberFormatter($locale,\NumberFormatter::CURRENCY);
           $formatter->setSymbol(\NumberFormatter::CURRENCY_SYMBOL,$currency);
           return $formatter->format($number);



        }
    }










?>