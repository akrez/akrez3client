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
    private static $baseGalleryUrl = 'http://localhost/akrez3/site/gallery/';

    private static function buildUrl($url, $params = [])
    {
        $user = Yii::$app->user->getIdentity();
        if ($user) {
            $params['_token'] = $user->token;
        }
        $params['_blog'] = Yii::$app->params['blogName'];
        return self::$baseUrl . $url . ($params ? '?' . http_build_query($params) : '');
    }

    private static function post($url, $postData = [], $params = [])
    {
        $fullUrl = self::buildUrl($url, $params);
        $data = (new Client())->createRequest()->setMethod('POST')->setUrl($fullUrl)->setData($postData)->send()->getData();
        switch ($data['code']) {
            case 200:
                Yii::$app->blog->setIdentity($data['_blog']);
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

    public static function gallery($type, $whq, $name)
    {
        $type = preg_replace('/[^A-Za-z0-9\-\.\_]/', '', $type);
        $whq = preg_replace('/[^A-Za-z0-9\-\.\_]/', '', $whq);
        $name = preg_replace('/[^A-Za-z0-9\-\.\_]/', '', $name);
        //
        $basePath = Yii::getAlias("@webroot/gallery/$type/$whq");
        $path = "$basePath/$name";
        $apiUrl = self::$baseGalleryUrl . "$type/$whq/$name";
        $url = Yii::getAlias("@web") . "/gallery/$type/$whq/$name";
        //
        if (file_exists($path)) {
            return $url;
        }
        //
        $response = (new Client())->createRequest()->setMethod('GET')->setUrl($apiUrl)->send();
        if ($response->statusCode == 200) {
            file_exists($basePath) || mkdir($basePath, '755', true);
            file_put_contents($path, $response->getContent());
        }
        return $url;
    }

    public static function constant($constantId)
    {
        $path = Yii::getAlias("@webroot") . "/cdn/constant/$constantId.json";
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true);
        }
        $fullUrl = self::buildUrl('constant');
        $data = (new Client())->createRequest()->setMethod('POST')->setUrl($fullUrl)->send()->getData();
        file_put_contents($path, json_encode($data));
        return $data;
    }

    public static function search($params)
    {
        return self::post('search', $params);
    }

    public static function category($id, $params)
    {
        return self::post('search', (array) $params, ['categoryId' => $id]);
    }

    public static function product($id, $params)
    {
        return self::post('product', [], ['id' => $id]);
    }

    public static function info()
    {
        return self::post('info');
    }

    public static function signin($user)
    {
        return self::post('signin', [
                    'email' => $user->email,
                    'password' => $user->password,
        ]);
    }

    public static function signup($user)
    {
        return self::post('signup', [
                    'email' => $user->email,
                    'password' => $user->password,
        ]);
    }

    public static function signout()
    {
        return self::post('signout');
    }

    public static function resetPasswordRequest($user)
    {
        return self::post('reset-password-request', [
                    'email' => $user->email,
        ]);
    }

    public static function resetPassword($user)
    {
        return self::post('reset-password', [
                    'email' => $user->email,
                    'password' => $user->password,
                    'reset_token' => $user->reset_token,
        ]);
    }

}
