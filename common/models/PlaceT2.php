<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "place_t2".
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property int $place_t1_id
 * @property string $latitude
 * @property string $longitude
 *
 * @property PlaceT1 $placeT1
 * @property PlaceT3[] $placeT3s
 */
class PlaceT2 extends \common\db\MyActiveRecord
{
    const TIER_ALIAS = 'd';

    public $tier_alias = self::TIER_ALIAS;

    const TYPE__URBAN_DISTRICT = 3;
    const TYPE__RURAL_DISTRICT = 4;
    const TYPE__TOWN = 5;
    const TYPE__PROVINCIAL_CITY = 6;

    public static $allTypeLabels = [
        self::TYPE__URBAN_DISTRICT => 'Quận',
        self::TYPE__RURAL_DISTRICT => 'Huyện',
        self::TYPE__TOWN => 'Thị xã',
        self::TYPE__PROVINCIAL_CITY => 'Thành phố',
    ];

    public static $allTypeSlugs = [
        self::TYPE__URBAN_DISTRICT => 'quan',
        self::TYPE__RURAL_DISTRICT => 'huyen',
        self::TYPE__TOWN => 'thi-xa',
        self::TYPE__PROVINCIAL_CITY => 'tp',
    ];

    public function typeLabel()
    {
        if (isset(self::$allTypeLabels[$this->type])) {
            return self::$allTypeLabels[$this->type];
        } else {
            return self::$allTypeLabels[self::TYPE__URBAN_DISTRICT];
        }
    }

    public function typeSlug()
    {
        if (isset(self::$allTypeSlugs[$this->type])) {
            return self::$allTypeSlugs[$this->type];
        } else {
            return self::$allTypeLabels[self::TYPE__URBAN_DISTRICT];
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
        return 'place_t2';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type', 'place_t1_id'], 'required'],
            [['type', 'place_t1_id'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['place_t1_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlaceT1::className(), 'targetAttribute' => ['place_t1_id' => 'id']],
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
    public function getPlaceT3s()
    {
        return $this->hasMany(PlaceT3::className(), ['place_t2_id' => 'id']);
    }
}
