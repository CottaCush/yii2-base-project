<?php

namespace app\assets;

use CottaCush\Yii2\Assets\AssetBundle;

/**
 * Class FastClickAsset
 * @package app\assets
 * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
 */
class FastClickAsset extends AssetBundle
{
    public $sourcePath = '@npm/fastclick/lib';

    public $js = [
        'fastclick.js'
    ];

    public $productionJs = [
        'https://cdnjs.cloudflare.com/ajax/libs/fastclick/1.0.6/fastclick.min.js'
    ];
}
