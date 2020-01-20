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
                        'actions' => ['error', 'index', 'category', 'product',],
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
        $this->view->params = Http::search(Yii::$app->request->get());
        return $this->render('index');
    }

    public function actionCategory($id)
    {
        $this->view->params = Http::category($id, Yii::$app->request->get());
        return $this->render('category');
    }

    public function actionProduct($id)
    {
        $this->view->params = Http::product($id, Yii::$app->request->get());
        return $this->render('product');
    }

}
