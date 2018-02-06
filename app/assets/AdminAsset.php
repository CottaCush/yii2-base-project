<?php

namespace app\assets;

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
        'app\assets\FastClickAsset',
        'app\assets\JquerySlimScrollAsset',
        'CottaCush\Yii2\Assets\FontAwesomeAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
