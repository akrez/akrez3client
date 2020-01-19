<?php

namespace app\controllers;

use Yii;
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
        return $this->render('index', [
                    'result' => Http::search(Yii::$app->request->get()),
        ]);
    }

}
