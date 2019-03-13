<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "product_order".
 *
 * @property int $id
 * @property int $total_value
 * @property int $delivery_fee
 * @property string $customer_name
 * @property string $customer_phone
 * @property string $customer_email
 * @property string $customer_address
 * @property string $customer_address_2
 * @property string $customer_country
 * @property string $customer_province
 * @property string $customer_city
 * @property string $customer_postal_code
 * @property int $status
 * @property string $created_time
 * @property string $updated_time
 * @property int $updated_user_id
 * @property string $user_note
 *
 * @property User $updatedUser
 * @property ProductOrderToProduct[] $productOrderToProducts
 * @property Product[] $products
 */
class ProductOrder extends \common\db\MyActiveRecord
{
    const STATUS__NEW = 1;
    const STATUS__FULFILLED = 2;
    const STATUS__CANCELLED = 3;
    const STATUS__REFUSED = 4;
    const STATUS__DELIVERING = 5;
    const STATUS__PROGRESSING = 6;

    public static $allStatusLabels = [
        self::STATUS__NEW => 'New',
        self::STATUS__FULFILLED => 'Fulfilled',
        self::STATUS__CANCELLED => 'Canceled',
        self::STATUS__REFUSED => 'Refused',
        self::STATUS__DELIVERING => 'Delivering',
        self::STATUS__PROGRESSING => 'Progressing',
    ];

    public function statusLabel() {
        if (isset(self::$allStatusLabels[$this->status])) {
            return self::$allStatusLabels[$this->status];
        }

        return "Unknown ($this->status)";
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_time',
                'updatedAtAttribute' => 'updated_time',
                'value' => (new \DateTime())->format('Y-m-d H:i:s'),
            ],
        ];
    }

    public function beforeSave($insert)
    {
        // if update
        if (!$insert) {
            if ($user = Yii::$app->user->identity) {
                $this->updated_user_id = $user->getId();
            }
        }

        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['total_value', 'delivery_fee', 'customer_name', 'customer_phone', 'customer_email', 'customer_address', 'customer_country', 'customer_province', 'customer_city', 'customer_postal_code', 'status'], 'required'],
            [['total_value', 'delivery_fee', 'status', 'updated_user_id'], 'integer'],
            [['customer_name', 'customer_phone', 'customer_email', 'customer_address', 'customer_address_2', 'customer_country', 'customer_province', 'customer_city', 'customer_postal_code'], 'string', 'max' => 255],
            [['user_note'], 'string', 'max' => 2047],
            [['updated_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'total_value' => 'Total Value',
            'delivery_fee' => 'Delivery Fee',
            'customer_name' => 'Customer Name',
            'customer_phone' => 'Customer Phone',
            'customer_email' => 'Customer Email',
            'customer_address' => 'Customer Address',
            'customer_place_t1_id' => 'Customer Place T1 ID',
            'customer_place_t2_id' => 'Customer Place T2 ID',
            'customer_place_t3_id' => 'Customer Place T3 ID',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'updated_user_id' => 'Updated User ID',
            'user_note' => 'User Note',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedUser()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrderToProducts()
    {
        return $this->hasMany(ProductOrderToProduct::className(), ['product_order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('product_order_to_product', ['product_order_id' => 'id']);
    }
}
