<?php

use yii\db\Migration;

class m251228_091103_create_table_loan_requirements extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
             $this->createTable('{{%loan_requirements}}', [
            'id' => $this->primaryKey(),
            'loan_type_ID' => $this->integer()->notNull(),
            'document' => $this->string(50)->notNull(),
            'optional' => "ENUM('true','false') NOT NULL DEFAULT 'false'",
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
        ]);

             $this->addForeignKey(
            'fk_requirements_loan_type',
            '{{%loan_requirements}}',
            'loan_type_ID',
            '{{%loan_types}}',
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
      $this->dropTable('loan_requirements');
    }

  
}
