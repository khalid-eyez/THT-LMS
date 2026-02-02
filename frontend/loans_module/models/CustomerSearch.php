<?php

namespace frontend\loans_module\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Customer;

/**
 * CustomerSearch represents the model behind the search form of `common\models\Customer`.
 */
class CustomerSearch extends Customer
{
    public $date_range;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'userID', 'isDeleted'], 'integer'],
            [['customerID', 'full_name','date_range', 'birthDate', 'gender', 'address', 'contacts', 'NIN', 'TIN', 'status', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
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
        \Yii::error(['date_range' => $this->date_range], 'export-debug');

        $query = Customer::find();

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
            'userID' => $this->userID,
            'birthDate' => $this->birthDate,
            'isDeleted' => $this->isDeleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'customerID', $this->customerID])
            ->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'contacts', $this->contacts])
            ->andFilterWhere(['like', 'NIN', $this->NIN])
            ->andFilterWhere(['like', 'TIN', $this->TIN])
            ->andFilterWhere(['like', 'status', $this->status]);

               if (!empty($this->date_range)) {
            [$start, $end] = explode(' - ', $this->date_range);
            $query->andFilterWhere(['between', 'DATE(created_at)', $start, $end]);
            }

        return $dataProvider;
    }
}
