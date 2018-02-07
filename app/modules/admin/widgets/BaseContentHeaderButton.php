<?php

namespace app\modules\admin\widgets;

use CottaCush\Yii2\Helpers\Html;
use CottaCush\Yii2\Widgets\BaseWidget;
use yii\helpers\ArrayHelper;

/**
 * Class BaseContentHeaderButton
 * @author Olajide Oye <jide@cottacush.com>
 * @package app\widgets
 */
class BaseContentHeaderButton extends BaseWidget
{
    public $button;
    public $buttonClass;

    protected function setButton()
    {
        if (is_string($this->button)) {
            return;
        }
        if (is_array($this->button)) {
            $this->buildButton($this->button);
            return;
        }

        $buttonDetails = ArrayHelper::getValue($this->view->params, 'content-header-button', '');
        if (is_string($buttonDetails)) {
            $this->button = $buttonDetails;
            return;
        }
        $this->buildButton($buttonDetails);
    }

    private function buildButton($buttonArray)
    {
        if (!is_array($buttonArray)) {
            return;
        }

        $text = ArrayHelper::getValue($buttonArray, 'label', 'Add New');
        $tag = ArrayHelper::getValue($buttonArray, 'tag', 'a');
        $iconName = ArrayHelper::getValue($buttonArray, 'icon', 'plus');
        $icon = '';
        if ($iconName) {
            $icon = Html::faIcon($iconName);
        }
        $options = ArrayHelper::getValue($buttonArray, 'options', []);

        Html::addCssClass($options, $this->buttonClass);

        $this->button = Html::tag($tag, "$icon $text", $options);

    }
}
