<?php

namespace app\models;

use Yii;
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
    public static function tableName()
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
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->user_auth_id;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->user_auth_id === $authKey;
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['key' => 'role_key']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusObj()
    {
        return $this->hasOne(Status::className(), ['key' => 'status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAuth()
    {
        return $this->hasOne(UserCredential::className(), ['id' => 'user_auth_id']);
    }


    /**
     * Confirm that user account is active
     * @return bool
     */
    public function isActive()
    {
        return $this->status == Status::VALUE_IS_ACTIVE;
    }

    /**
     * Confirm that userauth credentials is active
     * @return bool
     */
    public function isCredentialActive()
    {
        return $this->userAuth->isActive();
    }


    public function getAvatar()
    {
        return '';
    }
}
