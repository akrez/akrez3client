<?php

namespace app\controllers;

use app\components\BlogHelper;
use Yii;
use app\components\Http;
use app\models\Customer;
use yii\helpers\Url;
use app\models\Invoice;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{

    public function behaviors()
    {
        return self::defaultBehaviors([
                    [
                        'actions' => ['signin', 'signup', 'reset-password-request', 'reset-password'],
                        'allow' => true,
                        'verbs' => ['GET', 'POST'],
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['error', 'index', 'category', 'product',],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['signout', 'basket', 'basket-remove', 'basket-add',],
                        'allow' => true,
                        'verbs' => ['GET', 'POST'],
                        'roles' => ['@'],
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

    public function actionSignout()
    {
        Http::signout();
        Yii::$app->user->logout();
        return $this->redirect(BlogHelper::blogFirstPageUrl());
    }

    public function actionSignin()
    {
        $signin = new Customer(['scenario' => 'signin']);
        if ($signin->load(Yii::$app->request->post())) {
            $this->view->params = Http::signin($signin);
            if ($this->view->params['errors']) {
                $signin->load($this->view->params, 'customer');
                $signin->addErrors($this->view->params['errors']);
            } else {
                $user = Customer::findOne($this->view->params['customer']['id']);
                if (empty($user)) {
                    $user = new Customer();
                }
                $user->load($this->view->params, 'customer');
                $user->id = $this->view->params['customer']['id'];
                if ($user->save()) {
                    Yii::$app->user->login($user);
                    return $this->goBack(BlogHelper::blogFirstPageUrl());
                }
            }
        } else {
            $this->view->params = Http::info();
        }
        Yii::$app->view->title = 'وارد شوید!';
        return $this->render('signin', [
                    'model' => $signin,
        ]);
    }

    public function actionSignup()
    {
        $signup = new Customer(['scenario' => 'signup']);
        if ($signup->load(Yii::$app->request->post())) {
            $this->view->params = Http::signup($signup);
            if ($this->view->params['errors']) {
                $signup->load($this->view->params, 'customer');
                $signup->addErrors($this->view->params['errors']);
            } else {
                $user = new Customer();
                $user->load($this->view->params, 'customer');
                $user->id = $this->view->params['customer']['id'];
                if ($user->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'alertSignupSuccessfull'));
                    return $this->redirect(Url::current(['site/signin']));
                }
            }
        } else {
            $this->view->params = Http::info();
        }
        Yii::$app->view->title = 'ثبت نام کنید!';
        return $this->render('signup', [
                    'model' => $signup,
        ]);
    }

    public function actionResetPassword()
    {
        $resetPassword = new Customer(['scenario' => 'resetPassword']);
        if ($resetPassword->load(Yii::$app->request->post())) {
            $this->view->params = Http::resetPassword($resetPassword);
            if ($this->view->params['errors']) {
                $resetPassword->load($this->view->params, 'customer');
                $resetPassword->addErrors($this->view->params['errors']);
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app', 'alertResetPasswordSuccessfull'));
                return $this->redirect(Url::current(['site/signin']));
            }
        } else {
            $this->view->params = Http::info();
        }
        Yii::$app->view->title = Yii::t('app', 'Reset Password');
        return $this->render('reset-password', [
                    'model' => $resetPassword,
        ]);
    }

    public function actionResetPasswordRequest()
    {
        $resetPasswordRequest = new Customer(['scenario' => 'resetPasswordRequest']);
        if ($resetPasswordRequest->load(Yii::$app->request->post())) {
            $this->view->params = Http::resetPasswordRequest($resetPasswordRequest);
            if ($this->view->params['errors']) {
                $resetPasswordRequest->load($this->view->params, 'customer');
                $resetPasswordRequest->addErrors($this->view->params['errors']);
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app', 'alertResetPasswordRequestSuccessfull'));
                return $this->redirect(Url::current(['site/reset-password']));
            }
        } else {
            $this->view->params = Http::info();
        }
        Yii::$app->view->title = Yii::t('app', 'Reset Password Request');
        return $this->render('reset-password-request', [
                    'model' => $resetPasswordRequest,
        ]);
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

    public function actionBasket()
    {
        $invoice = new Invoice();
        if ($invoice->load(Yii::$app->request->post())) {
            $this->view->params = Http::invoiceAdd($invoice);
            if ($this->view->params['errors']) {
                $invoice->load($this->view->params, 'invoice');
                $invoice->addErrors($this->view->params['errors']);
            } else {
                return $this->redirect(BlogHelper::url('site/invoice'));
            }
        } else {
            $this->view->params = Http::basket();
        }
        return $this->render('basket', [
                    'model' => $invoice,
        ]);
    }

    public function actionBasketRemove($id)
    {
        $this->view->params = Http::basketRemove($id);
        return $this->redirect(BlogHelper::url('basket'));
    }

    public function actionBasketAdd($id, $cnt = null)
    {
        $this->view->params = Http::basketAdd($id, $cnt);
        if (empty($this->view->params['errors'])) {
            return $this->redirect(BlogHelper::url('site/basket'));
        }
        if (isset($this->view->params['package']['product_id'])) {
            return $this->redirect(BlogHelper::url('site/product', ['id' => $this->view->params['package']['product_id']]));
        }
        throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
    }

}
