<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "shareholders".
 *
 * @property int $id
 * @property int $customerID
 * @property string $memberID
 * @property float $initialCapital
 * @property int $shares
 * @property int|null $isDeleted
 * @property string|null $deleted_at
 *
 * @property Customer $customer
 * @property Deposit[] $deposits
 */
class Shareholder extends \yii\db\ActiveRecord
{
public function behaviors()
{
    return [
     
        'auditBehaviour' => 'bedezign\yii2\audit\AuditTrailBehavior',
    ];
}


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shareholders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deleted_at'], 'default', 'value' => null],
            [['isDeleted'], 'default', 'value' => 0],
            [['customerID', 'memberID', 'initialCapital'], 'required'],
            [['customerID', 'shares', 'isDeleted'], 'integer'],
            [['initialCapital'], 'number'],
            [['deleted_at'], 'safe'],
            [['memberID'], 'string', 'max' => 20],
            [['customerID'], 'unique'],
            [['memberID'], 'unique'],
            [['customerID'], 'exist','skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customerID' => 'id']],
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
            'memberID' => 'Member ID',
            'initialCapital' => 'Initial Capital',
            'shares' => 'Shares',
            'isDeleted' => 'Is Deleted',
            'deleted_at' => 'Deleted At',
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
     * Gets query for [[Deposits]].
     *
     * @return \yii\db\ActiveQuery|DepositQuery
     */
    public function getDeposits()
    {
        return $this->hasMany(Deposit::class, ['shareholderID' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ShareholderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShareholderQuery(get_called_class());
    }

}
