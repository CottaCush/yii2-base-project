<?php

use app\modules\admin\widgets\SidebarMenuWidget;
use yii\helpers\Html;

?>
<?= Html::beginTag('aside', ['class' => 'main-sidebar']) ?>
<?= Html::beginTag('section', ['class' => 'sidebar']) ?>
<?= SidebarMenuWidget::widget([
    'items' => [
        [
            'name' => 'Menu 1',
            'icon' => 'user', //use font awesome icons
            'link' => ['/admin/default']
        ],
        [
            'name' => 'Invites',
            'icon' => 'paper-plane',
            'link' => ['/admin/invite/index']
        ],
        [
            'name' => 'Menu 2',
            'icon' => 'user',
            'link' => ['/admin/menu'],
            'items' => [
                ['name' => 'Submenu 1', 'link' => ['/admin/menu/index']],
                ['name' => 'Submenu 2', 'link' => ['/admin/menu/new']]
            ],
        ]
    ],
    'submenuTemplate' => "\n<ul class='treeview-menu'>\n{items}\n</ul>\n",
]); ?>

<?= Html::endTag('section') ?>
<?= Html::endTag('aside');

