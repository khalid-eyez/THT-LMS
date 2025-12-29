<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shareholders".
 *
 * @property int $id
 * @property int $customerID
 * @property float $initialCapital
 * @property int $shares
 * @property int|null $isDeleted
 * @property string|null $deleted_at
 *
 * @property Customers $customer
 * @property Deposits[] $deposits
 */
class Shareholders extends \yii\db\ActiveRecord
{


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
            [['customerID', 'initialCapital', 'shares'], 'required'],
            [['customerID', 'shares', 'isDeleted'], 'integer'],
            [['initialCapital'], 'number'],
            [['deleted_at'], 'safe'],
            [['customerID'], 'unique'],
            [['customerID'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::class, 'targetAttribute' => ['customerID' => 'id']],
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
            'initialCapital' => 'Initial Capital',
            'shares' => 'Shares',
            'isDeleted' => 'Is Deleted',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery|CustomersQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customers::class, ['id' => 'customerID']);
    }

    /**
     * Gets query for [[Deposits]].
     *
     * @return \yii\db\ActiveQuery|DepositsQuery
     */
    public function getDeposits()
    {
        return $this->hasMany(Deposits::class, ['shareholderID' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ShareholdersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShareholdersQuery(get_called_class());
    }

}
