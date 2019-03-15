<?php

use app\constants\Messages;
use app\libs\Utils;
use app\widgets\EmptyStateWidget;
use CottaCush\Yii2\Widgets\ActionButtons;
use CottaCush\Yii2\Widgets\GridViewWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\widgets\modals\AddInviteModalWidget;
use app\models\Status;
use CottaCush\Yii2\Widgets\Modals\ConfirmModalWidget;

$this->title = 'Invites';

$this->params['content-header-button'] = [
    'tag' => 'button',
    'label' => 'New Invite',
    'options' => ['data-toggle' => 'modal', 'data-target' => '#addInviteModal']
];

$this->params['breadcrumbs'] = [
    ['label' => 'Admin'],
    ['label' => 'Invites']
];

if ($invites->getCount() == 0) :
    echo EmptyStateWidget::widget([
        'icon' => 'paper-plane',
        'title' => $msg,
        'description' => 'Please click on the button below to create invites',
        'showButton' => true
    ]);

else:
    echo GridViewWidget::widget([
        'dataProvider' => $invites,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'S/N'
            ],
            'email:text:email',
            'role.label:text:role',
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'status',
                'label' => 'STATUS',
                'format' => 'raw',
                'value' => function ($model) {
                    return Utils::getStatusHtml(ArrayHelper::getValue($model, 'status'));
                }
            ],
            'created_at:datetime:invited at',
            'sender.user.fullName:text:invited by',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'ACTION',
                'template' => '{actions}',
                'buttons' => [
                    'actions' => function ($url, $invite, $key) {
                        $actions = [
                            [
                                'label' => ucfirst(Messages::ACTION_CANCEL),
                                'visible' => ArrayHelper::getValue($invite, 'status') == Status::STATUS_PENDING,
                                'options' => [
                                    'data' => [
                                        'toggle' => 'modal',
                                        'target' => '#cancelInvite',
                                        'url' => Url::toRoute(['cancel']),
                                        'id' => ArrayHelper::getValue($invite, 'id'),
                                        'title' => "Cancel Invite",
                                        'msg' => Messages::getWarningMessage(
                                            Messages::ENTITY_INVITE,
                                            Messages::ACTION_CANCEL
                                        )
                                    ]
                                ],
                            ],
                        ];
                        return ActionButtons::widget(['actions' => $actions]);
                    }
                ]
            ]
        ]
    ]);

endif;

echo AddInviteModalWidget::widget([
    'title' => 'New Invite',
    'id' => 'addInviteModal',
    'route' => Url::toRoute('create'),
    'selectItems' => $roles,
    'modalCancelFooterClass' => 'btn btn-default',
    'modalSubmitFooterClass' => 'btn btn-primary',
]);

echo ConfirmModalWidget::widget([
    'modalId' => 'cancelInvite',
    'footerCancel' => Messages::NO,
    'footerSubmit' => Messages::YES
]);
