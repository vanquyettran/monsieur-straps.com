<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 4/5/2017
 * Time: 12:44 AM
 */

namespace common\models;


class UrlParam
{
    const ID = 'id';
    const SLUG = 'slug';
    const PARENT_SLUG = 'parent_slug';
    const CATEGORY_SLUG = 'category_slug';
    const ALIAS = 'alias';
    const TYPE = 'type';
    const ACTION_ID = 'action_id';
    const PAGE = 'page';
    const KEYWORD = 'keyword';
    const NAME = 'name';
    const VALUE = 'value';
    const FIELD = 'field';
    const VIEW_ID = 'view_id';
    const JSON_PARAMS = 'json_params';
    const AMP = 'amp';
    const SHARING_TITLE = 'sharing_title';
    const SHARING_DESCRIPTION = 'sharing_description';
    const SHARING_IMAGE_SRC = 'sharing_image_src';
    const ORDER_CODE = 'order_code';

    public static function allParams()
    {
        $allParams = [
            self::SLUG,
            self::PARENT_SLUG,
            self::CATEGORY_SLUG,
            self::ALIAS,
            self::TYPE,
            self::ACTION_ID,
            self::PAGE,
            self::KEYWORD,
            self::NAME,
            self::VALUE,
            self::FIELD,
            self::AMP,
            self::SHARING_TITLE,
            self::SHARING_DESCRIPTION,
            self::SHARING_IMAGE_SRC,
        ];

        return array_combine($allParams, $allParams);
    }

    public static function canonicalParams()
    {
        $canonicalParams = [
            self::SLUG,
            self::PARENT_SLUG,
            self::CATEGORY_SLUG,
            self::ALIAS,
            self::TYPE,
        ];

        return array_combine($canonicalParams, $canonicalParams);
    }
}