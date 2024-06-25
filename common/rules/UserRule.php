<?php
namespace common\rules;
use yii\rbac\Rule;

class UserRule extends Rule
{

public $name="User Rule";

public function execute($user, $item, $params)
{
    return true;
}
    

}






?>