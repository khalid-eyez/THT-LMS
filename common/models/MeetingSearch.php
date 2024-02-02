<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Meeting;

/**
 * MeetingSearch represents the model behind the search form of `common\models\Meeting`.
 */
class MeetingSearch extends Meeting
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['meetingID', 'announcedBy'], 'integer'],
            [['meetingTitle', 'description', 'type', 'meetingTime', 'venue', 'dateAnnounced'], 'safe'],
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
        $query = Meeting::find();

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
            'meetingID' => $this->meetingID,
            'meetingTime' => $this->meetingTime,
            'announcedBy' => $this->announcedBy,
            'dateAnnounced' => $this->dateAnnounced,
        ]);

        $query->andFilterWhere(['like', 'meetingTitle', $this->meetingTitle])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'venue', $this->venue]);

        return $dataProvider;
    }
}
