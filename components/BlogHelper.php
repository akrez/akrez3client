<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Url;

class BlogHelper extends Component
{

    public static $constant = false;

    public static function url($action, $config = [], $scheme = false)
    {
        if (Yii::$app->params['isParked']) {
            return Url::to([$action] + $config, $scheme);
        } else {
            return Url::to([$action] + $config, $scheme);
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

    public static function getMetaKeyword($categories = [], $categoryId = null)
    {
        $blogName = Yii::$app->blog->attribute('name');
        $blogTitle = Yii::$app->blog->attribute('title');
        $blogSlug = Yii::$app->blog->attribute('slug');
        //
        if (empty($categoryId)) {
            return Helper::normalizeArrayUnorder([$blogTitle, $blogSlug, $blogName], false, ',') . implode(',', $categories);
        }
        //
        $category = $categories[$categoryId];
        $keywords = array_merge([$category, $blogName, $blogTitle . '-' . $blogName,], $categories);
        $keywords = array_merge($keywords, [
            $category,
            $category . ' ' . $blogTitle,
            //
            'نمایندگی فروش ' . $category,
            'فروش ' . $category,
            'خرید اینترنتی محصولات ' . $category,
            'فروشگاه ' . $category,
            'فروشگاه اینترنتی ' . $category,
            'فروشگاه آنلاین ' . $category,
            //
            'نمایندگی فروش ' . $blogTitle,
            'فروش ' . $blogTitle,
            'خرید اینترنتی محصولات ' . $blogTitle,
            'فروشگاه ' . $blogTitle,
            'فروشگاه اینترنتی ' . $blogTitle,
            'فروشگاه آنلاین ' . $blogTitle,
            //
            'خرید آنلاین',
        ]);
        return implode(',', $keywords);
    }

}
