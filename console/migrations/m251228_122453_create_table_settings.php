<?php

use yii\db\Migration;

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

   
}
