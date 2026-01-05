<?php

use yii\db\Migration;

class m260105_134421_create_table_cashbook extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->createTable('cashbook', [
            'id' => $this->primaryKey(),
            'customerID' => $this->integer(),
            'reference_no' => $this->string()->notNull(),
            'description' => $this->string()->notNull(),
            'category' => $this->string()->notNull(),
            'debit' => $this->decimal(15,2)->notNull()->defaultValue(0),
            'credit' => $this->decimal(15,2)->notNull()->defaultValue(0),
            'balance' => $this->decimal(15,2)->notNull(),
            'payment_document' => $this->string()->notNull(),
        ]);
           $this->addForeignKey(
            'fk_cashbook_customer',
            'cashbook',
            'customerID',
            'customers',
            'id'
        );
    
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('cashbook');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260105_134421_create_table_cashbook cannot be reverted.\n";

        return false;
    }
    */
}
