<?php

use yii\db\Migration;

class m251228_090256_create_table_loan_types extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
            $this->createTable('{{%loan_types}}', [
            'id' => $this->primaryKey(),
            'categoryID' => $this->integer()->notNull(),
            'type' => $this->string(50)->notNull(),
            'interest_rate' => $this->decimal(5,2)->notNull()->defaultValue(0),
            'topup_rate' => $this->decimal(5,2)->notNull()->defaultValue(0),
            'penalty_rate' => $this->decimal(5,2)->notNull()->defaultValue(0),
            'processing_fee_rate' => $this->decimal(5,2)->notNull()->defaultValue(0),
            'penalty_grace_days' => $this->integer()->notNull()->defaultValue(1),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
        ]);

            $this->addForeignKey(
            'fk_loan_types_category',
            '{{%loan_types}}',
            'categoryID',
            '{{%loan_categories}}',
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
        $this->dropTable('loan_types');
    }

   
}
