<?php

namespace app\models;

/**
 * This is the model class for table "roles".
 *
 * @property integer $id
 * @property string $key
 * @property string $label
 * @property integer $is_active
 * @property string $created_at
 * @property string $updated_at
 *
 */
class Role extends BaseModel
{

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'roles';
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
}
