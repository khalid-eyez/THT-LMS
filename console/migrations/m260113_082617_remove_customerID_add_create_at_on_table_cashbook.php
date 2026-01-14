<?php

use yii\db\Migration;

class m260113_082617_remove_customerID_add_create_at_on_table_cashbook extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
       $this->dropForeignKey(
            'fk_cashbook_customer',
            'cashbook'
        );

        // 2. Drop customerID column
        $this->dropColumn('cashbook', 'customerID');

        // 3. Make category nullable
        $this->alterColumn(
            'cashbook',
            'category',
            $this->string()->null()
        );

        // 4. Add created_at with default current timestamp
        $this->addColumn(
            'cashbook',
            'created_at',
            $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')
        );
        $this->addColumn(
    'cashbook',
    'updated_at',
    $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')
);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // 1. Remove created_at
        $this->dropColumn('cashbook', 'created_at');

        // 2. Make category NOT NULL again
        $this->alterColumn(
            'cashbook',
            'category',
            $this->string()->notNull()
        );

        // 3. Re-add customerID column
        $this->addColumn(
            'cashbook',
            'customerID',
            $this->integer()
        );

        // 4. Restore foreign key
        $this->addForeignKey(
            'fk_cashbook_customer',
            'cashbook',
            'customerID',
            'customers',
            'id'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260113_082617_remove_customerID_add_create_at_on_table_cashbook cannot be reverted.\n";

        return false;
    }
    */
}
