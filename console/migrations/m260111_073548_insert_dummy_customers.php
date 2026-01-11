<?php

use yii\db\Migration;

class m260111_073548_insert_dummy_customers extends Migration
{
    /**
     * {@inheritdoc}
     */
 public function safeUp()
    {
        $this->batchInsert('customers', [
            'customerID',
            'userID',
            'full_name',
            'birthDate',
            'gender',
            'address',
            'contacts',
            'NIN',
            'TIN',
            'status',
            'isDeleted',
            'deleted_at'
        ], [
            [
                'CUST001',
                4,
                'John Michael Kato',
                '1990-05-12',
                'male',
                'House Number 45, Block C, Near University of Dodoma Main Gate, Nzuguni Area, Dodoma City, Tanzania',
                'Phone: 0756123456, Email: john@example.com',
                'NIN-JOHN-001',
                'TIN-JOHN-001',
                'active',
                0,
                null
            ],
            [
                'CUST002',
                5,
                'Agnes Neema Julius',
                '1992-11-22',
                'female',
                'Plot 12, Sakina Street, Near Clock Tower, Arusha City, Arusha Region, Tanzania',
                'Phone: 0756333444, Email: agnes@example.com',
                'NIN-AGNES-002',
                'TIN-AGNES-002',
                'active',
                0,
                null
            ]
        ]);
    }

    public function safeDown()
    {
        $this->delete('customers', ['customerID' => ['CUST001', 'CUST002']]);
    }
}
