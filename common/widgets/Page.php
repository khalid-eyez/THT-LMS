<?php 
namespace common\widgets;
use yii\base\Widget;
class Page extends Widget{
    public $pageTitle;
    public function init(){
        Parent::init();
    }
    public function run(){
        Parent::run();
        return $this->pageTitle;
    }
}
?>