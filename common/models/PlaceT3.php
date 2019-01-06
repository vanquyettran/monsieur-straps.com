<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "place_t3".
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property int $place_t1_id
 * @property int $place_t2_id
 * @property string $latitude
 * @property string $longitude
 *
 * @property PlaceT1 $placeT1
 * @property PlaceT2 $placeT2
 */
class PlaceT3 extends \common\db\MyActiveRecord
{
    const TIER_ALIAS = 'p';

    public $tier_alias = self::TIER_ALIAS;

    const TYPE__WARD = 7;
    const TYPE__COMMUNE = 8;
    const TYPE__TOWNSHIP = 9;

    public static $allTypeLabels = [
        self::TYPE__WARD => 'Phường',
        self::TYPE__COMMUNE => 'Xã',
        self::TYPE__TOWNSHIP => 'Thị trấn',
    ];

    public static $allTypeSlugs = [
        self::TYPE__WARD => 'phuong',
        self::TYPE__COMMUNE => 'xa',
        self::TYPE__TOWNSHIP => 'thi-tran',
    ];

    public function typeLabel()
    {
        if (isset(self::$allTypeLabels[$this->type])) {
            return self::$allTypeLabels[$this->type];
        } else {
            return self::$allTypeLabels[self::TYPE__WARD];
        }
    }

    public function typeSlug()
    {
        if (isset(self::$allTypeSlugs[$this->type])) {
            return self::$allTypeSlugs[$this->type];
        } else {
            return self::$allTypeSlugs[self::TYPE__WARD];
        }
    }

    public function shortName()
    {
        if (is_numeric($this->name)) {
            return $this->typeLabel() . ' ' . $this->name;
        } else {
            return $this->name;
        }
    }

    public function fullName()
    {
        if (is_numeric($this->name)) {
            return $this->typeLabel() . ' ' . $this->name;
        } else {
            return mb_strtolower($this->typeLabel()) . ' ' . $this->name;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'place_t3';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type', 'place_t1_id', 'place_t2_id'], 'required'],
            [['type', 'place_t1_id', 'place_t2_id'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['place_t1_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlaceT1::className(), 'targetAttribute' => ['place_t1_id' => 'id']],
            [['place_t2_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlaceT2::className(), 'targetAttribute' => ['place_t2_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
            'place_t1_id' => 'Place T1 ID',
            'place_t2_id' => 'Place T2 ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaceT1()
    {
        return $this->hasOne(PlaceT1::className(), ['id' => 'place_t1_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaceT2()
    {
        return $this->hasOne(PlaceT2::className(), ['id' => 'place_t2_id']);
    }

}
