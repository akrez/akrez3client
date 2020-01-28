<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Url;

class BlogHelper extends Component
{

    public static $constant = false;

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

    public static function getConstant($level1 = null, $level2 = null, $level3 = null)
    {
        if (self::$constant == false) {
            self::$constant = Http::constant(Yii::$app->blog->attribute('constant_id'));
        }
        $value = self::$constant;
        if (!empty(strlen($level1))) {
            $value = $value[$level1];
        }
        if (!empty(strlen($level2))) {
            $value = $value[$level2];
        }
        if (!empty(strlen($level3))) {
            $value = $value[$level3];
        }
        return $value;
    }

}
