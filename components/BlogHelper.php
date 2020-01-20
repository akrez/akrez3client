<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Url;

class BlogHelper extends Component
{

    public static function url($action, $config = [])
    {
        if (Yii::$app->params['isParked']) {
            return Url::to([$action] + $config);
        } else {
            return Url::to([$action, '_blog' => Yii::$app->params['blogName']] + $config);
        }
    }

    public static function blogFirstPageUrl()
    {
        return self::url('site/index');
    }

    public static function getImage($type, $whq, $name)
    {
        return Http::gallery($type, $whq, $name);
    }

}
