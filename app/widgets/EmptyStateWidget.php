<?php

namespace app\widgets;

use app\assets\FontAwesomeAsset;
use CottaCush\Yii2\Helpers\Html;
use Exception;
use yii\helpers\ArrayHelper;

/**
 * Class EmptyStateWidget
 * @author Olajide Oye <jide@cottacush.com>
 * @package app\widgets
 */
class EmptyStateWidget extends BaseContentHeaderButton
{
    public string $icon = '';
    public string $title = '';
    public string $description = '';
    public string $buttonClass = 'btn btn-primary';
    public bool $showContentHeader = true;
    public bool $showContentHeaderButton = true;
    public bool $showButton = true;

    /**
     * @throws Exception
     */
    public function init()
    {
        parent::init();
        $view = $this->view;

        $view->params['show-content-header'] = $this->showContentHeader;
        $view->params['show-content-header-button'] = $this->showContentHeaderButton;

        if (is_null($this->button)) {
            $this->button = ArrayHelper::getValue($view->params, 'content-header-button', true);
        }

        if ($this->showButton) {
            $this->setButton();
        }

        FontAwesomeAsset::register($this->view);
    }

    public function run(): void
    {
        echo Html::beginTag('section', ['class' => 'empty-state']);

        echo Html::faIcon($this->icon, ['class' => 'empty-state__icon']);
        echo Html::tag('h2', $this->title, ['class' => 'empty-state__title']);
        echo Html::tag('p', $this->description, ['class' => 'empty-state__description']);
        if ($this->showButton) {
            echo Html::tag('div', $this->button, ['class' => 'empty-state__btn']);
        }
        echo Html::endTag('section');
    }
}
