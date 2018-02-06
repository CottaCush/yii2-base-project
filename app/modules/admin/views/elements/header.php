<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>

<?=
Html::beginTag('header', ['class' => 'main-header']) .
Html::a(
    Html::img(Url::toRoute('/admin-assets/img/mini-logo.png'), ['class' => 'logo-mini']) .
    Html::img(Url::toRoute('/admin-assets/img/main-logo.png'), ['class' => 'logo-lg']),
    Url::toRoute('/admin/'),
    ['class' => 'logo']
) .
Html::beginTag('nav', ['class' => 'navbar navbar-static-top', 'role' => 'navigation']) .
Html::tag(
    'a',
    Html::tag('span', 'Toggle navigation', ['class' => 'sr-only']),
    ['class' => 'sidebar-toggle', 'data-toggle' => 'offcanvas', 'role' => 'button']
) .
Html::beginTag('div', ['class' => 'navbar-custom-menu pull-left']) .
Html::endTag('div') .
Html::endTag('nav') .
Html::endTag('header');
