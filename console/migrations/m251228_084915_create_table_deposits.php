<?php

use yii\db\Migration;

class m251228_084915_create_table_deposits extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%deposits}}', [
            'depositID' => $this->primaryKey(),
            'shareholderID' => $this->integer()->notNull(),
            'amount' => $this->decimal(15,2)->notNull(),
            'interest_rate' => $this->decimal(5,2)->notNull(),
            'type' => "ENUM('capital','monthly')",
            'deposit_date' => $this->date()->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
            'isDeleted'=>$this->boolean()->defaultValue(false),
            'deleted_at'=>$this->dateTime(),
        ]);

           $this->addForeignKey(
            'fk_deposits_shareholder',
            '{{%deposits}}',
            'shareholderID',
            '{{%shareholders}}',
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
       $this->dropTable('deposits');
    }

   
}
