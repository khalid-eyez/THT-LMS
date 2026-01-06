<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;

use Yii;

/**
 * This is the model class for table "cashbook".
 *
 * @property int $id
 * @property int|null $customerID
 * @property string $reference_no
 * @property string $description
 * @property string $category
 * @property float $debit
 * @property float $credit
 * @property float $balance
 * @property string $payment_document
 *
 * @property Customer $customer
 */
class Cashbook extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
             'auditBehaviour'=>'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cashbook';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customerID'], 'default', 'value' => null],
            [['credit'], 'default', 'value' => 0.00],
            [['customerID'], 'integer'],
            [['reference_no', 'description', 'category', 'balance', 'payment_document'], 'required'],
            [['debit', 'credit', 'balance'], 'number'],
            [['reference_no', 'description', 'category', 'payment_document'], 'string', 'max' => 255],
            [['customerID'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customerID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customerID' => 'Customer ID',
            'reference_no' => 'Reference No',
            'description' => 'Description',
            'category' => 'Category',
            'debit' => 'Debit',
            'credit' => 'Credit',
            'balance' => 'Balance',
            'payment_document' => 'Payment Document',
        ];
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery|CustomerQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customerID']);
    }

    /**
     * {@inheritdoc}
     * @return CashbookQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CashbookQuery(get_called_class());
    }

}
