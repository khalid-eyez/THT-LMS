<?php

use yii\db\Migration;

class m260112_144034_modify_column_status_on_table_customer_loans extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
           $this->execute("
            ALTER TABLE customer_loans
            MODIFY COLUMN status ENUM(
                'new',
                'approved',
                'rejected',
                'active',
                'finished'
            ) NOT NULL
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         $this->execute("
            ALTER TABLE customer_loans
            MODIFY COLUMN status ENUM(
                'new',
                'approved',
                'active',
                'finished'
            ) NOT NULL
        ");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260112_144034_modify_column_status_on_table_customer_loans cannot be reverted.\n";

        return false;
    }
    */
}
