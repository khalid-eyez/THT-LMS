<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use Yii;

/**
 * This is the model class for table "repayment_statement".
 *
 * @property int $id
 * @property int|null $scheduleID
 * @property int $loanID;
 * @property string $payment_date
 * @property float $loan_amount
 * @property float $principal_amount
 * @property float $interest_amount
 * @property float $installment
 * @property float $paid_amount
 * @property float $unpaid_amount
 * @property float $penalty_amount
 * @property float $prepayment
 * @property float $topup_amount
 * @property float|null $balance
 *
 * @property RepaymentSchedule $schedule
 */
class RepaymentStatement extends \yii\db\ActiveRecord
{

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
        return 'repayment_statement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scheduleID', 'balance'], 'default', 'value' => null],
            [['prepayment','topup_amount'], 'default', 'value' => 0.00],
            [['scheduleID','loanID'], 'integer'],
            [['payment_date', 'loan_amount', 'principal_amount', 'interest_amount', 'installment','loanID'], 'required'],
            [['payment_date','created_at', 'updated_at'], 'safe'],
            [['loan_amount', 'principal_amount', 'interest_amount', 'installment', 'paid_amount', 'unpaid_amount', 'penalty_amount', 'prepayment', 'balance','topup_amount'], 'number'],
            [['scheduleID'], 'exist', 'skipOnError' => true, 'targetClass' => RepaymentSchedule::class, 'targetAttribute' => ['scheduleID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'scheduleID' => 'Schedule ID',
            'payment_date' => 'Payment Date',
            'loan_amount' => 'Loan Amount',
            'principal_amount' => 'Principal Amount',
            'interest_amount' => 'Interest Amount',
            'installment' => 'Installment',
            'paid_amount' => 'Paid Amount',
            'unpaid_amount' => 'Unpaid Amount',
            'penalty_amount' => 'Penalty Amount',
            'prepayment' => 'Prepayment',
            'balance' => 'Balance',
        ];
    }

    /**
     * Gets query for [[Schedule]].
     *
     * @return \yii\db\ActiveQuery|RepaymentScheduleQuery
     */
    public function getSchedule()
    {
        return $this->hasOne(RepaymentSchedule::class, ['id' => 'scheduleID']);
    }

    /**
     * {@inheritdoc}
     * @return RepaymentStatementQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RepaymentStatementQuery(get_called_class());
    }
    public function getLoan()
    {
        return $this->hasOne(CustomerLoan::class, ['id' => 'loanID']);
    }

}
