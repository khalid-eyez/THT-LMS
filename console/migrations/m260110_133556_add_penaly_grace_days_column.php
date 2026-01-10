<?php

use yii\db\Migration;

class m260110_133556_add_penaly_grace_days_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%customer_loans}}',
            'penalty_grace_days',
            $this->integer()->notNull()->defaultValue(0)->after('penalty_rate')
        );
    }

    public function safeDown()
    {
        $this->dropColumn('{{%customer_loans}}', 'penalty_grace_days');
    }

}
