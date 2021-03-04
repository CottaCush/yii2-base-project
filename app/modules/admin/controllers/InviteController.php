<?php

namespace app\modules\admin\controllers;

use app\constants\Messages;
use app\exceptions\InviteCreationException;
use app\models\AppUser;
use app\models\BaseModel;
use app\models\Invite;
use app\models\Role;
use app\models\Status;
use CottaCush\Yii2\Action\UpdateAction;
use Exception;
use yii\base\ExitException;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * Class InviteController
 * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
 * @package app\modules\admin\controllers
 */
class InviteController extends BaseAdminController
{
    const INDEX_URL = '/admin/invite';
    const LOGIN_URL = '/login';

    /**
     * @return array[]
     * @throws InvalidConfigException
     */
    public function actions(): array
    {
        $currentUser = $this->getUserId();

        return [
            'cancel' => [
                'class' => UpdateAction::class,
                'returnUrl' => self::INDEX_URL,
                'model' => Invite::findOne($this->getRequest()->post('id')),
                'postData' => [
                    'Invite' => [
                        'status' => Status::STATUS_CANCELLED,
                        'updated_by' => $currentUser
                    ]
                ],
                'successMessage' => Messages::getSuccessMessage(Messages::ENTITY_INVITE, Messages::TASK_CANCEL)
            ]
        ];
    }

    /**
     * @param null $status
     * @return string
     * @throws Exception
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     */
    public function actionIndex($status = null): string
    {
        $query = Invite::getInvites($status);
        $msg = ($status) ? sprintf('You have no %s invites', $status) :
            'No invites have been sent';

        $dataProvider = BaseModel::convertToProvider($query, [
            'defaultOrder' => [
                'created_at' => SORT_DESC,
            ],
            'attributes' => ['email', 'created_by', 'created_at', 'updated_at',
                'roleObj.label' => [
                    'asc' => [Role::tableName() . '.label' => SORT_ASC],
                    'desc' => [Role::tableName() . '.label' => SORT_DESC],
                ],
                'createdBy.fullName' => [
                    'asc' => [AppUser::tableName() . '.first_name' => SORT_ASC],
                    'desc' => [AppUser::tableName() . '.first_name' => SORT_DESC],
                ]
            ]
        ]);

        $roles =  ArrayHelper::map(Role::getAll(), 'key', 'label');

        return $this->render('index', [
            'invites' => $dataProvider,
            'msg' => $msg,
            'roles' => $roles,
        ]);
    }

    /**
     * @return Response
     * @throws InvalidConfigException
     * @throws ExitException
     * @throws Exception
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     */
    public function actionCreate(): Response
    {
        $this->isPostCheck(self::INDEX_URL);
        $postData = $this->getRequest()->post();

        //Todo: get createdBy from session
        $createdBy = $this->getUserId();

        $emails = ArrayHelper::getValue($postData, 'email');
        $role = ArrayHelper::getValue($postData, 'role');

        try {
            Invite::createInvites($emails, $role, $createdBy);
        } catch (InviteCreationException $ex) {
            return $this->returnError($ex->getMessage(), self::INDEX_URL);
        }
        $subject = (count($emails) > 1) ? 'Invites' : 'Invite';
        return $this->returnSuccess("$subject successfully sent");
    }
}
