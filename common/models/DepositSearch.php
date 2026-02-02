<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Deposit;

/**
 * DepositSearch represents the model behind the search form of `common\models\Deposit`.
 */
class DepositSearch extends Deposit
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['depositID', 'shareholderID', 'isDeleted'], 'integer'],
            [['amount', 'interest_rate'], 'number'],
            [['type', 'deposit_date', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
     * Modes:
     * - Locked (shareholder context): pass $contextShareholderID (hard filter)
     * - General search: do not pass $contextShareholderID (optional filter by $this->shareholderID)
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     * @param int|null $contextShareholderID If provided, results are scoped to this shareholder only.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null, $contextShareholderID = null)
    {
        $query = Deposit::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            return $dataProvider;
        }

        /**
         * Shareholder filtering:
         * - If contextShareholderID is provided => hard lock to it
         * - Else => allow optional filtering by shareholderID from the search form
         */
        if ($contextShareholderID !== null && $contextShareholderID !== '') {
            $query->andWhere(['shareholderID' => (int)$contextShareholderID]);
        } else {
            $query->andFilterWhere(['shareholderID' => $this->shareholderID]);
        }

        /**
         * deposit_date supports:
         * - date range string: "YYYY-MM-DD - YYYY-MM-DD"
         * - single date: "YYYY-MM-DD"
         */
        if (!empty($this->deposit_date) && strpos($this->deposit_date, ' - ') !== false) {
            [$from, $to] = explode(' - ', $this->deposit_date);

            $from = trim($from);
            $to   = trim($to);

            // If deposit_date is DATETIME in DB, uncomment:
            // $from .= ' 00:00:00';
            // $to   .= ' 23:59:59';

            $query->andWhere(['between', 'deposit_date', $from, $to]);
        } else {
            $query->andFilterWhere(['deposit_date' => $this->deposit_date]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'depositID' => $this->depositID,
            'amount' => $this->amount,
            'interest_rate' => $this->interest_rate,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'isDeleted' => $this->isDeleted,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
