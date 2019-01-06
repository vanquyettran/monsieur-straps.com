<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "banner".
 *
 * @property int $id
 * @property string $title
 * @property string $caption
 * @property string $filter
 * @property int $position
 * @property int $sort_order
 * @property string $start_time
 * @property string $end_time
 * @property int $active
 * @property int $image_id
 *
 * @property Image $image
 */
class Banner extends \common\db\MyActiveRecord
{
    const POSITION_HEADER = 0;

    /**
     * @return string[]
     */
    public function getPositionLabels()
    {
        return [
            self::POSITION_HEADER => 'Header',
        ];
    }

    /**
     * @return string
     */
    public function positionLabel()
    {
        $positionLabels = $this->getPositionLabels();
        if (isset($positionLabels[$this->position])) {
            return $positionLabels[$this->position];
        } else {
            return "$this->position";
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'banner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'position', 'sort_order', 'start_time', 'end_time', 'image_id'], 'required'],
            [['position', 'sort_order', 'active', 'image_id'], 'integer'],
            [['start_time', 'end_time'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['title', 'filter'], 'string', 'max' => 255],
            [['caption'], 'string', 'max' => 2047],
            [['image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['image_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'caption' => 'Caption',
            'filter' => 'Filter',
            'position' => 'Position',
            'sort_order' => 'Sort Order',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'active' => 'Active',
            'image_id' => 'Image ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }
}
