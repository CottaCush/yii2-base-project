<?php

namespace app\assets;

use yii\bootstrap4\BootstrapPluginAsset;

/**
 * Class AdminAsset
 * @package app\assets
 * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
 */
class AdminAsset extends BaseAdminAsset
{
    public $css = [
        'css/styles.css',
    ];
    public $js = [
        'js/theme-options.js',
        'js/theme.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'CottaCush\Yii2\Assets\AppModuleAsset',
        'yii\bootstrap4\BootstrapAsset',
        'app\assets\FastClickAsset',
        'app\assets\JquerySlimScrollAsset',
        'app\assets\FontAwesomeAsset',
        BootstrapPluginAsset::class,
    ];
}
