<?php

use yii\db\Migration;
use yii\helpers\ArrayHelper;

class m251228_122453_create_table_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
             $this->createTable('{{%settings}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(20)->notNull(),
            'value'=>$this->json()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropTable("settings");
    }
    public function getaLl()
    {
       $settings=$this->find()->all();
       $settings=ArrayHelper::map($settings,'name',function($setting){
           return json_decode($setting->value)[0];
       });

       return $settings;
    }
    public function getSettings(string  $name){
          return $this->getaLl()[$name];
    }

   
}
