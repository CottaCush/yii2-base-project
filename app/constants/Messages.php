<?php

namespace app\constants;

/**
 * Class Messages
 * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
 * @package app\constants
 */
class Messages
{
    const INVITE_ALREADY_CANCELLED = 'This invitation has expired, please contact Admin';
    const INVITE_NOT_FOUND = 'Invite not found';
    const INVITE_ALREADY_ACCEPTED = 'You have already accepted this invite';
    const INVITE_ALREADY_SENT = 'You have already sent an invite to one or more email provided';
    const INVITE_EXPIRED = 'Sorry, this invite has expired';
    const DUPLICATE_EMAILS_IN_INVITES = 'Please remove duplicated email addresses in the invite list';
    const INVALID_EMAIL = 'One or more invalid emails supplied';

    const ENTITY_INVITE = 'Invite';

    const TASK_CANCEL = 'cancelled';
    const TASK_SEND = 'sent';
    const TASK_CREATE = 'created';

    const ACTION_CANCEL = 'cancel';

    const NO = 'No';
    const YES = 'Yes';

    /**
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     * @param $entity
     * @param $action
     * @return string
     */
    public static function getWarningMessage($entity, $action)
    {
        return sprintf('Are you sure you want to %s this %s?', $action, $entity);
    }

    /**
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     * @param $entity
     * @param $task
     * @return string
     */
    public static function getSuccessMessage($entity, $task)
    {
        return sprintf('%s %s successfully', $entity, $task);
    }

    /**
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     * @param $entity
     * @return string
     */
    public static function getNotFoundMessage($entity = 'Record')
    {
        return sprintf('%s not found', $entity);
    }
}
