<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;

use Yii;
use yii\db\Expression;
use yii\base\Exception;
use yii\base\UserException;
use DateTime;
use common\exceptions\UnproccessableClaimException;

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
     public function beforeDelete()
     {
        $paid_deposits=$this->getDepositInterests()
    ->andWhere(['IS NOT', 'payment_date', null])
    ->andWhere(['IS NOT', 'approved_at', null])->all();

        if($paid_deposits!=null){
            throw new UserException('Cannot delete deposits having paid interests claims!');
        }
        return parent::beforeDelete();
     }

     public function beforeSave($insert)
     {
        if($insert && $this->type==self::TYPE_CAPITAL)
            {
               if($this->hasInitialDeposit($this->shareholderID))
                {
                    throw new UserException("Shareholder can deposit initial capital only once!");
                }

                $shareholder=Shareholder::findOne($this->shareholderID);
                $shareholder->initialCapital=$this->amount;

                if(!$shareholder->save())
                    {
                       throw new UserException("Could not update shareholder initial capital!"); 
                    }
            }

            if($insert && $this->type==self::TYPE_MONTHLY)
                {
                    if($this->shareholder->initialCapital==0 || $this->shareholder->initialCapital==null)
                        {
                          throw new UserException("Cannot record monthly deposit before initial capital deposit");
                        }
                }
        return parent::beforeSave($insert);
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
     public function getMonthlyInterestAmount(): float
    {
        return round(
            $this->amount * ((float)$this->interest_rate / 100),
            2
        );
    }

    /**
     * Total approved (already claimed) interest.
     */
    public function getTotalApprovedInterest(): float
    {
        $sum = $this->getDepositInterests()
            ->andWhere(['IS NOT', 'approved_at', null])
            ->sum('interest_amount');

        return (float) ($sum ?? 0);
    }
    public function getPayableInterests()
    {
       return $this->getDepositInterests()
    ->andWhere(['payment_date'=>null])
    ->andWhere(['IS NOT', 'approved_at', null])
    ->andWhere(['IS NOT', 'approved_by', null])
    ->all();  
    }

    public function pay($payment_date)
    {
        $payableinterests=$this->getPayableInterests();
        $total_paid=0;

        foreach($payableinterests as $payableinterest)
            {
                $payableinterest->payment_date=$payment_date;
                if(!$payableinterest->save())
                    {
                        throw new UserException("Unprocessable claim detected!");
                    }

                    $total_paid+=$payableinterest->interest_amount;
            }

            return $total_paid;
    }
    public function getTotalApprovableInterest(): float
    {
    $sum = $this->getDepositInterests()
        ->andWhere(['approved_at'=>null])
        ->sum('interest_amount');

    return (float) ($sum ?? 0);
    }
    public function getTotalPaidApprovedInterest(): float
    {
    $sum = $this->getDepositInterests()
    ->andWhere(['IS NOT', 'payment_date', null])
    ->andWhere(['IS NOT', 'approved_at', null])
    ->sum('interest_amount');

    return (float) ($sum ?? 0);
    }
    /**
     * Last approved interest claim.
     */
    public function getLastApprovedInterestClaim(): ?DepositInterest
    {
        return $this->getDepositInterests()
            ->andWhere(['IS NOT', 'approved_at', null])
            ->orderBy(['approved_at' => SORT_DESC])
            ->one();
    }

    public function getApprovableClaims()
    {
   
        return $this->getDepositInterests()
            ->andWhere(['approved_at'=>null])
            ->andWhere(['approved_by'=>null])
            ->all();
    
    }

    public function approve()
    {
        $approvables=$this->getApprovableClaims();
        if($approvables==null){return true;}

        foreach($approvables as $approvable)
            {
                $approvable->approve();
            }

            return true;
    }
   
    public function getLastInterestClaim(): ?DepositInterest
    {
        return $this->getDepositInterests()
            ->orderBy(['approved_at' => SORT_DESC])
            ->one();
    }

    /**
     * Number of FULL months elapsed since last approved claim
     * (or since deposit_date if none).
     */
    public function getElapsedClaimableMonths(): int
    {
        $lastInt = $this->getLastInterestClaim();

        $startDate = $lastInt && $lastInt->claim_date
            ? $lastInt->claim_date
            : $this->deposit_date;

        if (!$startDate) {
            return 0;
        }

        return $this->fullMonthsBetween(
            $startDate,
            date('Y-m-d H:i:s') // TODAY (hard-coded)
        );
    }

    /**
     * Total interest currently claimable (not yet approved).
     */
    public function getTotalClaimableInterest(): float
    {
        $months = $this->getElapsedClaimableMonths();

        if ($months <= 0) {
            return 0.0;
        }

        return round(
            $months * $this->getMonthlyInterestAmount(),
            2
        );
    }

    /**
     * Helper: count FULL completed months between two dates.
     */
    private function fullMonthsBetween(string $from, string $to): int
    {
        $fromDt = new DateTime($from);
        $toDt   = new DateTime($to);

        if ($toDt <= $fromDt) {
            return 0;
        }

        $months =
            ((int)$toDt->format('Y') - (int)$fromDt->format('Y')) * 12
            + ((int)$toDt->format('n') - (int)$fromDt->format('n'));

        // If current day < start day → last month not complete
        if ((int)$toDt->format('j') < (int)$fromDt->format('j')) {
            $months--;
        }

        return max(0, $months);
    }
    public function claimInterest(): DepositInterest
    {
        // 2) Calculate claimable months + amount
        $months = $this->getElapsedClaimableMonths(); // hardcoded "today" internally
        if ($months < 1) {
            throw new UnproccessableClaimException('No interest is currently claimable (less than 1 full month elapsed).');
        }

        $amount = $this->getTotalClaimableInterest(); // hardcoded "today" internally
        if ($amount <= 0) {
            throw new UnproccessableClaimException('Claimable interest amount is zero.');
        }

        // 3) Create DepositInterest row (pending approval)
        $tx = Yii::$app->db->beginTransaction();
        try {
            $interest = new DepositInterest();
            $interest->depositID = $this->depositID;
            $interest->interest_amount = $amount;
            $interest->claim_months = $months;

            // claim_date means "requested/created"
            $interest->claim_date = date('Y-m-d H:i:s');

            // leave payment_date, approved_at, approved_by as null (pending)
            // $interest->payment_date = null;
            // $interest->approved_at = null;
            // $interest->approved_by = null;

            if (!$interest->save()) {
                $errors = json_encode($interest->getErrors());
                throw new UserException("Failed to create interest claim record: {$errors}");
            }

            $tx->commit();
            return $interest;
        } catch (\Throwable $e) {
            $tx->rollBack();
            throw $e;
        }
    }
    public function hasInitialDeposit($shareholderID): bool
    {
    return self::find()
    ->where([
    'shareholderID' => $shareholderID,
    'type' => self::TYPE_CAPITAL,
    ])
    ->exists();
    }

}
