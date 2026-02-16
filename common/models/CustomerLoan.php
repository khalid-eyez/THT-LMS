<?php

namespace common\models;
use frontend\loans_module\models\LoanCalculator;
use yii\base\UserException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Yii;
use common\helpers\UniqueCodeHelper;
use Throwable;

/**
 * This is the model class for table "customer_loans".
 *
 * @property int $id
 * @property int $customerID
 * @property int $loan_type_ID
 * @property string $loanID
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
 * @property int $penalty_grace_days
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
 * @property RepaymentStatement[] $repaymentStatements
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
    const STATUS_DISAPPROVED = 'rejected';
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
            [['customerID', 'loan_type_ID', 'loan_amount', 'deposit_amount', 'loan_duration_units', 'processing_fee_rate', 'processing_fee', 'status', 'interest_rate', 'penalty_rate', 'topup_rate', 'initializedby','penalty_grace_days','loanID'], 'required'],
            [['customerID', 'loan_type_ID', 'loan_duration_units', 'approvedby', 'initializedby', 'paidby', 'isDeleted'], 'integer'],
             [['duration_extended'], 'default', 'value' => false],
             [['duration_extended'], 'boolean'],
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
            'loanID'=>'Loan ID',
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
            'approvedby' => 'Approved by',
            'initializedby' => 'Initialized by',
            'paidby' => 'Paid by',
            'approved_at' => 'Approved At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'isDeleted' => 'Is Deleted',
            'deleted_at' => 'Deleted At',
        ];
    }
public function beforeSave($insert)
{
            if($insert)
                {
            if ($this->customer && $this->customer->hasActiveLoan()) {
            throw new UserException('Customer has another active loan !');
            }
                }
    
          
    return parent::beforeSave($insert);
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
            self::STATUS_DISAPPROVED => 'rejected',
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
     public function setStatusToDisapproved()
    {
        $this->status = self::STATUS_DISAPPROVED;
    }
    public function isToppedUp()
    {
        return $this->topup_amount!=0;
    }
    public function getLastRepayment()
    {
        $lastStatement = $this->getRepaymentStatements()
        ->orderBy(['id' => SORT_DESC])
        ->one(); 
        
        return $lastStatement;
    }
    public function getRepaymentStatements()
    {
        return $this->hasMany(RepaymentStatement::class, ['loanID' => 'id']);
    }
    public function overdues()
    {
        return [
            'total_unpaid'=>$this->getRepaymentStatements()->sum('unpaid_amount'),
            'total_penalties'=>$this->getRepaymentStatements()->sum('penalty_amount'),

        ];

    }
    public function totalRepayment()
    {
        return $this->getRepaymentSchedules()->sum('installment_amount');
    }
    public function total_paid()
    {
        return $this->getRepaymentStatements()->sum('paid_amount');
    }
    public function repayment_ratio(){
     $total_repayment=$this->totalRepayment();
     $total_paid=$this->total_paid();

     return ($total_paid *100)/(($total_repayment==0)?1:$total_repayment);

    }
     public function totalInterest()
    {
        return $this->getRepaymentSchedules()->sum('interest_amount');
    }

    public function overduesSimulate($payment_date)
    {
      $transaction=yii::$app->db->beginTransaction();
      try{
          $overdues=$this->computeOverdues($payment_date);
          $transaction->rollBack();
          return  $overdues;
      }
      catch(UserException $u)
      {
          $transaction->rollBack(); 
          throw new UserException($u->getMessage());
      }
      catch(\Throwable $t)
      {
         $transaction->rollBack(); 
         throw new UserException("Could not fetch any payment dues for the selected date");
      }
    }
    public function computeOverdues($payment_date)
    {
        if($this->isStatusFinished())
            {
                throw new UserException("Loan Repayment Finished !");
            }
       $dues=$this->repaymentSchedules;

       foreach($dues as $due)
        {
              if(!$due->isDue($payment_date)){ continue; }
            
              if($due->isDelayed($payment_date) && !$due->isLastDue())
                {
                    $due->pay($payment_date);
                    continue;
                }
                $overdues=$this->overdues();
                $overdues['installment']=$due->installment_amount;
                $overdues['total_repayment']=$overdues['installment']+$overdues['total_penalties']+$overdues['total_unpaid'];
                $overdues['due']=$due;
                return $overdues;
        }

        throw new UserException("No repayment dues found for ".$payment_date);

    }
    public function overduesSync($payment_date)
    {
       $dues=$this->repaymentSchedules;

       foreach($dues as $due)
        {
              if(!$due->isDue($payment_date)){ continue; }
              if($due->isDelayed($payment_date))
                {
                    $due->pay($payment_date);
                    continue;
                }
           
        }

    }
    public function isTopupAllowed()
    {
        if($this->isToppedUp()){
            throw new UserException("Loan top-up is allowed only once !");
        }
        $topup_rate=$this->topup_rate;
        $paid_installments=$this->getRepaymentSchedules()
    ->andWhere(['status' => 'paid'])
    ->count();

        $total_installments=$this->loan_duration_units;

        $repayment_rate=round((($paid_installments*100)/$total_installments),2);

        //checking and updating  overdues

        $this->overduesSync(date("Y-m-d H:i:s"));

        //fetching the up to date overdues

        $total_penalties=$this->overdues()['total_penalties'];
        $total_unpaid=$this->overdues()['total_unpaid'];

        return ($repayment_rate>=$topup_rate && $total_penalties==0 && $total_unpaid==0);


    }
    public function topUp($topup_amount,$mode,$extension_periods,$document)
    {
        if(!$this->isTopupAllowed())
            {
                 throw new UserException("Customer has not reached the repayment rate of ".$this->topup_rate." % or has overdues");
            }
        //update the loan itself

        $this->duration_extended=true;
        $this->loan_duration_units+=$extension_periods;
        $this->topup_amount+=$topup_amount;

        if(!$this->save())
            {
                throw new UserException("Could not update loan details!".json_encode($this->getErrors()));
            }

        $total_interest=$this->totalInterest(); //interest for the original schedule

        //update the schedule

        $this->topup_schedule_update($topup_amount,$mode,$extension_periods);
        $this->populateRelation('repaymentSchedules', $this->getRepaymentSchedules()->all());
        
        $total_loan=$this->topup_amount + $this->loan_amount;
        $extended_duration=$this->loan_duration_units;

        if($mode=='tenure_retention')
            {
              $topup_interest=$this->totalInterest()-$total_interest;
            }
            else
                {
                  $topup_interest=$this->totalInterest()-$this->interestb4extension($extended_duration,$total_loan);
                }

        $repayment_topup=$topup_amount+$topup_interest;
        //update the loan statement

        $this->updateRepaymentStatement($repayment_topup);

        //update the cashbook

        $cashbook=new Cashbook();
        $cashbook->credit=$topup_amount;
        $cashbook->reference_no=UniqueCodeHelper::generate("TU").'-'.$this->id.date("Y");
        $cashbook->description="[$this->loanID] Loan Topping up";
        $cashbook->payment_document=$document;
        $cashbook->category="disbursement";
        $cashbook->balance=$cashbook->updatedBalance();

        if(!$cashbook->save())
            {
                throw new UserException("Could not update the cashbook !");
            }

            
        $this->populateRelation('repaymentSchedules', $this->getRepaymentSchedules()->all());


    }
    public function topup_schedule_update($topup_amount,$mode,$extension_periods=0)
    {
      
      if($mode=="tenure_retention")
        {
            $this->pruneActiveScheduleDue();
            $lastDue=$this->getLastDue();
            $loan_duration=$this->loan_duration_units-count($this->repaymentSchedules);

        }
        else{
             $lastDue=$this->getLastDue();
             $loan_duration=$extension_periods;
        }
        

        $loan_amount=$lastDue->loan_balance+$topup_amount;

        //updating the latest due

        $lastDue->loan_balance=$loan_amount;

        if(!$lastDue->save())
            {
             throw new UserException("Could not update the repayment schedule !");
            }

        $schedules=(new LoanCalculator())->generateRepaymentSchedule($loan_amount,$this->interest_rate, $this->repayment_frequency,$loan_duration,$lastDue->repayment_date);

         foreach($schedules as $record)
                    {
                       $repaymodel=new RepaymentSchedule();
                       $repaymodel->loanID=$this->id;
                       $repaymodel->loan_amount=$record['loan_amount'];
                       $repaymodel->interest_amount=$record['interest'];
                       $repaymodel->installment_amount=$record['installment'];
                       $repaymodel->loan_balance=$record['balance'];
                       $repaymodel->principle_amount=$record['principal'];
                       $repaymodel->repayment_date=$record['payment_date'];
                       $repaymodel->status="active";
                       if(!$repaymodel->save())
                        {
                            throw new UserException("Could not update the repayment schedule !");
                        }

                    }

    }

    public function updateRepaymentStatement($topup_amount)
    {
         $lastrepayment=$this->getLastRepayment();
         $latest_balance=($lastrepayment)?($lastrepayment->balance+$topup_amount):$this->totalRepayment(); 

         $statement=new RepaymentStatement();
         $statement->loanID=$this->id;
         $statement->loan_amount=($lastrepayment)?$lastrepayment->balance:$this->totalRepayment();
         $statement->installment=0;
         $statement->balance=$latest_balance;
         $statement->unpaid_amount=0;
         $statement->paid_amount=0;
         $statement->interest_amount=0;
         $statement->prepayment=0;
         $statement->penalty_amount=0;
         $statement->principal_amount=0;
         $statement->topup_amount=$topup_amount;
         $statement->payment_date=date("Y-m-d H:i:s");

         if(!$statement->save())
            {
                throw new UserException("Could not update the loan statement !".json_encode($statement->getErrors()));
            }
    }
    public function getLastDue()
    {
        $lastDue = $this->getRepaymentSchedules()
        ->orderBy(['id' => SORT_DESC])
        ->one(); 
        
        return $lastDue;
    }
    public function pruneActiveScheduleDue()
    {
        $scheduleDues=$this->repaymentSchedules;

        foreach($scheduleDues as $scheduleDue)
            {
                if($scheduleDue->isStatusPaid() || $scheduleDue->isStatusDelayed()){
                   continue;
                }
                $deleted = RepaymentSchedule::deleteAll(['id' => $scheduleDue->id]);
                if (!$deleted) {
                throw new UserException("Could not delete schedule: ".$scheduleDue->id);
                }
            }
            $this->populateRelation('repaymentSchedules', $this->getRepaymentSchedules()->all());

    }
    public function interestb4extension($duration,$loan_amount)
    {
        $schedules=(new LoanCalculator())->generateRepaymentSchedule($loan_amount,$this->interest_rate, $this->repayment_frequency,$duration,date('Y-m-d H:i:s'));
        $totalInterest = array_sum(array_column($schedules, 'interest_amount'));

        return $totalInterest;


    }
    /**
 * Executive summary from repayment statements.
 * - Sums all numeric columns
 * - Returns latest loan_amount and balance (not summed)
 *
 * @return array
 */
