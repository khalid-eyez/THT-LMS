<?php

use yii\db\Migration;

class m260105_133639_create_table_repayment_statement extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
             $this->createTable('repayment_statement', [
            'id' => $this->primaryKey(),
            'scheduleID' => $this->integer(),
            'payment_date' => $this->dateTime()->notNull(),
            'loan_amount' => $this->decimal(15,2)->notNull(),
            'principal_amount' => $this->decimal(15,2)->notNull(),
            'interest_amount' => $this->decimal(15,2)->notNull(),
            'installment' => $this->decimal(15,2)->notNull(),
            'paid_amount' => $this->decimal(15,2)->notNull()->defaultValue(0),
            'unpaid_amount' => $this->decimal(15,2)->notNull()->defaultValue(0),
            'penalty_amount' => $this->decimal(15,2)->notNull()->defaultValue(0),
            'prepayment' => $this->decimal(15,2)->notNull()->defaultValue(0),
            'balance' => $this->decimal(15,2),
        ]);
             $this->addForeignKey(
            'fk_repayment_statement_schedule',
            'repayment_statement',
            'scheduleID',
            'repayment_schedule',
            'id'
        );
         
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('repayment_statement');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260105_133639_create_table_repayment_statement cannot be reverted.\n";

        return false;
    }
    */
}
