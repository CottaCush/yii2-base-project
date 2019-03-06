<?php
/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */

namespace app\services;

use yii\base\BaseObject as DummyObject;

class DummyService extends DummyObject implements DummyServiceInterface
{
    public function shout($text)
    {
        return $text;
    }
}