public function getRepaymentExecutiveSummary(): array
{
    $latest = $this->getRepaymentStatements()
        ->orderBy(['id' => SORT_DESC])
        ->one();

    // No statements yet → return safe defaults
    if (!$latest) {
        return [
            'customerID' => $this->customer->customerID,
            'loanID' => $this->loanID,

            'loan_amount' => 0,
            'balance' => 0,

            'installment_total' => 0,
            'unpaid_amount_total' => 0,
            'paid_amount_total' => 0,
            'interest_amount_total' => 0,
            'prepayment_total' => 0,
            'penalty_amount_total' => 0,
            'principal_amount_total' => 0,
            'topup_amount_total' => 0,
        ];
    }

    // Aggregate totals in a single query
    $totals = $this->getRepaymentStatements()
        ->select([
            'installment_total'      => 'COALESCE(SUM(installment),0)',
            'unpaid_amount_total'    => 'COALESCE(SUM(unpaid_amount),0)',
            'paid_amount_total'      => 'COALESCE(SUM(paid_amount),0)',
            'interest_amount_total'  => 'COALESCE(SUM(interest_amount),0)',
            'prepayment_total'       => 'COALESCE(SUM(prepayment),0)',
            'penalty_amount_total'   => 'COALESCE(SUM(penalty_amount),0)',
            'principal_amount_total' => 'COALESCE(SUM(principal_amount),0)',
            'topup_amount_total'     => 'COALESCE(SUM(topup_amount),0)',
        ])
        ->asArray()
        ->one();

    return array_merge(
        [
            'customerID' => $this->customer->customerID,
            'loanID' => $this->loanID,

            // latest values (not summed)
            'loan_amount' => (float) $latest->loan_amount,
            'balance' => (float) $latest->balance,
        ],
        array_map('floatval', $totals ?? [])
    );
}

}
