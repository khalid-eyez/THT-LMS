<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "branch".
 *
 * @property int $branchID
 * @property string $branchName
 * @property string|null $branch_short
 * @property string|null $location
 * @property string|null $email
 * @property string|null $telphone
 * @property string|null $fax
 * @property string|null $website
 * @property string|null $pobox
 * @property string $level

 *
 * @property Meeting[] $meetings
 * @property Member[] $members
 * @property BranchAnnualBudget $branchBudget;
 */
class Branch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'branch';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branchName'], 'required'],
            [['branchName'], 'string', 'max' => 150],
            [['branch_short', 'pobox'], 'string', 'max' => 50],
            [['location'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 40],
            [['telphone', 'fax'], 'string', 'max' => 15],
            [['website'], 'string', 'max' => 30],
            [['branchName'], 'unique'],
            [['branch_short'], 'unique'],
            ['level','default','value'=>'BR']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'branchID' => 'Branch ID',
            'branchName' => 'Branch Name',
            'branch_short' => 'Branch Short',
            'location' => 'Location',
            'email' => 'Email',
            'telphone' => 'Telphone',
            'fax' => 'Fax',
            'website' => 'Website',
            'pobox' => 'Pobox',
        ];
    }

    /**
     * Gets query for [[Meetings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeetings()
    {
        return $this->hasMany(Meeting::className(), ['announcedFrom' => 'branchID']);
    }
    public function getBranchBudget()
    {
        return $this->hasOne(BranchAnnualBudget::className(),['branch'=>'branchID']);
    }

    /**
     * Gets query for [[Members]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(Member::className(), ['branch' => 'branchID']);
    }
    public function membersCount()
    {
        return count($this->members);
    }
    public function isHQ()
    {
        return $this->level=="HQ";
    }
    public function getHQ()
    {
        $HQ=$this->find()->where(['level'=>'HQ'])->one();

        return $HQ;
    }

    public function createAnnualBudgets($annualBudget)
    {
        $branches=$this->find()->all();
        $transaction=yii::$app->db->beginTransaction();
        try
        {
        foreach($branches as $branch)
        {
            $budget=new BranchAnnualBudget;
            $budget->budgetID=$annualBudget;
            $budget->projected_amount=0;
            $budget->branch=$branch->branchID;

            if(!$budget->save())
            {
                throw new \Exception("Could not create annual budget for ".$branch->branch_short);
            }
        }
        $transaction->commit();
        }
        catch(\Exception $s)
        {
           $transaction->rollBack();
           throw $s; 
        }
    }
}
