<?php

use yii\db\Migration;

class m260116_091403_add_loanID_on_table_repayment_statement extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
          // 1. Add column
        $this->addColumn(
            'repayment_statement',
            'loanID',
            $this->integer()->after('scheduleID')
        );

        // 2. Add foreign key with CASCADE on delete
        $this->addForeignKey(
            'fk_repayment_statement_loan',
            'repayment_statement',
            'loanID',
            'customer_loans',
            'id',
            'CASCADE', // on delete
            'CASCADE' // on update (can be CASCADE if you prefer)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         $this->dropForeignKey(
            'fk_repayment_statement_loan',
            'repayment_statement'
        );

        // Then drop column
        $this->dropColumn(
            'repayment_statement',
            'loanID'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260116_091403_add_loanID_on_table_repayment_statement cannot be reverted.\n";

        return false;
    }
    */
}
