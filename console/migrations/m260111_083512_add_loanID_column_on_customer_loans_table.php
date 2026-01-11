<?php

use yii\db\Migration;

class m260111_083512_add_loanID_column_on_customer_loans_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%customer_loans}}',
            'loanID',
            $this->string()->notNull()->unique()
        );
       
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%customer_loans}}', 'loanID');
    }

 
}
