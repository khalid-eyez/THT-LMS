<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "customer_loans".
 *
 * @property int $id
 * @property int $customerID
 * @property int $loan_type_ID
 * @property float $loan_amount
 * @property float $topup_amount
 * @property float $deposit_amount
 * @property string|null $repayment_frequency
 * @property int $loan_duration_units
 * @property int|null $duration_extended
 * @property string|null $deposit_account
 * @property string|null $deposit_account_names
 * @property float $processing_fee_rate
 * @property float $processing_fee
 * @property string $status
 * @property float $interest_rate
 * @property float $penalty_rate
 * @property float $topup_rate
 * @property int|null $approvedby
 * @property int $initializedby
 * @property int|null $paidby
 * @property string|null $approved_at
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $isDeleted
 * @property string|null $deleted_at
 *
 * @property User $approvedby0
 * @property Customer $customer
 * @property User $initializedby0
 * @property LoanAttachment[] $loanAttachments
 * @property LoanType $loanType
 * @property User $paidby0
 * @property RepaymentSchedule[] $repaymentSchedules
 */
class CustomerLoan extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const REPAYMENT_FREQUENCY_DAILY = 'daily';
    const REPAYMENT_FREQUENCY_WEEKLY = 'weekly';
    const REPAYMENT_FREQUENCY_MONTHLY = 'monthly';
    const REPAYMENT_FREQUENCY_QUARTERLY = 'quarterly';
    const REPAYMENT_FREQUENCY_SEMI_ANNUALLY = 'semi-annually';
    const REPAYMENT_FREQUENCY_ANNUALLY = 'annually';
    const STATUS_NEW = 'new';
    const STATUS_APPROVED = 'approved';
    const STATUS_ACTIVE = 'active';
    const STATUS_FINISHED = 'finished';

     public function behaviors()
    {
        return [
               [
            'class' => TimestampBehavior::class,
            'value' => new Expression('NOW()'),
             ],
             'auditBehaviour'=>'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_loans';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['repayment_frequency', 'deposit_account', 'deposit_account_names', 'approvedby', 'paidby', 'approved_at', 'deleted_at'], 'default', 'value' => null],
            [['topup_amount'], 'default', 'value' => 0.00],
            [['isDeleted'], 'default', 'value' => 0],
            [['customerID', 'loan_type_ID', 'loan_amount', 'deposit_amount', 'loan_duration_units', 'processing_fee_rate', 'processing_fee', 'status', 'interest_rate', 'penalty_rate', 'topup_rate', 'initializedby'], 'required'],
            [['customerID', 'loan_type_ID', 'loan_duration_units', 'duration_extended', 'approvedby', 'initializedby', 'paidby', 'isDeleted'], 'integer'],
            [['loan_amount', 'topup_amount', 'deposit_amount', 'processing_fee_rate', 'processing_fee', 'interest_rate', 'penalty_rate', 'topup_rate'], 'number'],
            [['repayment_frequency', 'status'], 'string'],
            [['approved_at', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['deposit_account', 'deposit_account_names'], 'string', 'max' => 255],
            ['repayment_frequency', 'in', 'range' => array_keys(self::optsRepaymentFrequency())],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['approvedby'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['approvedby' => 'id']],
            [['customerID'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customerID' => 'id']],
            [['initializedby'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['initializedby' => 'id']],
            [['paidby'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['paidby' => 'id']],
            [['loan_type_ID'], 'exist', 'skipOnError' => true, 'targetClass' => LoanType::class, 'targetAttribute' => ['loan_type_ID' => 'id']],
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
            'loan_type_ID' => 'Loan Type ID',
            'loan_amount' => 'Loan Amount',
            'topup_amount' => 'Topup Amount',
            'deposit_amount' => 'Deposit Amount',
            'repayment_frequency' => 'Repayment Frequency',
            'loan_duration_units' => 'Loan Duration Units',
            'duration_extended' => 'Duration Extended',
            'deposit_account' => 'Deposit Account',
            'deposit_account_names' => 'Deposit Account Names',
            'processing_fee_rate' => 'Processing Fee Rate',
            'processing_fee' => 'Processing Fee',
            'status' => 'Status',
            'interest_rate' => 'Interest Rate',
            'penalty_rate' => 'Penalty Rate',
            'topup_rate' => 'Topup Rate',
            'approvedby' => 'Approvedby',
            'initializedby' => 'Initializedby',
            'paidby' => 'Paidby',
            'approved_at' => 'Approved At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'isDeleted' => 'Is Deleted',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[Approvedby0]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getApprovedby0()
    {
        return $this->hasOne(User::class, ['id' => 'approvedby']);
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
     * Gets query for [[Initializedby0]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getInitializedby0()
    {
        return $this->hasOne(User::class, ['id' => 'initializedby']);
    }

    /**
     * Gets query for [[LoanAttachments]].
     *
     * @return \yii\db\ActiveQuery|LoanAttachmentQuery
     */
    public function getLoanAttachments()
    {
        return $this->hasMany(LoanAttachment::class, ['loanID' => 'id']);
    }

    /**
     * Gets query for [[LoanType]].
     *
     * @return \yii\db\ActiveQuery|LoanTypeQuery
     */
    public function getLoanType()
    {
        return $this->hasOne(LoanType::class, ['id' => 'loan_type_ID']);
    }

    /**
     * Gets query for [[Paidby0]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getPaidby0()
    {
        return $this->hasOne(User::class, ['id' => 'paidby']);
    }

    /**
     * Gets query for [[RepaymentSchedules]].
     *
     * @return \yii\db\ActiveQuery|RepaymentScheduleQuery
     */
    public function getRepaymentSchedules()
    {
        return $this->hasMany(RepaymentSchedule::class, ['loanID' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return CustomerLoanQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CustomerLoanQuery(get_called_class());
    }


    /**
     * column repayment_frequency ENUM value labels
     * @return string[]
     */
    public static function optsRepaymentFrequency()
    {
        return [
            self::REPAYMENT_FREQUENCY_DAILY => ucfirst('daily'),
            self::REPAYMENT_FREQUENCY_WEEKLY => ucfirst('weekly'),
            self::REPAYMENT_FREQUENCY_MONTHLY => ucfirst('monthly'),
            self::REPAYMENT_FREQUENCY_QUARTERLY => ucfirst('quarterly'),
            self::REPAYMENT_FREQUENCY_SEMI_ANNUALLY => ucfirst('semi-annually'),
            self::REPAYMENT_FREQUENCY_ANNUALLY => ucfirst('annually'),
        ];
    }

    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_NEW => 'new',
            self::STATUS_APPROVED => 'approved',
            self::STATUS_ACTIVE => 'active',
            self::STATUS_FINISHED => 'finished',
        ];
    }

    /**
     * @return string
     */
    public function displayRepaymentFrequency()
    {
        return self::optsRepaymentFrequency()[$this->repayment_frequency];
    }

    /**
     * @return bool
     */
    public function isRepaymentFrequencyDaily()
    {
        return $this->repayment_frequency === self::REPAYMENT_FREQUENCY_DAILY;
    }

    public function setRepaymentFrequencyToDaily()
    {
        $this->repayment_frequency = self::REPAYMENT_FREQUENCY_DAILY;
    }

    /**
     * @return bool
     */
    public function isRepaymentFrequencyWeekly()
    {
        return $this->repayment_frequency === self::REPAYMENT_FREQUENCY_WEEKLY;
    }

    public function setRepaymentFrequencyToWeekly()
    {
        $this->repayment_frequency = self::REPAYMENT_FREQUENCY_WEEKLY;
    }

    /**
     * @return bool
     */
    public function isRepaymentFrequencyMonthly()
    {
        return $this->repayment_frequency === self::REPAYMENT_FREQUENCY_MONTHLY;
    }

    public function setRepaymentFrequencyToMonthly()
    {
        $this->repayment_frequency = self::REPAYMENT_FREQUENCY_MONTHLY;
    }

    /**
     * @return bool
     */
    public function isRepaymentFrequencyQuarterly()
    {
        return $this->repayment_frequency === self::REPAYMENT_FREQUENCY_QUARTERLY;
    }

    public function setRepaymentFrequencyToQuarterly()
    {
        $this->repayment_frequency = self::REPAYMENT_FREQUENCY_QUARTERLY;
    }

    /**
     * @return bool
     */
    public function isRepaymentFrequencySemiAnnually()
    {
        return $this->repayment_frequency === self::REPAYMENT_FREQUENCY_SEMI_ANNUALLY;
    }

    public function setRepaymentFrequencyToSemiAnnually()
    {
        $this->repayment_frequency = self::REPAYMENT_FREQUENCY_SEMI_ANNUALLY;
    }

    /**
     * @return bool
     */
    public function isRepaymentFrequencyAnnually()
    {
        return $this->repayment_frequency === self::REPAYMENT_FREQUENCY_ANNUALLY;
    }

    public function setRepaymentFrequencyToAnnually()
    {
        $this->repayment_frequency = self::REPAYMENT_FREQUENCY_ANNUALLY;
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
    public function isStatusNew()
    {
        return $this->status === self::STATUS_NEW;
    }

    public function setStatusToNew()
    {
        $this->status = self::STATUS_NEW;
    }

    /**
     * @return bool
     */
    public function isStatusApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function setStatusToApproved()
    {
        $this->status = self::STATUS_APPROVED;
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
    public function isStatusFinished()
    {
        return $this->status === self::STATUS_FINISHED;
    }

    public function setStatusToFinished()
    {
        $this->status = self::STATUS_FINISHED;
    }
}
