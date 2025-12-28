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
            'amount' => $this->decimal(15,2)->notNull(),
            'penalty' => $this->decimal(15,2)->notNull()->defaultValue(0),
            'repayment_date' => $this->dateTime()->notNull(),
            'status' => "ENUM('payed','waiting','delayed') NOT NULL DEFAULT 'waiting'",
            'date_paid' => $this->dateTime(),
            'payment_document' => $this->string(255),
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
