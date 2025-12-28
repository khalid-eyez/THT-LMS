<?php

use yii\db\Migration;

class m251228_080105_create_table_customers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
             $this->createTable('{{%customers}}', [
            'id' => $this->primaryKey(),
            'userID' => $this->integer()->notNull()->unique(),
            'full_name' => $this->string(50)->notNull(),
            'birthDate' => $this->date()->notNull(),
            'gender' => $this->string(8)->notNull(),
            'address' => $this->json()->notNull(),
            'contacts' => $this->json()->notNull(),
            'NIN' => $this->string(50)->notNull()->unique(),
            'TIN' => $this->string(50),
            'status' => "ENUM('active','inactive') NOT NULL DEFAULT 'active'",
            'isDeleted'=>$this->boolean()->defaultValue(false),
            'deleted_at'=>$this->dateTime(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        
        $this->addForeignKey(
            'fk_customers_user',
            '{{%customers}}',
            'userID',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('customers');

    }

 
}
