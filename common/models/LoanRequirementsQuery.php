<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[LoanRequirements]].
 *
 * @see LoanRequirements
 */
class LoanRequirementsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return LoanRequirements[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return LoanRequirements|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
