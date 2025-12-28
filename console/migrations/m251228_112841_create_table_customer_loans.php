<?php

use yii\db\Migration;

class m251228_112841_create_table_customer_loans extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
           $this->createTable('{{%customer_loans}}', [
            'id' => $this->primaryKey(),
            'customerID' => $this->integer()->notNull(),
            'loan_type_ID' => $this->integer()->notNull(),
            'amount' => $this->decimal(15,2)->notNull(),
            'repayment_frequency' => "ENUM('week','month','year') NOT NULL",
            'loan_duration_units' => $this->integer()->notNull(),
            'approvedby' => $this->integer()->notNull(),
            'approved_at'=> $this->dateTime(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
            'isDeleted'=>$this->boolean()->defaultValue(false),
            'deleted_at'=>$this->dateTime(),
        ]);
            $this->addForeignKey(
            'fk_customer_loans_customer',
            '{{%customer_loans}}',
            'customerID',
            '{{%customers}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_customer_loans_type',
            '{{%customer_loans}}',
            'loan_type_ID',
            '{{%loan_types}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_customer_loans_approver',
            '{{%customer_loans}}',
            'approvedby',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropTable("customer_loans");
    }

   
}
