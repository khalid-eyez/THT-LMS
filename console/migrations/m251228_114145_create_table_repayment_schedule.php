<?php

use yii\db\Migration;

class m251228_114145_create_table_repayment_schedule extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
             $this->createTable('{{%repayment_schedule}}', [
            'id' => $this->primaryKey(),
            'loanID' => $this->integer()->notNull(),
            'principle_amount' => $this->decimal(15,2)->notNull(),
            'interest_amount' => $this->decimal(15,2)->notNull(),
            'installment_amount' => $this->decimal(15,2)->notNull(),
            'loan_amount' => $this->decimal(15,2)->notNull(),
            'loan_balance' => $this->decimal(15,2)->notNull(),
            'repayment_date' => $this->dateTime()->notNull(), // as on the schedule
            'status' => "ENUM('paid','active','delayed') NOT NULL DEFAULT 'active'",
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
            'isDeleted'=>$this->boolean()->defaultValue(false),
            'deleted_at'=>$this->dateTime(),
        ]);

          $this->addForeignKey(
            'fk_repayment_loan',
            '{{%repayment_schedule}}',
            'loanID',
            '{{%customer_loans}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropTable("repayment_schedule");
    }

   
}
