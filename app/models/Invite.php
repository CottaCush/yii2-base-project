<?php

namespace app\models;

use app\constants\Messages;
use app\exceptions\InviteCreationException;
use app\exceptions\InviteTokenValidationException;
use CottaCush\Yii2\Date\DateFormat;
use CottaCush\Yii2\Date\DateUtils;
use yii\validators\EmailValidator;

/**
 * Class Invite
 *
 * @property integer $id
 * @property string $email
 * @property string $role_key
 * @property string $status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_by
 * @property string invite_token
 * @property integer is_active
 *
 * @property Role $role
 */
class Invite extends BaseModel
{
    public static function tableName()
    {
        return 'invites';
    }

    public function rules()
    {
        return [
            [['email', 'role_key', 'status', 'created_by'], 'required'],
            [['email'], 'email'],
            [['created_at', 'updated_at', 'updated_by'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email Address',
            'role_key' => 'Role',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getSender()
    {
        return $this->hasOne(UserCredential::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleObj()
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
    public function getCreatedBy()
    {
        return $this->hasOne(AppUser::className(), ['user_auth_id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(AppUser::className(), ['username' => 'updated_by']);
    }

    /**
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     * @return bool
     */
    public function isCancelled()
    {
        return $this->status == Status::STATUS_CANCELLED;
    }

    /**
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     * @return bool
     */
    public function isPending()
    {
        return $this->status == Status::STATUS_PENDING;
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $email
     * @return string
     */
    public static function getInviteToken($email)
    {
        return md5($email . date(DateFormat::FORMAT_MYSQL_STYLE));
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['key' => 'role_key']);
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $token
     * @return static
     */
    public static function validateToken($token)
    {
        $invite = Invite::findOne(['invite_token' => $token]);
        if (!$invite) {
            throw new InviteTokenValidationException(Messages::INVITE_NOT_FOUND);
        }

        if ($invite->status == Status::STATUS_CANCELLED) {
            throw new InviteTokenValidationException(Messages::INVITE_ALREADY_CANCELLED);
        }

        if ($invite->status == Status::STATUS_ACCEPTED) {
            throw new InviteTokenValidationException(Messages::INVITE_ALREADY_ACCEPTED);
        }

        return $invite;
    }

    /**
     * @author Taiwo Ladipo <ladipotaiwo01@gmail.com>
     * @param $emails
     * @return bool
     */
    public static function validateEmails($emails)
    {
        $validator = new EmailValidator();
        foreach ($emails as $email) {
            if (!$validator->validate($email)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     * @param array $emails
     * @return bool
     */
    public static function checkDuplicateInviteEmails(array $emails)
    {
        return self::find()
            ->where(['IN', 'email', $emails])
            ->andwhere('status != "' . Status::STATUS_CANCELLED . '"')->exists();
    }

    /**
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     * @param $emails
     * @param $role
     * @param $createdBy
     * @return bool
     * @throws InviteCreationException
     */
    public static function createInvites($emails, $role, $createdBy)
    {
        if (!array_unique($emails)) {
            throw new InviteCreationException(Messages::DUPLICATE_EMAILS_IN_INVITES);
        }

        if (!Invite::validateEmails($emails)) {
            throw new InviteCreationException(Messages::INVALID_EMAIL);
        }

        if (Invite::checkDuplicateInviteEmails($emails)) {
            throw new InviteCreationException(Messages::INVITE_ALREADY_SENT);
        }

        foreach ($emails as $email) {
            $invite = new Invite();
            $invite->email = $email;
            $invite->role_key = $role;
            $invite->created_at = DateUtils::getMysqlNow();
            $invite->created_by = $createdBy;
            $invite->invite_token = Invite::generateInviteToken($email);
            $invite->status = Status::STATUS_PENDING;
            if (!$invite->save()) {
                throw new InviteCreationException($invite->getFirstError());
            }
            //TODO: Add logic for sending email
        }
        return true;
    }

    /**
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     * @param $email
     * @return string
     */
    public static function generateInviteToken($email)
    {
        return md5($email . date(DateFormat::FORMAT_MYSQL_STYLE));
    }

    /**
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     * @param null $status
     * @param null $role
     * @param null $createdBy
     * @return \yii\db\ActiveQuery
     */
    public static function getInvites($status = null, $role = null, $createdBy = null)
    {
        return self::find()
            ->filterWhere([self::tableName() . '.status' => $status])
            ->andFilterWhere([self::tableName() . '.role_key' => $role])
            ->andFilterWhere([self::tableName() . '.created_by' => $createdBy])
            ->joinWith(['roleObj', 'createdBy']);
    }
}