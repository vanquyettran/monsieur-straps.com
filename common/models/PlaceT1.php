<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "place_t1".
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property string $latitude
 * @property string $longitude
 *
 * @property PlaceT2[] $placeT2s
 * @property PlaceT3[] $placeT3s
 */
class PlaceT1 extends \common\db\MyActiveRecord
{
    const TIER_ALIAS = 'm';

    public $tier_alias = self::TIER_ALIAS;

    const TYPE__MUNICIPALITY = 1;
    const TYPE__PROVINCE = 2;

    public static $allTypeLabels = [
        self::TYPE__MUNICIPALITY => 'Thành phố',
        self::TYPE__PROVINCE => 'Tỉnh',
    ];

    public static $allTypeSlugs = [
        self::TYPE__MUNICIPALITY => 'thanh-pho',
        self::TYPE__PROVINCE => 'tinh',
    ];

    public function typeLabel()
    {
        if (isset(self::$allTypeLabels[$this->type])) {
            return self::$allTypeLabels[$this->type];
        } else {
            return self::$allTypeLabels[self::TYPE__PROVINCE];
        }
    }

    public function typeSlug()
    {
        if (isset(self::$allTypeSlugs[$this->type])) {
            return self::$allTypeSlugs[$this->type];
        } else {
            return self::$allTypeSlugs[self::TYPE__PROVINCE];
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
        return 'place_t1';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['name'], 'unique'],
            [['type'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['name'], 'string', 'max' => 255],
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
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaceT2s()
    {
        return $this->hasMany(PlaceT2::className(), ['place_t1_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaceT3s()
    {
        return $this->hasMany(PlaceT3::className(), ['place_t1_id' => 'id']);
    }
}
