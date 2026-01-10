<?php

use yii\db\Migration;

class m240718_205814_create_table_user extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%user}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull(),
                'username' => $this->string()->notNull(),
                'auth_key' => $this->string(32)->notNull(),
                'password_hash' => $this->string()->notNull()->defaultValue('$2y$13$.kIiLCr/VFHDJULiTjtIk.7oKy4UN3dYlX2WKa1eJ3/mNpXhWVW96'),
                'password_reset_token' => $this->string(),
                'status' => $this->smallInteger()->notNull()->defaultValue('10'),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
                'verification_token' => $this->string(),
                'last_login' => $this->dateTime(),
            ],
            $tableOptions
        );

        $this->createIndex('username', '{{%user}}', ['username'], true);
        $this->createIndex('password_reset_token', '{{%user}}', ['password_reset_token'], true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
