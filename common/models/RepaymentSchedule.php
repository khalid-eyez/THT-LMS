<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;
use frontend\cashbook_module\models\Cashbook as Book;
use frontend\loans_module\models\LoanCalculator;

use Yii;
use yii\db\Expression;
use yii\base\UserException;

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
    public function isDue($payment_date)
    {
        if($this->isStatusPaid() || $this->isStatusDelayed()){ return false;}
        $dueTs   = (new \DateTimeImmutable($this->repayment_date))->setTime(0, 0,0);
        $todayTs = (new \DateTimeImmutable($payment_date))->setTime(0, 0,0);

        if($dueTs<=$todayTs){
          return true;
        }
     return false;
    }
    public function isDelayed($payment_date)
    {
        $loan=$this->loan;
        $repayment_frequency=$loan->repayment_frequency;
        $gracedays=0;
        switch($repayment_frequency)
        {
            case $loan::REPAYMENT_FREQUENCY_MONTHLY:{
                 $gracedays=$loan->penalty_grace_days;
                break;
            }
             case $loan::REPAYMENT_FREQUENCY_QUARTERLY:{
                $gracedays=$loan->penalty_grace_days*3;
                break;
            }
              case $loan::REPAYMENT_FREQUENCY_SEMI_ANNUALLY:{
                $gracedays=$loan->penalty_grace_days*6;
                break;
            }
               case $loan::REPAYMENT_FREQUENCY_ANNUALLY:{
                $gracedays=$loan->penalty_grace_days*12;
                break;
            }
               case $loan::REPAYMENT_FREQUENCY_WEEKLY:{
                $gracedays=round(($loan->penalty_grace_days/30)*7);
                break;
            }
                case $loan::REPAYMENT_FREQUENCY_DAILY:{
                    $gracedays=0;
                break;
            }
        }
        
        $due=(new \DateTimeImmutable($this->repayment_date))->setTime(0, 0,0);
        $due=$due->add(new \DateInterval("P{$gracedays}D"));
        $payment_date=(new \DateTimeImmutable($payment_date))->setTime(0, 0,0);
        if($payment_date > $due)
            {
                return true;
            }
            return false;

    }
    public function isPayable()
    {
        return ($this->status!="delayed" && $this->status!="paid");
    }
    public function pay($payment_date,$paid_amount=0,$paymentdoc=null)
    {
        $transaction=yii::$app->db->beginTransaction();
            try{
            
            if(!$this->isPayable())
                {
                    throw new UserException("Repayment Due Not Payable");
                }
            $isdelayed=$this->isDelayed($payment_date);
      
            $statement=new RepaymentStatement();
            $lastRepayment=$this->loan->getLastRepayment();

            $statement->scheduleID=$this->id;
            $statement->payment_date=$payment_date;
            $statement->loan_amount=$lastRepayment?->balance??$this->loan->totalRepayment(); // handle balance for the first repayment
            $statement->principal_amount=$this->principle_amount;
            $statement->interest_amount=$this->interest_amount;
            $statement->installment=$this->installment_amount;
            $statement->paid_amount=$paid_amount;

            $overdues=$this->loan->overdues();

            //if it's the last due must clear all overdues
            if($this->isLastDue())
                {
               $total_penalties=$overdues['total_penalties'];
               $loan_balance=$lastRepayment->balance; 
               
                $total_dues=$total_penalties+$loan_balance;
                if($paid_amount<$total_dues)
                    {
                        throw new UserException("Last repayment due must close the loan, please pay the whole amount of ".yii::$app->formatter->asDecimal($total_dues));
                    }
                }
          
            $paid_installment=min($this->installment_amount,$paid_amount);
            $paid_amount-=$paid_installment;


            //clearing the unpaid

            $unpaidPaid = 0;
            if ($paid_amount > 0 && $overdues['total_unpaid'] > 0) {
            $unpaidPaid = min($paid_amount, $overdues['total_unpaid']);
            $paid_amount -= $unpaidPaid;
            }

            //clearing the penalties

            $penaltyPaid = 0;
            if ($paid_amount > 0 && $overdues['total_penalties'] > 0) {
            $penaltyPaid = min($paid_amount, $overdues['total_penalties']);
            $paid_amount -= $penaltyPaid;
            }


           //prepayment
           $prepayment = max(0, $paid_amount);


            //resolving the unpaid amount
            $statement->unpaid_amount  =
            $this->installment_amount - ($paid_installment+$unpaidPaid);

            $penalty_rate=$this->loan->penalty_rate;
            $penalty_rate=$penalty_rate/100;
            $penalty=round(($statement->unpaid_amount*$penalty_rate),2);
            $statement->penalty_amount=($isdelayed)?$penalty:0;

            $statement->penalty_amount-=$penaltyPaid;
            $statement->prepayment=$prepayment;
            $balance=($isdelayed)?$statement->loan_amount:($statement->loan_amount-($statement->paid_amount-$penaltyPaid));
            if($balance<=-1)
                {
                  throw new UserException('Overpayment detected !');
                }
            $statement->balance=$balance;
            $statement->loanID=$this->loan->id;

            //updating the loan if the balance is 0 then the loan is marked
            //as finished

             $loan=$this->loan;
             if($balance<1)
                {
                    $loan->status="finished";
                    if(!$loan->save())
                        {
                            throw new UserException("Could not update loan status!");
                        }
                }

            //persistence

            if(!$statement->save()){
                throw new UserException("could not update loan statement".json_encode($statement->getErrors()));
            }
            $this->status=($isdelayed)?"delayed":"paid";

            if(!$this->save()){
                throw new UserException("could not update repayment schedule");
            }

            //now time for the cashbook record
            $bookrecord=null;
         if($statement->paid_amount!=0)
            {
            $cashbook_record=[
                'credit'=>0,
                'debit'=>$statement->paid_amount,
                'description'=>'['.$this->loan->loanID.'] Repayment',
                'payment_doc'=>$paymentdoc,
                'category'=>'Repayment'
                
            ];
            $book=new Book($cashbook_record);
            $bookrecord=$book->save('RP',$this->loan->id);
            }

             $transaction->commit();
              return [
                'statement'=>$statement,
                'reference'=>$bookrecord?->reference_no
              ];
            }
            catch(UserException $u)
            {
             $transaction->rollBack();
             throw $u;
            }
            catch(\Exception $e)
            {
             $transaction->rollBack();
             throw $e;
            }

            
        }
        public function isLastDue()
        {
        $lastDue = self::find()
        ->where([
        'loanID'     => $this->loanID,
        'isDeleted'  => 0,
        ])
        ->orderBy(['repayment_date' => SORT_DESC, 'id' => SORT_DESC]) // tie-breaker
        ->one();

        if (!$lastDue) {
        return false;
        }

        return (int)$lastDue->id === (int)$this->id;
        }
        public function pay_dry_run($payment_date,$paid_amount=0,$paymentdoc=null)
        {
           
            $isdelayed=$this->isDelayed($payment_date);
              if(!$this->isPayable())
                {
                    throw new UserException("Repayment Due Not Payable");
                }
      
            $statement=new RepaymentStatement();
            $lastRepayment=$this->loan->getLastRepayment();

            $statement->scheduleID=$this->id;
            $statement->payment_date=$payment_date;
            $statement->loan_amount=$lastRepayment?->balance??$this->loan->totalRepayment(); // handle balance for the first repayment
            $statement->principal_amount=$this->principle_amount;
            $statement->interest_amount=$this->interest_amount;
            $statement->installment=$this->installment_amount;
            $statement->paid_amount=$paid_amount;

            $overdues=$this->loan->overdues();
              if($this->isLastDue())
                {
               $total_penalties=$overdues['total_penalties'];
               $loan_balance=$lastRepayment->balance; 
               
                $total_dues=$total_penalties+$loan_balance;
                if(round($paid_amount)<round($total_dues))
                    {
                        throw new UserException("Last repayment due must close the loan, please pay the whole amount of ".yii::$app->formatter->asDecimal($total_dues));
                    }
                }
            
            $paid_installment=min($this->installment_amount,$paid_amount);
            $paid_amount-=$paid_installment;


            //clearing the unpaid

            $unpaidPaid = 0;
            if ($paid_amount > 0 && $overdues['total_unpaid'] > 0) {
            $unpaidPaid = min($paid_amount, $overdues['total_unpaid']);
            $paid_amount -= $unpaidPaid;
            }

            //clearing the penalties

            $penaltyPaid = 0;
            if ($paid_amount > 0 && $overdues['total_penalties'] > 0) {
            $penaltyPaid = min($paid_amount, $overdues['total_penalties']);
            $paid_amount -= $penaltyPaid;
            }


           //prepayment
           $prepayment = max(0, $paid_amount);


            //resolving the unpaid amount
            $statement->unpaid_amount  =
            $this->installment_amount - ($paid_installment+$unpaidPaid);

            $penalty_rate=$this->loan->penalty_rate;
            $penalty_rate=$penalty_rate/100;
            $penalty=round(($statement->unpaid_amount*$penalty_rate),2);
            $statement->penalty_amount=($isdelayed)?$penalty:0;

            $statement->penalty_amount-=$penaltyPaid;
            $statement->prepayment=$prepayment;
            $balance=($isdelayed)?$statement->loan_amount:($statement->loan_amount-($statement->paid_amount-$penaltyPaid));
            if($balance<=-1)
                {
                  throw new UserException('Overpayment detected !');
                }
            $statement->balance=$balance;
            $statement->loanID=$this->loan->id;
            $this->status=($isdelayed)?"delayed":"paid";


            return [
                'statement'=>$statement,
                'repayment_due'=>$this,
                'payment_doc'=>$paymentdoc
            ];
           
        }
    

       
}
