<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "site_param".
 *
 * @property int $id
 * @property string $name
 * @property string $value
 * @property int $sort_order
 */
class SiteParam extends \common\db\MyActiveRecord
{
    const LOGO_COLOURED = 'logo_coloured';
    const LOGO_WHITE = 'logo_white';
    const COMPANY = 'company';
    const ADDRESS = 'address';
    const WORKSHOP = 'workshop';
    const PHONE = 'phone';
    const EMAIL = 'email';
    const FACEBOOK_PAGE = 'facebook_page';
    const INSTAGRAM_PAGE = 'instagram_page';
    const PINTEREST_PAGE = 'pinterest_page';
    const TUMBLR_PAGE = 'tumblr_page';
    const TWITTER_PAGE = 'twitter_page';
    const YOUTUBE_CHANNEL = 'facebook_channel';
    const ABOUT_US = 'about_us';
    const TRACKING_CODE = 'tracking_code';
    const FOOTER_C1_TITLE = 'footer_c1_title';
    const FOOTER_C2_TITLE = 'footer_c2_title';
    const FOOTER_C1_LINK = 'footer_c1_link';
    const FOOTER_C2_LINK = 'footer_c2_link';
    const FOOTER_CONTACT_TITLE = 'footer_contact_title';
    const PAYMENT_LINK = 'payment_link';
    const GOOGLE_CUSTOM_SEARCH_ID = 'google_custom_search_id';
    const FAVICON_URL = 'favicon_url';

    /**
     * @return string[]
     */
    public function getParamLabels()
    {
        return [
            self::LOGO_COLOURED => 'Coloured logo',
            self::LOGO_WHITE => 'White logo',
            self::COMPANY => 'Company',
            self::ADDRESS => 'Address',
            self::WORKSHOP => 'Workshop',
            self::PHONE => 'Phone',
            self::EMAIL => 'Email',
            self::FACEBOOK_PAGE => 'Facebook page',
            self::INSTAGRAM_PAGE => 'Instagram page',
            self::PINTEREST_PAGE => 'Pinterest page',
            self::TUMBLR_PAGE => 'Tumblr page',
            self::TWITTER_PAGE => 'Twitter page',
            self::YOUTUBE_CHANNEL => 'Youtube chanel',
            self::ABOUT_US => 'About us',
            self::TRACKING_CODE => 'Tracking Code',
            self::FOOTER_C1_TITLE => 'Footer column 1 title',
            self::FOOTER_C2_TITLE => 'Footer column 2 title',
            self::FOOTER_C1_LINK => 'Footer column 1 link: NAME | LINK',
            self::FOOTER_C2_LINK => 'Footer column 2 link: NAME | LINK',
            self::FOOTER_CONTACT_TITLE => 'Footer contact title',
            self::PAYMENT_LINK => 'Payment Link',
            self::GOOGLE_CUSTOM_SEARCH_ID => 'Google Custom Search ID',
            self::FAVICON_URL => 'Favicon URL',
        ];
    }

    /**
     * @return string
     */
    public function paramLabel()
    {
        $paramLabels = $this->getParamLabels();
        if (isset($paramLabels[$this->name])) {
            return $paramLabels[$this->name];
        } else {
            return $this->name;
        }
    }

    private static $_indexData;

    /**
     * @return self[]
     */
    public static function indexData()
    {
        if (self::$_indexData == null) {
            self::$_indexData = self::find()->orderBy('sort_order asc')->indexBy('id')->all();
        }

        return self::$_indexData;
    }

    /**
     * @param $name
     * @return self|null
     */
    public static function findOneByName($name)
    {
        $data = self::indexData();
        foreach ($data as $item) {
            if ($item->name == $name) {
                return $item;
            }
        }
        return null;
    }

    /**
     * @param $names
     * @param $limit
     * @return self[]
     */
    public static function findAllByNames($names, $limit = INF)
    {
        $result = [];
        $data = self::indexData();
        $i = 0;
        foreach ($data as $item) {
            if (in_array($item->name, $names)) {
                $result[] = $item;
                $i++;
            }
            if ($i >= $limit) {
                break;
            }
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_param';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'value', 'sort_order'], 'required'],
            [['sort_order'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['value'], 'string', 'max' => 10000],
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
            'value' => 'Value',
            'sort_order' => 'Sort Order',
        ];
    }
}
