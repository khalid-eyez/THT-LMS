<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "repayment_schedule".
 *
 * @property int $id
 * @property int $loanID
 * @property float $principle_amount
 * @property float $interest_amount
 * @property float $installment_amount
 * @property float $loan_amount
 * @property float $loan_balance
 * @property string $repayment_date
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $isDeleted
 * @property string|null $deleted_at
 *
 * @property CustomerLoan $loan
 * @property RepaymentStatement[] $repaymentStatements
 */
class RepaymentSchedule extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const STATUS_PAID = 'paid';
    const STATUS_ACTIVE = 'active';
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
            [['deleted_at'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 'active'],
            [['isDeleted'], 'default', 'value' => 0],
            [['loanID', 'principle_amount', 'interest_amount', 'installment_amount', 'loan_amount', 'loan_balance', 'repayment_date'], 'required'],
            [['loanID', 'isDeleted'], 'integer'],
            [['principle_amount', 'interest_amount', 'installment_amount', 'loan_amount', 'loan_balance'], 'number'],
            [['repayment_date', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['status'], 'string'],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['loanID'], 'exist', 'skipOnError' => true, 'targetClass' => CustomerLoan::class, 'targetAttribute' => ['loanID' => 'id']],
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
            'principle_amount' => 'Principle Amount',
            'interest_amount' => 'Interest Amount',
            'installment_amount' => 'Installment Amount',
            'loan_amount' => 'Loan Amount',
            'loan_balance' => 'Loan Balance',
            'repayment_date' => 'Repayment Date',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'isDeleted' => 'Is Deleted',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[Loan]].
     *
     * @return \yii\db\ActiveQuery|CustomerLoanQuery
     */
    public function getLoan()
    {
        return $this->hasOne(CustomerLoan::class, ['id' => 'loanID']);
    }

    /**
     * Gets query for [[RepaymentStatements]].
     *
     * @return \yii\db\ActiveQuery|RepaymentStatementQuery
     */
    public function getRepaymentStatements()
    {
        return $this->hasMany(RepaymentStatement::class, ['scheduleID' => 'id']);
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
            self::STATUS_PAID => 'paid',
            self::STATUS_ACTIVE => 'active',
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
    public function isStatusPaid()
    {
        return $this->status === self::STATUS_PAID;
    }

    public function setStatusToPaid()
    {
        $this->status = self::STATUS_PAID;
    }

    /**
     * @return bool
     */
    public function isStatusActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function setStatusToActive()
    {
        $this->status = self::STATUS_ACTIVE;
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
