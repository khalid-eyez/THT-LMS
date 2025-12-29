<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[LoanCategories]].
 *
 * @see LoanCategories
 */
class LoanCategoriesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return LoanCategories[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return LoanCategories|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
