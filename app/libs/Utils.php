<?php

namespace app\libs;

use yii\helpers\Html;

/**
 * Class Utils
 * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
 * @package app\libs
 */
class Utils
{
    public static function getStatusHtml($status, $extraClasses = '')
    {
        $status = strtolower($status);
        $statusHypenated = implode('-', explode(' ', $status));
        $class = trim("status status--$statusHypenated $extraClasses");
        return Html::tag('span', $status, ['class' => $class]);
    }
}
