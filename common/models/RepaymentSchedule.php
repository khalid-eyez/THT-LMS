<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "repayment_schedule".
 *
 * @property int $id
 * @property int $loanID
 * @property float $amount
 * @property float $penalty
 * @property string $repayment_date
 * @property string $status
 * @property string|null $date_paid
 * @property string|null $payment_document
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $isDeleted
 * @property string|null $deleted_at
 *
 * @property CustomerLoans $loan
 */
class RepaymentSchedule extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const STATUS_PAYED = 'payed';
    const STATUS_WAITING = 'waiting';
    const STATUS_DELAYED = 'delayed';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'repayment_schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_paid', 'payment_document', 'deleted_at'], 'default', 'value' => null],
            [['penalty'], 'default', 'value' => 0.00],
            [['status'], 'default', 'value' => 'waiting'],
            [['isDeleted'], 'default', 'value' => 0],
            [['loanID', 'amount', 'repayment_date'], 'required'],
            [['loanID', 'isDeleted'], 'integer'],
            [['amount', 'penalty'], 'number'],
            [['repayment_date', 'date_paid', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['status'], 'string'],
            [['payment_document'], 'string', 'max' => 255],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['loanID'], 'exist', 'skipOnError' => true, 'targetClass' => CustomerLoans::class, 'targetAttribute' => ['loanID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'loanID' => 'Loan ID',
            'amount' => 'Amount',
            'penalty' => 'Penalty',
            'repayment_date' => 'Repayment Date',
            'status' => 'Status',
            'date_paid' => 'Date Paid',
            'payment_document' => 'Payment Document',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'isDeleted' => 'Is Deleted',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[Loan]].
     *
     * @return \yii\db\ActiveQuery|CustomerLoansQuery
     */
    public function getLoan()
    {
        return $this->hasOne(CustomerLoans::class, ['id' => 'loanID']);
    }

    /**
     * {@inheritdoc}
     * @return RepaymentScheduleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RepaymentScheduleQuery(get_called_class());
    }


    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_PAYED => 'payed',
            self::STATUS_WAITING => 'waiting',
            self::STATUS_DELAYED => 'delayed',
        ];
    }

    /**
     * @return string
     */
    public function displayStatus()
    {
        return self::optsStatus()[$this->status];
    }

    /**
     * @return bool
     */
    public function isStatusPayed()
    {
        return $this->status === self::STATUS_PAYED;
    }

    public function setStatusToPayed()
    {
        $this->status = self::STATUS_PAYED;
    }

    /**
     * @return bool
     */
    public function isStatusWaiting()
    {
        return $this->status === self::STATUS_WAITING;
    }

    public function setStatusToWaiting()
    {
        $this->status = self::STATUS_WAITING;
    }

    /**
     * @return bool
     */
    public function isStatusDelayed()
    {
        return $this->status === self::STATUS_DELAYED;
    }

    public function setStatusToDelayed()
    {
        $this->status = self::STATUS_DELAYED;
    }
}
