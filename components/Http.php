<?php

namespace app\components;

//


use Yii;
use yii\base\Component;
use yii\httpclient\Client;
use yii\web\BadRequestHttpException;
use yii\web\ConflictHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\GoneHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\TooManyRequestsHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\UnsupportedMediaTypeHttpException;

class Http extends Component
{

    private static $baseUrl = 'http://localhost/akrez3/api/v1/';

    private static function buildUrl($url, $params = [])
    {
        $user = Yii::$app->user->getIdentity();
        if ($user) {
            $params['_token'] = $user->token;
        }
        $params['_blog'] = Yii::$app->params['blogName'];
        return self::$baseUrl . $url . ($params ? '?' . http_build_query($params) : '');
    }

    private static function post($url, $params = [], $data = [])
    {
        $fullUrl = self::buildUrl($url, $params);
        $data = (new Client())->createRequest()->setMethod('POST')->setUrl($fullUrl)->setData($data)->send()->getData();
        //
        dd( $data);
        switch ($data['code']) {
            case 200:
                return $data;
            case 400:
                throw new BadRequestHttpException;
            case 401:
                throw new UnauthorizedHttpException;
            case 403:
                throw new ForbiddenHttpException('You are not allowed to perform this action.');
            case 404:
                throw new NotFoundHttpException('Page not found.');
            case 405:
                throw new MethodNotAllowedHttpException;
            case 406:
                throw new NotAcceptableHttpException;
            case 409:
                throw new ConflictHttpException;
            case 410:
                throw new GoneHttpException;
            case 415:
                throw new UnsupportedMediaTypeHttpException;
            case 429:
                throw new TooManyRequestsHttpException;
        }
        throw new ServerErrorHttpException('An internal server error occurred.');
    }

    public static function search($params)
    {
        return self::post('search', $params);
    }

}
