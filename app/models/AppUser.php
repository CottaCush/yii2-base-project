<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property integer $user_auth_id
 * @property string $created_at
 * @property string $role_key
 * @property string $status
 * @property integer $is_active
 * @property string $updated_at
 * @property integer $store_id
 * @property integer $updated_by
 *
 * @property Role $role
 * @property Status $statusObj
 * @property UserCredential $userAuth
 */
class AppUser extends BaseModel implements IdentityInterface
{
    const SESSION_KEY = 'user';

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        if (!Yii::$app->session->has(self::SESSION_KEY)) {
            return null;
        }
        $user = unserialize(Yii::$app->session->get(self::SESSION_KEY));
        return $user ?: null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        return null;
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId(): int|string
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey(): int|string
    {
        return $this->user_auth_id;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->user_auth_id === $authKey;
    }

    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return ActiveQuery
     */
    public function getRole(): ActiveQuery
    {
        return $this->hasOne(Role::class, ['key' => 'role_key']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStatusObj(): ActiveQuery
    {
        return $this->hasOne(Status::class, ['key' => 'status']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserAuth(): ActiveQuery
    {
        return $this->hasOne(UserCredential::class, ['id' => 'user_auth_id']);
    }


    /**
     * Confirm that user account is active
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status == Status::VALUE_IS_ACTIVE;
    }

    /**
     * Confirm that userauth credentials is active
     * @return bool
     */
    public function isCredentialActive(): bool
    {
        return $this->userAuth->isActive();
    }


    public function getAvatar(): string
    {
        return '';
    }
}
