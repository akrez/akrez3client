<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%Customer}}".
 *
 * @property int $id
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $status
 * @property string $token
 * @property string $password_hash
 * @property string $reset_token
 * @property string $reset_at
 * @property string $email
 * @property string $mobile
 *
 */
class Customer extends ActiveRecord implements IdentityInterface
{
    const TIMEOUT_RESET = 120;

    public $password;
    public $_customer;

    public static function tableName()
    {
        return '{{%customer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        /*
          $scenariosRules = [
          'signup' => [
          'email' => [['required'], ['unique'],],
          'password' => [['required'],],
          ],
          'signin' => [
          'email' => [['required']],
          'password' => [['required'], ['passwordValidation']],
          ],
          'resetPasswordRequest' => [
          'email' => [['required'], ['findValidCustomerByEmailValidation']],
          ],
          'resetPassword' => [
          'email' => [['required'], ['findValidCustomerByEmailResetTokenValidation']],
          'password' => [['required']],
          'reset_token' => [['required']],
          ],
          ];
          $attributesRules = [
          'email' => [
          ['email'],
          ],
          'password' => [
          ['minLenValidation', 'params' => ['min' => 6]],
          ],
          ];
          $r = \app\components\Helper::rulesDumper($scenariosRules, $attributesRules);
         */

        return [
            [0 => ['email',], 1 => 'required', 'on' => 'signup',],
            [0 => ['email', '!blog_name'], 1 => 'unique', 'on' => 'signup',],
            [0 => ['email',], 1 => 'email', 'on' => 'signup',],
            [0 => ['password',], 1 => 'required', 'on' => 'signup',],
            [0 => ['password',], 1 => 'minLenValidation', 'params' => ['min' => 6,], 'on' => 'signup',],
            [0 => ['email',], 1 => 'required', 'on' => 'signin',],
            [0 => ['email',], 1 => 'email', 'on' => 'signin',],
            [0 => ['password',], 1 => 'required', 'on' => 'signin',],
            [0 => ['password',], 1 => 'passwordValidation', 'on' => 'signin',],
            [0 => ['password',], 1 => 'minLenValidation', 'params' => ['min' => 6,], 'on' => 'signin',],
            [0 => ['email',], 1 => 'required', 'on' => 'resetPasswordRequest',],
            [0 => ['email',], 1 => 'findValidCustomerByEmailValidation', 'on' => 'resetPasswordRequest',],
            [0 => ['email',], 1 => 'email', 'on' => 'resetPasswordRequest',],
            [0 => ['email',], 1 => 'required', 'on' => 'resetPassword',],
            [0 => ['email',], 1 => 'findValidCustomerByEmailResetTokenValidation', 'on' => 'resetPassword',],
            [0 => ['email',], 1 => 'email', 'on' => 'resetPassword',],
            [0 => ['password',], 1 => 'required', 'on' => 'resetPassword',],
            [0 => ['password',], 1 => 'minLenValidation', 'params' => ['min' => 6,], 'on' => 'resetPassword',],
            [0 => ['reset_token',], 1 => 'required', 'on' => 'resetPassword',],
        ];
    }

    /////

    public static function findIdentity($id)
    {
        return static::find()->where(['id' => $id])->andWhere(['status' => [Status::STATUS_UNVERIFIED, Status::STATUS_ACTIVE, Status::STATUS_DISABLE]])->one();
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()->where(['token' => $token])->andWhere(['status' => [Status::STATUS_UNVERIFIED, Status::STATUS_ACTIVE, Status::STATUS_DISABLE]])->one();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->token;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /////

    public function passwordValidation($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $customer = Customer::findValidCustomerByEmail($this->email);
            if ($customer && $customer->validatePassword($this->password)) {
                return $this->_customer = $customer;
            }
            $this->addError($attribute, Yii::t('yii', '{attribute} is invalid.', ['attribute' => $this->getAttributeLabel($attribute)]));
        }
        return $this->_customer = null;
    }

    public function findValidCustomerByEmailValidation($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $customer = Customer::findValidCustomerByEmail($this->email);
            if ($customer) {
                return $this->_customer = $customer;
            }
            $this->addError($attribute, Yii::t('yii', '{attribute} is invalid.', ['attribute' => $this->getAttributeLabel($attribute)]));
        }
        return $this->_customer = null;
    }

    public function findValidCustomerByEmailResetTokenValidation($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $customer = Customer::findValidCustomerByEmailResetToken($this->email, $this->reset_token);
            if ($customer) {
                return $this->_customer = $customer;
            }
            $this->addError($attribute, Yii::t('yii', '{attribute} is invalid.', ['attribute' => $this->getAttributeLabel($attribute)]));
        }
        return $this->_customer = null;
    }

    public function minLenValidation($attribute, $params, $validator)
    {
        $min = $params['min'];
        if (strlen($this->$attribute) < $min) {
            $this->addError($attribute, Yii::t('yii', '{attribute} must be no less than {min}.', ['min' => $min, 'attribute' => $this->getAttributeLabel($attribute)]));
        }
    }

    public function maxLenValidation($attribute, $params, $validator)
    {
        $max = $params['max'];
        if ($max < strlen($this->$attribute)) {
            $this->addError($attribute, Yii::t('yii', '{attribute} must be no greater than {max}.', ['max' => $max, 'attribute' => $this->getAttributeLabel($attribute)]));
        }
    }

    public function setPasswordHash($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function setAuthKey()
    {
        return $this->token = Yii::$app->security->generateRandomString();
    }

    public function setResetToken()
    {
        if (empty($this->reset_token) || time() - self::TIMEOUT_RESET > $this->reset_at) {
            $this->reset_token = self::generateResetToken();
        }
        $this->reset_at = time();
    }

    public static function findValidCustomerByEmail($email)
    {
        return self::find()->where(['status' => [Status::STATUS_UNVERIFIED, Status::STATUS_ACTIVE, Status::STATUS_DISABLE]])->andWhere(['blog_name' => Yii::$app->blog->name()])->andWhere(['email' => $email])->one();
    }

    public static function findValidCustomerByEmailResetToken($email, $resetToken)
    {
        return self::find()->where(['status' => [Status::STATUS_UNVERIFIED, Status::STATUS_ACTIVE, Status::STATUS_DISABLE]])->andWhere(['email' => $email])->andWhere(['reset_token' => $resetToken])->andWhere(['>', 'reset_at', time() - self::TIMEOUT_RESET])->one();
    }

    public function generateResetToken()
    {
        do {
            $rand = rand(10000, 99999);
            $model = self::find()->where(['reset_token' => $rand])->one();
        } while ($model != null);
        return $rand;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function getCustomer()
    {
        return $this->_customer;
    }

    public function info($includeToken = false)
    {
        return [
            'id' => $this->id,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'email' => $this->email,
            'blog_name' => $this->blog_name,
            'token' => ($includeToken ? $this->token : null),
        ];
    }
}
