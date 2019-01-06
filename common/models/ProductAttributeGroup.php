<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_attribute_group".
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property int $sort_order
 *
 * @property ProductAttribute[] $productAttributes
 */
class ProductAttributeGroup extends \common\db\MyActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_attribute_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'sort_order'], 'required'],
            [['type', 'sort_order'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
            'sort_order' => 'Sort Order',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributes()
    {
        return $this->hasMany(ProductAttribute::className(), ['product_attribute_group_id' => 'id']);
    }
}
