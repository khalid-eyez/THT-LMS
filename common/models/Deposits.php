<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "deposits".
 *
 * @property int $depositID
 * @property int $shareholderID
 * @property float $amount
 * @property float $interestRate
 * @property string $deposit_date
 * @property string $deposit_document
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $isDeleted
 * @property string|null $deleted_at
 *
 * @property Shareholders $shareholder
 */
class Deposits extends \yii\db\ActiveRecord
{


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
            [['deleted_at'], 'default', 'value' => null],
            [['isDeleted'], 'default', 'value' => 0],
            [['shareholderID', 'amount', 'interestRate', 'deposit_date', 'deposit_document'], 'required'],
            [['shareholderID', 'isDeleted'], 'integer'],
            [['amount', 'interestRate'], 'number'],
            [['deposit_date', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['deposit_document'], 'string', 'max' => 255],
            [['shareholderID'], 'exist', 'skipOnError' => true, 'targetClass' => Shareholders::class, 'targetAttribute' => ['shareholderID' => 'id']],
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
            'interestRate' => 'Interest Rate',
            'deposit_date' => 'Deposit Date',
            'deposit_document' => 'Deposit Document',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'isDeleted' => 'Is Deleted',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[Shareholder]].
     *
     * @return \yii\db\ActiveQuery|ShareholdersQuery
     */
    public function getShareholder()
    {
        return $this->hasOne(Shareholders::class, ['id' => 'shareholderID']);
    }

    /**
     * {@inheritdoc}
     * @return DepositsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DepositsQuery(get_called_class());
    }

}
