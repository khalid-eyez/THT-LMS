<?php

use yii\db\Migration;

class m260217_064452_add_status_to_cashbook_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
          $this->addColumn(
            'cashbook',
            'status',
            "ENUM('new','reversed') NOT NULL DEFAULT 'new' AFTER balance"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cashbook', 'status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260217_064452_add_status_to_cashbook_table cannot be reverted.\n";

        return false;
    }
    */
}
