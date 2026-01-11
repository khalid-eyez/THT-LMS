<?php

use yii\db\Migration;

class m260111_075602_insert_dummy_shareholders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('shareholders', [
            'customerID',
            'memberID',
            'initialCapital',
            'shares',
            'isDeleted',
            'deleted_at'
        ], [
            [
                3,              // customers.id
                'MEM-001',
                500000,
                50,
                0,
                null
            ],
            [
                4,              // customers.id
                'MEM-002',
                750000,
                75,
                0,
                null
            ],
        ]);
    }

    public function safeDown()
    {
        $this->delete('shareholders', ['memberID' => ['MEM-001', 'MEM-002']]);
    }
}

