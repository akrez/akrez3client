<?php

namespace app\controllers;

use Yii;
use yii\web\Controller as BaseController;
use yii\web\ForbiddenHttpException;
use yii\helpers\Url;

class Controller extends BaseController
{

    public static function defaultBehaviors($rules = [])
    {
        return [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => $rules,
                'denyCallback' => function ($rule, $action) {
                    if (Yii::$app->user->isGuest) {
                        Yii::$app->user->setReturnUrl(Url::current());
                        return Yii::$app->controller->redirect(['/site/signin']);
                    }
                    throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
                }
            ],
        ];
    }

}
