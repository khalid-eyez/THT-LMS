<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "goals".
 *
 * @property int $goalID
 * @property string|null $code
 * @property string $description
 * @property string|null $createdAt
 * @property string|null $updatedAt
 *
 * @property Strategies[] $strategies
 */
class Goals extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'goals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['description'], 'string'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'goalID' => 'Goal ID',
            'code' => 'Code',
            'description' => 'Description',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Strategies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStrategies()
    {
        return $this->hasMany(Strategies::className(), ['goal' => 'goalID']);
    }

    public function completionStatus()
    {
        $strategies=$this->strategies;
        $total=0;
        if($strategies==null)
        {
            return 0;
        }
        foreach($strategies as $strategy)
        {
          $total+=$strategy->getCompletionStatus();
        }

        return $total;
    }
    public function getCompletionstatus()
    {
        return $this->completionStatus()/(($this->strategies!=null)?count($this->strategies):1); 
    }

    public function getAVGCompletionStatus()
    {
        $goals=$this->find()->all();

        $total=0;
        if($goals==null){return 0;}

        foreach($goals as $goal)
        {
            $total+=$goal->getCompletionstatus();
        }

        return $total/(($goals!=null)?count($goals):1);

    }
}
