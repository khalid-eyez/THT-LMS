<?php

use yii\db\Migration;

class m260120_184739_add_column_topup_amount_on_table_repayment_statement extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'repayment_statement',
            'topup_amount',
            $this->decimal(15,2)->notNull()->defaultValue(0)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('repayment_statement', 'topup_amount');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260120_184739_add_column_topup_amount_on_table_repayment_statement cannot be reverted.\n";

        return false;
    }
    */
}
