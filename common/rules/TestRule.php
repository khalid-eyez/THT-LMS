<?php
namespace common\rules;
use yii\rbac\Rule;

class TestRule extends Rule
{

public $name="TestRule";

public function execute($user, $item, $params)
{
    return true;
}
    

}






?>