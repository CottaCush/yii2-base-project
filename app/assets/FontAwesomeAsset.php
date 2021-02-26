<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class FontAwesomeAsset
 * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
 * @package app\assets
 */
class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@npm/font-awesome';
    public $css = [
        'css/font-awesome.min.css'
    ];

    public array $productionCss = [
      'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'
    ];
}
