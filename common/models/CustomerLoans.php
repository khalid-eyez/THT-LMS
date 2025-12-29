<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "customer_loans".
 *
 * @property int $id
 * @property int $customerID
 * @property int $loan_type_ID
 * @property float $amount
 * @property string $repayment_frequency
 * @property int $loan_duration_units
 * @property int $approvedby
 * @property string|null $approved_at
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $isDeleted
 * @property string|null $deleted_at
 *
 * @property User $approvedby0
 * @property Customers $customer
 * @property LoanAttachments[] $loanAttachments
 * @property LoanTypes $loanType
 * @property RepaymentSchedule[] $repaymentSchedules
 */
class CustomerLoans extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const REPAYMENT_FREQUENCY_WEEK = 'week';
    const REPAYMENT_FREQUENCY_MONTH = 'month';
    const REPAYMENT_FREQUENCY_YEAR = 'year';

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
            [['approved_at', 'deleted_at'], 'default', 'value' => null],
            [['isDeleted'], 'default', 'value' => 0],
            [['customerID', 'loan_type_ID', 'amount', 'repayment_frequency', 'loan_duration_units', 'approvedby'], 'required'],
            [['customerID', 'loan_type_ID', 'loan_duration_units', 'approvedby', 'isDeleted'], 'integer'],
            [['amount'], 'number'],
            [['repayment_frequency'], 'string'],
            [['approved_at', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            ['repayment_frequency', 'in', 'range' => array_keys(self::optsRepaymentFrequency())],
            [['approvedby'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['approvedby' => 'id']],
            [['customerID'], 'exist', 'skipOnError' => true, 'targetClass' => Customers::class, 'targetAttribute' => ['customerID' => 'id']],
            [['loan_type_ID'], 'exist', 'skipOnError' => true, 'targetClass' => LoanTypes::class, 'targetAttribute' => ['loan_type_ID' => 'id']],
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
            'amount' => 'Amount',
            'repayment_frequency' => 'Repayment Frequency',
            'loan_duration_units' => 'Loan Duration Units',
            'approvedby' => 'Approvedby',
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
     * @return \yii\db\ActiveQuery|CustomersQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customers::class, ['id' => 'customerID']);
    }

    /**
     * Gets query for [[LoanAttachments]].
     *
     * @return \yii\db\ActiveQuery|LoanAttachmentsQuery
     */
    public function getLoanAttachments()
    {
        return $this->hasMany(LoanAttachments::class, ['loanID' => 'id']);
    }

    /**
     * Gets query for [[LoanType]].
     *
     * @return \yii\db\ActiveQuery|LoanTypesQuery
     */
    public function getLoanType()
    {
        return $this->hasOne(LoanTypes::class, ['id' => 'loan_type_ID']);
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
     * @return CustomerLoansQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CustomerLoansQuery(get_called_class());
    }


    /**
     * column repayment_frequency ENUM value labels
     * @return string[]
     */
    public static function optsRepaymentFrequency()
    {
        return [
            self::REPAYMENT_FREQUENCY_WEEK => 'week',
            self::REPAYMENT_FREQUENCY_MONTH => 'month',
            self::REPAYMENT_FREQUENCY_YEAR => 'year',
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
    public function isRepaymentFrequencyWeek()
    {
        return $this->repayment_frequency === self::REPAYMENT_FREQUENCY_WEEK;
    }

    public function setRepaymentFrequencyToWeek()
    {
        $this->repayment_frequency = self::REPAYMENT_FREQUENCY_WEEK;
    }

    /**
     * @return bool
     */
    public function isRepaymentFrequencyMonth()
    {
        return $this->repayment_frequency === self::REPAYMENT_FREQUENCY_MONTH;
    }

    public function setRepaymentFrequencyToMonth()
    {
        $this->repayment_frequency = self::REPAYMENT_FREQUENCY_MONTH;
    }

    /**
     * @return bool
     */
    public function isRepaymentFrequencyYear()
    {
        return $this->repayment_frequency === self::REPAYMENT_FREQUENCY_YEAR;
    }

    public function setRepaymentFrequencyToYear()
    {
        $this->repayment_frequency = self::REPAYMENT_FREQUENCY_YEAR;
    }
}
