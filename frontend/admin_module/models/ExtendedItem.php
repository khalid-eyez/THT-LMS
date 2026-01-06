<?php

namespace frontend\admin_module\models;
use yii\rbac\Item;



class ExtendedItem extends Item
{

public $node=[];

public function __construct(Item $item)
{
   $this->name=$item->name;
   $this->type=$item->type;
   $this->description=$item->description;
   $this->ruleName=$item->ruleName;
   $this->data=$item->data;
   $this->createdAt=$item->createdAt;
   $this->updatedAt=$item->updatedAt;
   
}
}








?>