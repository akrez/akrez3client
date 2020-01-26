<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property string $status
 * @property string $name
 * @property string $province
 * @property string $address
 * @property string $mobile
 * @property string $phone
 * @property string $des
 * @property string $receive_from
 * @property string $receive_until
 * @property int $price
 * @property string $blog_name
 * @property int $customer_id
 *
 * @property Basket[] $baskets
 * @property Customer $customer
 */
class Invoice extends Model
{

    public $id;
    public $created_at;
    public $updated_at;
    public $status;
    public $name;
    public $province;
    public $address;
    public $mobile;
    public $phone;
    public $des;
    public $receive_from;
    public $receive_until;
    public $price;
    public $blog_name;
    public $customer_id;

    const STATUS_UNVERIFIED = 0;
    const STATUS_VERIFIED = 1;
    const STATUS_CUSTOMER_DELETED_UNVERIFIED = 2;
    const STATUS_CUSTOMER_DELETED_VERIFIED = 3;
    const STATUS_ADMIN_DELETED_UNVERIFIED = 4;
    const STATUS_ADMIN_DELETED_VERIFIED = 5;

    public static function statuses()
    {
        return [
            self::STATUS_UNVERIFIED => Yii::t('app', 'Unverified'),
            self::STATUS_VERIFIED => Yii::t('app', 'Verified'),
            self::STATUS_CUSTOMER_DELETED_UNVERIFIED => Yii::t('app', 'Customer Deleted Unverified'),
            self::STATUS_CUSTOMER_DELETED_VERIFIED => Yii::t('app', 'Customer Deleted Verified'),
            self::STATUS_ADMIN_DELETED_UNVERIFIED => Yii::t('app', 'Admin Deleted Unverified'),
            self::STATUS_ADMIN_DELETED_VERIFIED => Yii::t('app', 'Admin Deleted Verified'),
        ];
    }

    public static function statuseLabel($item)
    {
        $list = self::statuses();
        return (isset($list[$item]) ? $list[$item] : null);
    }

    public function rules()
    {
        return [
            [['!status', '!blog_name', '!customer_id', '!price', 'name', 'province', 'address', 'mobile', 'phone'], 'required'],
            [['name'], 'string', 'max' => 31],
            [['address'], 'string', 'max' => 1024],
            [['mobile'], 'match', 'pattern' => '/^09[0-9]{9}$/'],
            [['phone'], 'match', 'pattern' => '/^[1-9]{1}[0-9]{5,8}$/'],
            [['des'], 'string', 'max' => 1024],
            //
            [['receive_from', 'receive_until'], 'string', 'max' => 24],
        ];
    }

}
