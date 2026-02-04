<?php

use yii\db\Migration;

class m260203_120000_alter_fk_deposit_interests_deposit_cascade extends Migration
{
    public function safeUp()
    {
        // Drop the existing FK
        $this->dropForeignKey('fk_deposit_interests_deposit', 'deposit_interests');

        // Re-add it with CASCADE on delete/update
        $this->addForeignKey(
            'fk_deposit_interests_deposit',
            'deposit_interests',
            'depositID',
            'deposits',
            'depositID',
            'CASCADE', // ON DELETE
            'CASCADE'  // ON UPDATE
        );
    }

    public function safeDown()
    {
        // Revert back to no action / restrict behavior (Yii will map null to DB default)
        $this->dropForeignKey('fk_deposit_interests_deposit', 'deposit_interests');

        $this->addForeignKey(
            'fk_deposit_interests_deposit',
            'deposit_interests',
            'depositID',
            'deposits',
            'depositID',
            null, // ON DELETE
            null  // ON UPDATE
        );
    }
}
