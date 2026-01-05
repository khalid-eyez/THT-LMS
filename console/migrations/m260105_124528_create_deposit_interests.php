<?php

use yii\db\Migration;

class m260105_124528_create_deposit_interests extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
           $this->createTable('deposit_interests', [
            'id' => $this->primaryKey(),
            'depositID' => $this->integer()->notNull(),
            'interest_amount' => $this->decimal(15,2)->notNull(),
            'payment_date' => $this->dateTime(),
            'claim_date' => $this->dateTime(),
            'claim_months' => $this->integer()->notNull(),
            'approved_at' => $this->dateTime(),
            'approved_by' => $this->integer(),
    
        ]);
           $this->addForeignKey(
            'fk_deposit_interests_deposit',
            'deposit_interests',
            'depositID',
            'deposits',
            'depositID'
        );

        $this->addForeignKey(
            'fk_deposit_interests_user',
            'deposit_interests',
            'approved_by',
            'user',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         $this->dropTable('deposit_interests');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260105_124528_create_deposit_interests cannot be reverted.\n";

        return false;
    }
    */
}
