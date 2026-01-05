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
            'loan_amount' => $this->decimal(15,2)->notNull(),
            'topup_amount' => $this->decimal(15,2)->notNull()->defaultValue(0),
            'deposit_amount' => $this->decimal(15,2)->notNull(),
            'repayment_frequency' => "ENUM('daily','weekly','monthly','quarterly','semi-annually','annually')",
            'loan_duration_units' => $this->integer()->notNull(),
            'duration_extended' => $this->boolean()->defaultValue(false),
            'deposit_account' => $this->string(),
            'deposit_account_names' => $this->string(),
            'processing_fee_rate' => $this->decimal(15,2)->notNull(),
            'processing_fee' => $this->decimal(15,2)->notNull(),
            'status' => "ENUM('new','approved','active','finished') NOT NULL",
            'interest_rate' =>  $this->decimal(15,2)->notNull(),
            'penalty_rate' =>  $this->decimal(15,2)->notNull(),
            'topup_rate' =>  $this->decimal(15,2)->notNull(),
            'approvedby' => $this->integer(),
            'initializedby' => $this->integer()->notNull(),
            'paidby' => $this->integer(),
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
           $this->addForeignKey(
            'fk_customer_loans_initializer',
            '{{%customer_loans}}',
            'initializedby',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
           $this->addForeignKey(
            'fk_customer_loans_payer',
            '{{%customer_loans}}',
            'paidby',
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
