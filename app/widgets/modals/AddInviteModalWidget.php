<?php

namespace app\widgets\modals;

use app\assets\InviteWidgetAsset;
use CottaCush\Yii2\Widgets\BaseModalWidget;
use yii\helpers\Html;
use yii\bootstrap\ButtonDropdown;

/**
 * Class AddInviteModalWidget
 * @package app\widgets\modals
 * @author Taiwo Ladipo <ladipotaiwo01@gmail.com>
 * @author Bolade Oye <bolade@cottacush.com>
 */
class AddInviteModalWidget extends BaseModalWidget
{
    public $formMethod = 'post';
    public $formOptions = [];
    public string $label = 'Email Address';
    public string $inviteTagName = 'email[]';
    public string $inputPlaceholder = 'Enter email addresses...';
    public string $tagErrorMsg = 'Please add at least one email address';
    public string $inputErrorMsg = 'You entered an invalid email address';
    public string $helpText = 'Add more invite by separating with a comma(,)';
    public string $selectName = 'role';
    public string $selectPrompt = 'Choose a Role';
    public array $selectItems = [];
    public string $selectErrorMsg = 'Please choose a Role';

    public function init()
    {
        InviteWidgetAsset::register($this->view);
    }

    public function renderContents()
    {
        echo Html::beginTag('div', ['class' => 'new-invite']);

        echo Html::label($this->label, 'newInviteInput', ['class' => 'new-invite__label']);
        echo Html::beginTag('div', ['class' => 'new-invite__dropdown-wrap']);
        echo ButtonDropdown::widget([
            'label' => Html::tag('span', $this->selectPrompt, ['data-name' => '']),
            'encodeLabel' => false,
            'options' => [
                'class' => ['btn-default', 'new-invite__btn'],
                'id' => 'newInviteDropdownBtn'
            ],
            'dropdown' => [
                'options' => [
                    'class' => ['dropdown-menu-right new-invite__dropdown-menu'],
                    'id' => 'newInviteDropdownMenu',
                ],
                'items' => []
            ]
        ]);
        echo Html::tag(
            'span',
            $this->selectErrorMsg,
            ['class' => 'help-block new-invite__error hide', 'id' => 'newInviteSelectError']
        );
        echo Html::dropDownList(
            $this->selectName,
            null,
            $this->selectItems,
            ['prompt' => $this->selectPrompt, 'class' => 'new-invite__select', 'id' => 'newInviteSelect']
        );
        echo Html::endTag('div'); // .new-invite__dropdown-wrap

        echo Html::beginTag('div', ['class' => 'new-invite__box']);
        echo Html::tag(
            'div',
            Html::input(
                'text',
                '',
                '',
                ['class' => 'new-invite__input', 'placeholder' => $this->inputPlaceholder, 'id' => 'newInviteInput']
            ),
            ['class' => 'new-invite__box-inner']
        );
        echo Html::tag(
            'span',
            '',
            [
                'class' => 'help-block new-invite__error hide',
                'id' => 'newInviteInputError',
                'data-input-msg' => $this->inputErrorMsg,
                'data-tag-msg' => $this->tagErrorMsg
            ]
        );
        echo Html::tag('span', $this->helpText, ['class' => 'help-block']);

        echo Html::endTag('div');

        echo Html::endTag('div');

        echo Html::beginTag('template', ['id' => 'newInviteTagTemplate']);
        echo Html::tag(
            'span',
            Html::hiddenInput($this->inviteTagName) .
            Html::a('&times', null, ['class' => 'new-invite__tag-cancel', 'title' => 'Remove this email address']),
            ['class' => 'new-invite__tag']
        );
        echo Html::endTag('template');
    }
}
