<?php

use yii\db\Migration;

class m260116_073245_add_time_stamps_on_table_repayment_statement extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('{{%repayment_statement}}', 'created_at',
            $this->dateTime()
                ->notNull()
                ->defaultExpression('CURRENT_TIMESTAMP')
        );

        $this->addColumn('{{%repayment_statement}}', 'updated_at',
            $this->dateTime()
                ->notNull()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('{{%repayment_statement}}', 'updated_at');
        $this->dropColumn('{{%repayment_statement}}', 'created_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260116_073245_add_time_stamps_on_table_repayment_statement cannot be reverted.\n";

        return false;
    }
    */
}
