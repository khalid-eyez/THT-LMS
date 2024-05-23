<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_audit_entry".
 *
 * @property int $audit_entry_id
 * @property string $audit_entry_timestamp
 * @property string $audit_entry_model_name
 * @property string $audit_entry_operation
 * @property string $audit_entry_field_name
 * @property string|null $audit_entry_old_value
 * @property string $audit_entry_new_value
 * @property string $audit_entry_user_id
 * @property string $audit_entry_ip
 * @property string $audit_entry_affected_record_reference
 * @property string $audit_entry_affected_record_reference_type
 */
class TblAuditEntry extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_audit_entry';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['audit_entry_timestamp', 'audit_entry_model_name', 'audit_entry_operation', 'audit_entry_field_name', 'audit_entry_new_value', 'audit_entry_user_id', 'audit_entry_ip', 'audit_entry_affected_record_reference', 'audit_entry_affected_record_reference_type'], 'required'],
            ['audit_entry_timestamp','default','value'=>time()],
            [['audit_entry_old_value', 'audit_entry_new_value'], 'string'],
            [['audit_entry_timestamp', 'audit_entry_model_name', 'audit_entry_operation', 'audit_entry_field_name', 'audit_entry_user_id', 'audit_entry_ip'], 'string', 'max' => 100],
            [['audit_entry_affected_record_reference', 'audit_entry_affected_record_reference_type'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'audit_entry_id' => Yii::t('app', 'ID'),
            'audit_entry_timestamp' => Yii::t('app','Date & Time'),
            'audit_entry_model_name' => Yii::t('app', 'Affected DB Table'),
            'audit_entry_operation' => Yii::t('app', 'Action'),
            'audit_entry_field_name' => Yii::t('app', 'Affected Field Name'),
            'audit_entry_old_value' => Yii::t('app', 'Old Value'),
            'audit_entry_new_value' => Yii::t('app', 'New Value'),
            'audit_entry_user_id' => Yii::t('app', 'User'),
            'audit_entry_ip' => Yii::t('app', 'IP Address'),
            'audit_entry_affected_record_reference' => Yii::t('app', 'Affected Record Reference'),
            'audit_entry_affected_record_reference_type' => Yii::t('app', 'Affected Record Reference type')
        ];
    }
    public function afterFind()  {

    $this->audit_entry_user_id=User::findOne($this->audit_entry_user_id)->username;
  
        return parent::afterFind();
    }
}
