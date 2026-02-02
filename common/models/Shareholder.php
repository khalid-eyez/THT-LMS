<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "shareholders".
 *
 * @property int $id
 * @property int $customerID
 * @property string $memberID
 * @property float $initialCapital
 * @property int $shares
 * @property int|null $isDeleted
 * @property string|null $deleted_at
 *
 * @property Customer $customer
 * @property Deposit[] $deposits
 */
class Shareholder extends \yii\db\ActiveRecord
{
public function behaviors()
{
    return [
     
        'auditBehaviour' => 'bedezign\yii2\audit\AuditTrailBehavior',
    ];
}


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shareholders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deleted_at'], 'default', 'value' => null],
            [['isDeleted'], 'default', 'value' => 0],
            [['customerID', 'memberID', 'initialCapital'], 'required'],
            [['customerID', 'shares', 'isDeleted'], 'integer'],
            [['initialCapital'], 'number'],
            [['deleted_at'], 'safe'],
            [['memberID'], 'string', 'max' => 20],
            [['customerID'], 'unique'],
            [['memberID'], 'unique'],
            [['customerID'], 'exist','skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customerID' => 'id']],
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
            'memberID' => 'Member ID',
            'initialCapital' => 'Initial Capital',
            'shares' => 'Shares',
            'isDeleted' => 'Is Deleted',
            'deleted_at' => 'Deleted At',
        ];
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
     * Gets query for [[Deposits]].
     *
     * @return \yii\db\ActiveQuery|DepositQuery
     */
    public function getDeposits()
    {
        return $this->hasMany(Deposit::class, ['shareholderID' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ShareholderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShareholderQuery(get_called_class());
    }

    public function getTotalApprovedInterests(): float
    {
        $total = 0.0;

        foreach ($this->deposits as $deposit) {
            /** @var Deposit $deposit */
            $total += (float) $deposit->totalApprovedInterest;
        }

        return round($total, 2);
    }
    public function getTotalPaidApprovedInterest(): float
    {
    $total = 0.0;

    foreach ($this->deposits as $deposit) {
    /** @var Deposit $deposit */
    $total += (float) $deposit->totalPaidApprovedInterest;
    }

    return round($total, 2);
    }
    /**
     * Total claimable interests for ALL deposits under this shareholder (today-based).
     */
    public function getTotalClaimableInterests(): float
    {
        $total = 0.0;

        foreach ($this->deposits as $deposit) {
            /** @var Deposit $deposit */
            $total += (float) $deposit->totalClaimableInterest;
        }

        return round($total, 2);
    }

    /**
     * Claim interest for ALL deposits under this shareholder.
     *
     * Behavior:
     * - Skips deposits with 0 claimable months/interest
     * - Skips deposits that already have a pending claim (claimInterest() throws)
     * - Continues processing others even if one fails
     *
     * Returns a summary:
     * [
     *   'created' => [ ['depositID'=>..., 'depositInterestID'=>..., 'months'=>..., 'amount'=>...], ...],
     *   'skipped' => [ ['depositID'=>..., 'reason'=>...], ...],
     *   'failed'  => [ ['depositID'=>..., 'error'=>...], ...],
     *   'totals'  => ['created_amount'=>..., 'created_count'=>...]
     * ]
     */
    public function claimAllInterests(): array
    {
        $result = [
            'created' => [],
            'skipped' => [],
            'failed'  => [],
            'totals'  => [
                'created_amount' => 0.0,
                'created_count'  => 0,
            ],
        ];

        foreach ($this->deposits as $deposit) {
            /** @var Deposit $deposit */

            $months = (int) $deposit->elapsedClaimableMonths;
            $amount = (float) $deposit->totalClaimableInterest;

            if ($months < 1 || $amount <= 0) {
                $result['skipped'][] = [
                    'depositID' => $deposit->depositID,
                    'reason'    => 'Nothing claimable (less than 1 full month).',
                ];
                continue;
            }

            try {
                $claim = $deposit->claimInterest();

                $result['created'][] = [
                    'depositID'          => $deposit->depositID,
                    'depositInterestID'  => $claim->id,
                    'months'             => (int) $claim->claim_months,
                    'amount'             => (float) $claim->interest_amount,
                ];

                $result['totals']['created_amount'] += (float) $claim->interest_amount;
                $result['totals']['created_count']  += 1;

            } catch (\Throwable $e) {
                // claimInterest() throws if pending claim exists or nothing claimable etc.
                $result['failed'][] = [
                    'depositID' => $deposit->depositID,
                    'error'     => $e->getMessage(),
                ];
            }
        }

        $result['totals']['created_amount'] = round((float)$result['totals']['created_amount'], 2);

        return $result;
    }

}
