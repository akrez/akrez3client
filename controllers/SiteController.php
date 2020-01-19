<?php

namespace app\controllers;

use app\components\Http;

class SiteController extends Controller
{

    public function behaviors()
    {
        return self::defaultBehaviors([
                    [
                        'actions' => ['error', 'index',],
                        'allow' => true,
                    ],
        ]);
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => 'blank'
            ],
        ];
    }

    public function actionIndex()
    {
        $result = Http::search([]);
        ed($result);
        return $this->render('index');
    }

}
