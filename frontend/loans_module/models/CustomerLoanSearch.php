<?php

namespace frontend\loans_module\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CustomerLoan;

/**
 * CustomerLoanSearch represents the model behind the search form of `common\models\CustomerLoan`.
 */
class CustomerLoanSearch extends CustomerLoan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'customerID', 'loan_type_ID', 'loan_duration_units', 'duration_extended', 'penalty_grace_days', 'approvedby', 'initializedby', 'paidby', 'isDeleted'], 'integer'],
            [['loan_amount', 'topup_amount', 'deposit_amount', 'processing_fee_rate', 'processing_fee', 'interest_rate', 'penalty_rate', 'topup_rate'], 'number'],
            [['repayment_frequency', 'deposit_account', 'deposit_account_names', 'status', 'approved_at', 'created_at', 'updated_at', 'deleted_at', 'loanID'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = CustomerLoan::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'customerID' => $this->customerID,
            'loan_type_ID' => $this->loan_type_ID,
            'loan_amount' => $this->loan_amount,
            'topup_amount' => $this->topup_amount,
            'deposit_amount' => $this->deposit_amount,
            'loan_duration_units' => $this->loan_duration_units,
            'duration_extended' => $this->duration_extended,
            'processing_fee_rate' => $this->processing_fee_rate,
            'processing_fee' => $this->processing_fee,
            'interest_rate' => $this->interest_rate,
            'penalty_rate' => $this->penalty_rate,
            'penalty_grace_days' => $this->penalty_grace_days,
            'topup_rate' => $this->topup_rate,
            'approvedby' => $this->approvedby,
            'initializedby' => $this->initializedby,
            'paidby' => $this->paidby,
            'approved_at' => $this->approved_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'isDeleted' => $this->isDeleted,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'repayment_frequency', $this->repayment_frequency])
            ->andFilterWhere(['like', 'deposit_account', $this->deposit_account])
            ->andFilterWhere(['like', 'deposit_account_names', $this->deposit_account_names])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'loanID', $this->loanID]);

        return $dataProvider;
    }
}
