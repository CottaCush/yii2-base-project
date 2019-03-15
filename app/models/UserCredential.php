<?php

namespace app\models;

use cottacush\userauth\models\User as AuthUserCredentials;

/**
 * This is the model class for table "user_credentials".
 *
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $user_type_id
 *
 */
class UserCredential extends AuthUserCredentials
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password', 'created_at'], 'required'],
            [['user_type_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['email', 'password', 'status'], 'string', 'max' => 100],
            ['email', 'unique', 'message' => 'Sorry, The email has been used by another user'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Password',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_type_id' => 'User Type ID',
        ];
    }

    public function isActive()
    {
        return ($this->status == Status::STATUS_ACTIVE);
    }


    public function getEmail()
    {
        return $this->email;
    }

    public function getUser()
    {
        return $this->hasOne(AppUser::className(), ['user_auth_id' => 'id']);
    }
}
