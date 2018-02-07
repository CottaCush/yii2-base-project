<?php

namespace app\modules\admin\widgets;

use CottaCush\Yii2\Helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/**
 * Class ContentHeaderWidget
 * @author Olajide Oye <jide@cottacush.com>
 * @package app\widgets
 */
class ContentHeaderWidget extends BaseContentHeaderButton
{
    public $title;
    public $breadcrumbs;
    public $buttonClass = 'btn btn-sm content-header-btn';
    public $contentRight;

    public function init()
    {
        parent::init();

        $view = $this->view;

        if (is_null($this->title)) {
            $this->title = isset($view->params['pageTitle']) ? $view->params['pageTitle'] : $view->title;
        }
        if (is_null($this->breadcrumbs)) {
            $this->breadcrumbs = isset($view->params['breadcrumbs']) ? $view->params['breadcrumbs'] : [$this->title];
        }
        if (is_null($this->contentRight)) {
            $this->contentRight = isset($view->params['contentHeaderRight']) ? $view->params['contentHeaderRight'] : '';
        }

        $this->setButton();
    }

    public function run()
    {
        echo Html::beginTag('section', ['class' => 'content-header']);
        echo Html::beginTag('div', ['class' => ' clearfix']);
        echo Html::beginTag('div', ['class' => ' content-header__left']);
        echo Html::tag('h1', $this->title, ['class' => 'content-header-title']);

        if (ArrayHelper::getValue($this->view->params, 'show-content-header-button', true)) {
            echo $this->button;
        }

        echo Html::endTag('div');
        echo Html::tag('div', $this->contentRight, ['class' => 'content-header__right']);
        echo Html::endTag('div');
        echo Breadcrumbs::widget([
            'tag' => 'ol',
            'homeLink' => [
                'label' => 'Home',
                'url' => Url::toRoute('/admin/')
            ],
            'links' => $this->breadcrumbs
        ]);
        echo Html::endTag('section');
    }
}
