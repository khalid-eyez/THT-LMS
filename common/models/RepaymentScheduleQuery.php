<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[RepaymentSchedule]].
 *
 * @see RepaymentSchedule
 */
class RepaymentScheduleQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RepaymentSchedule[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RepaymentSchedule|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
