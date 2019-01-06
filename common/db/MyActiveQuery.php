<?php
namespace common\db;

use Yii;
use yii\db\ActiveQuery;
use yii\di\Instance;
use yii\db\ActiveQueryInterface;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MyActiveQuery
 *
 * @author Quyet Tran <quyettvq at gmail.com>
 */
class MyActiveQuery extends ActiveQuery implements ActiveQueryInterface
{
    /**
     * @var bool
     */
    public $enableCache = true;

    /**
     * @var int
     */
    public $cacheDuration = 3600;

    /**
     * MyActiveQuery constructor.
     * @param string $modelClass
     * @param array $config
     */
    public function __construct($modelClass, array $config = [])
    {
        if (isset(Yii::$app->params['myActiveQuery'])) {
            foreach (Yii::$app->params['myActiveQuery'] as $key => $value) {
                if ($this->hasProperty($key)) {
                    $config[$key] = $value;
                }
            }
        }

        parent::__construct($modelClass, $config);
    }

    /** CACHING */

    /**
     * @param null $db
     * @return array|bool|mixed|\yii\db\ActiveRecord[]
     */
    public function all($db = null)
    {
        $result = false;
        $cache_key = $this->getCacheKey(__METHOD__, $db);
        if ($this->enableCache) {
            $result = Yii::$app->cache->get($cache_key);
        }
        if (!$this->enableCache || $result === false) {
            $result = parent::all($db);
            if ($this->enableCache) {
                Yii::$app->cache->set($cache_key, $result, $this->cacheDuration);
            }
        }
        return $result;
    }

    /**
     * @param null $db
     * @return array|bool|mixed|null|string|\yii\db\ActiveRecord
     */
    public function one($db = null)
    {
        $result = false;
        $cache_key = $this->getCacheKey(__METHOD__, $db);
        if ($this->enableCache) {
            $result = Yii::$app->cache->get($cache_key);
        }
        if (!$this->enableCache || $result === false) {
            $result = parent::one($db);
            if ($result === false) {
                $result = 'F';
            }
            if ($this->enableCache) {
                Yii::$app->cache->set($cache_key, $result, $this->cacheDuration);
            }
        }
        if ($result === 'F') {
            $result = false;
        }
        return $result;
    }

    /**
     * @param string $q
     * @param null $db
     * @return bool|int|mixed|string
     */
    public function count($q = '*', $db = null)
    {
        $result = false;
        $cache_key = $this->getCacheKey(__METHOD__, [$db, $q]);
        if ($this->enableCache) {
            $result = Yii::$app->cache->get($cache_key);
        }
        if (!$this->enableCache || (is_bool($result) && !$result)) {
            $result = parent::count($q, $db);
            if ($this->enableCache) {
                Yii::$app->cache->set($cache_key, $result, $this->cacheDuration);
            }
        }
        return $result;
    }

    /**
     * @param $method
     * @param $params
     * @return string
     */
    protected function getCacheKey($method, $params)
    {
        $query = clone $this;
        if ($query->primaryModel !== null) {
            $query->primaryModel = "{$query->primaryModel->className()}#" . json_encode($query->primaryModel->primaryKey);
        }
        return [
            $method,
            $query,
            $params
        ];
    }
}
