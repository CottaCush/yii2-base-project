<?php

namespace app\assets;

use CottaCush\Yii2\Assets\AssetBundle;

/**
 * Class JquerySlimScrollAsset
 * @package app\assets
 * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
 */
class JquerySlimScrollAsset extends AssetBundle
{
    public $sourcePath = '@npm/jquery-slimscroll';

    public $js = [
        'jquery.slimscroll.min.js'
    ];

    public $productionJs = [
        'https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
