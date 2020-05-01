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

    public static function getMetaKeyword()
    {
        /////
        $blogName = Yii::$app->blog->attribute('name');
        $blogTitle = Yii::$app->blog->attribute('title');
        $blogSlug = Yii::$app->blog->attribute('slug');
        //
        $categories = isset(Yii::$app->view->params['_categories']) ? Yii::$app->view->params['_categories'] : [];
        $categoryId = isset(Yii::$app->view->params['categoryId']) ? Yii::$app->view->params['categoryId'] : null;
        $category = isset($categories[$categoryId]) ? $categories[$categoryId] : null;
        //
        $productTitle = isset(Yii::$app->view->params['product']['title']) ? Yii::$app->view->params['product']['title'] : null;
        /////
        $words = [
            'نمایندگی فروش',
            'فروش',
            'خرید اینترنتی محصولات',
            'فروشگاه',
            'فروشگاه اینترنتی',
            'فروشگاه آنلاین',
            'خرید آنلاین'
        ];
        /////
        $keywords = [$blogTitle, $blogName, $blogTitle . '-' . $blogName, $blogTitle . '-' . $blogSlug];
        //
        if ($productTitle) {
            $keywords = array_merge($keywords, [
                $productTitle . ' ' . $blogTitle,
                $productTitle,
            ]);
        }
        //
        if ($category) {
            $keywords = array_merge($keywords, [$category, $category . ' ' . $blogTitle,], array_map(function($value) use($category) {
                        return $value . ' ' . $category;
                    }, $words));
        }
        //
        if ($categories) {
            $keywords = array_merge($keywords, $categories);
        }
        //
        if ($blogTitle) {
            $keywords = array_merge($keywords, array_map(function($value) use($blogTitle) {
                        return $value . ' ' . $blogTitle;
                    }, $words));
        }
        //
        return implode(',', $keywords);
    }

}
