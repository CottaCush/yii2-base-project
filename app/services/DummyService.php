<?php
/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */

namespace app\services;

use yii\base\Object;

class DummyService extends Object implements DummyServiceInterface
{
    public function shout($text)
    {
        return $text;
    }
}
