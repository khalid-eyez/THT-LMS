<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "deposits".
 *
 * @property int $depositID
 * @property int $shareholderID
 * @property float $amount
 * @property float $interest_rate
 * @property string|null $type
 * @property string $deposit_date
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $isDeleted
 * @property string|null $deleted_at
 *
 * @property DepositInterest[] $depositInterests
 * @property Shareholder $shareholder
 */
class Deposit extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const TYPE_CAPITAL = 'capital';
    const TYPE_MONTHLY = 'monthly';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deposits';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'deleted_at'], 'default', 'value' => null],
            [['isDeleted'], 'default', 'value' => 0],
            [['shareholderID', 'amount', 'interest_rate', 'deposit_date'], 'required'],
            [['shareholderID', 'isDeleted'], 'integer'],
            [['amount', 'interest_rate'], 'number'],
            [['type'], 'string'],
            [['deposit_date', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            ['type', 'in', 'range' => array_keys(self::optsType())],
            [['shareholderID'], 'exist', 'skipOnError' => true, 'targetClass' => Shareholder::class, 'targetAttribute' => ['shareholderID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'depositID' => 'Deposit ID',
            'shareholderID' => 'Shareholder ID',
            'amount' => 'Amount',
            'interest_rate' => 'Interest Rate',
            'type' => 'Type',
            'deposit_date' => 'Deposit Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'isDeleted' => 'Is Deleted',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[DepositInterests]].
     *
     * @return \yii\db\ActiveQuery|DepositInterestQuery
     */
    public function getDepositInterests()
    {
        return $this->hasMany(DepositInterest::class, ['depositID' => 'depositID']);
    }

    /**
     * Gets query for [[Shareholder]].
     *
     * @return \yii\db\ActiveQuery|ShareholderQuery
     */
    public function getShareholder()
    {
        return $this->hasOne(Shareholder::class, ['id' => 'shareholderID']);
    }

    /**
     * {@inheritdoc}
     * @return DepositQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DepositQuery(get_called_class());
    }


    /**
     * column type ENUM value labels
     * @return string[]
     */
    public static function optsType()
    {
        return [
            self::TYPE_CAPITAL => 'capital',
            self::TYPE_MONTHLY => 'monthly',
        ];
    }

    /**
     * @return string
     */
    public function displayType()
    {
        return self::optsType()[$this->type];
    }

    /**
     * @return bool
     */
    public function isTypeCapital()
    {
        return $this->type === self::TYPE_CAPITAL;
    }

    public function setTypeToCapital()
    {
        $this->type = self::TYPE_CAPITAL;
    }

    /**
     * @return bool
     */
    public function isTypeMonthly()
    {
        return $this->type === self::TYPE_MONTHLY;
    }

    public function setTypeToMonthly()
    {
        $this->type = self::TYPE_MONTHLY;
    }
}
