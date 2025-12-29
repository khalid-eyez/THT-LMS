<?php

use yii\db\Migration;

class m251228_121206_create_table_loan_attachments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
          $this->createTable('{{%loan_attachments}}', [
            'id' => $this->primaryKey(),
            'loanID' => $this->integer()->notNull(),
            'uploaded_doc' => $this->string(255)->notNull(),
            'timevalidated' => $this->dateTime(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
            'isDeleted'=>$this->boolean()->defaultValue(false),
            'deleted_at'=>$this->dateTime(),
        ]);
           $this->addForeignKey(
            'fk_attachments_loan',
            '{{%loan_attachments}}',
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
       $this->dropTable("loan_attachments");
    }

   
}
