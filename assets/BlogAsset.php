<?php
namespace app\assets;

use yii\web\AssetBundle;

class BlogAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'themes/blog/css/blog.css',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'blog\assets\cdn\BootstrapAsset',
        'blog\assets\cdn\FontSahelAsset',
        //'yii\bootstrap\BootstrapThemeAsset',
    ];
}
