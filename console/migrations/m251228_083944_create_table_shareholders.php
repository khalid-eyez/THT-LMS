<?php

use yii\db\Migration;

class m251228_083944_create_table_shareholders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
             $this->createTable('{{%shareholders}}', [
            'id' => $this->primaryKey(),
            'customerID' => $this->integer()->notNull()->unique(),
            'initialCapital' => $this->decimal(15,2)->notNull(),
            'shares' => $this->integer()->notNull(),
            'isDeleted' => $this->boolean()->defaultValue(false),
            'deleted_at'=>$this->dateTime()
        ]);

            $this->addForeignKey(
            'fk_shareholders_customer',
            '{{%shareholders}}',
            'customerID',
            '{{%customers}}',
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
       $this->dropTable('shareholders');
    }


}
