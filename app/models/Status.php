<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "statuses".
 *
 * @property integer $id
 * @property string $key
 * @property string $label
 * @property integer $is_active
 * @property string $created_at
 * @property string $updated_at
 *
 */
class Status extends BaseModel
{
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_SUSPENDED = 'suspended';
    const VALUE_IS_ACTIVE = 1;


    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'statuses';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['key', 'label'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['key'], 'unique'],
        ];
    }

    /**
     * @param $statuses
     * @return array
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     */
    public static function getStatuses($statuses): array
    {
        return self::find()->where(['IN', '`key`', $statuses])->all();
    }
}
