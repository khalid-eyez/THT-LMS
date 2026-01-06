<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[LoanAttachment]].
 *
 * @see LoanAttachment
 */
class LoanAttachmentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return LoanAttachment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return LoanAttachment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
