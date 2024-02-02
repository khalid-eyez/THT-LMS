<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TblAuditEntry;

/**
 * TblAuditEntrySearch represents the model behind the search form of `common\models\TblAuditEntry`.
 */
class TblAuditEntrySearch extends TblAuditEntry
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['audit_entry_id'], 'integer'],
            [['audit_entry_timestamp', 'audit_entry_model_name', 'audit_entry_operation', 'audit_entry_field_name', 'audit_entry_old_value', 'audit_entry_new_value', 'audit_entry_user_id', 'audit_entry_ip', 'audit_entry_affected_record_reference', 'audit_entry_affected_record_reference_type'], 'safe'],
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TblAuditEntry::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'audit_entry_id' => $this->audit_entry_id,
        ]);

        $query->andFilterWhere(['like', 'audit_entry_timestamp', $this->audit_entry_timestamp])
            ->andFilterWhere(['like', 'audit_entry_model_name', $this->audit_entry_model_name])
            ->andFilterWhere(['like', 'audit_entry_operation', $this->audit_entry_operation])
            ->andFilterWhere(['like', 'audit_entry_field_name', $this->audit_entry_field_name])
            ->andFilterWhere(['like', 'audit_entry_old_value', $this->audit_entry_old_value])
            ->andFilterWhere(['like', 'audit_entry_new_value', $this->audit_entry_new_value])
            ->andFilterWhere(['like', 'audit_entry_user_id', $this->audit_entry_user_id])
            ->andFilterWhere(['like', 'audit_entry_ip', $this->audit_entry_ip])
            ->andFilterWhere(['like', 'audit_entry_affected_record_reference', $this->audit_entry_affected_record_reference])
            ->andFilterWhere(['like', 'audit_entry_affected_record_reference_type', $this->audit_entry_affected_record_reference_type]);

        return $dataProvider;
    }
}
