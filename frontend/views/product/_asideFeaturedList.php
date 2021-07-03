<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2/2/2018
 * Time: 2:25 AM
 */

use frontend\models\Product;
use yii\helpers\Html;

$products_1 = Product::find()
    ->andWhere(['active' => 1, 'visible' => 1])
    ->andWhere(['<', 'published_time', date('Y-m-d H:i:s')])
    ->andWhere(['>', 'published_time', date('Y-m-d H:i:s', time() - 2 * 86400)])
    ->limit(2)
    ->orderBy('view_count desc')
    ->indexBy('id')
    ->all();

$not_ids = array_keys($products_1);

$products_2 = Product::find()
    ->andWhere(['active' => 1, 'visible' => 1])
    ->andWhere(['<', 'published_time', date('Y-m-d H:i:s')])
    ->andWhere(['>', 'published_time', date('Y-m-d H:i:s', time() - 7 * 86400)])
    ->andWhere(['not in', 'id', $not_ids])
    ->limit(4 - count($not_ids))
    ->orderBy('view_count desc')
    ->indexBy('id')
    ->all();

$not_ids = array_merge($not_ids, array_keys($products_2));

$products_3 = Product::find()
    ->andWhere(['active' => 1, 'visible' => 1])
    ->andWhere(['<', 'published_time', date('Y-m-d H:i:s')])
    ->andWhere(['not in', 'id', $not_ids])
    ->limit(6 - count($not_ids))
    ->orderBy('published_time desc')
    ->indexBy('id')
    ->all();

$not_ids = array_merge($not_ids, array_keys($products_3));

$products_4 = Product::find()
    ->andWhere(['active' => 1, 'visible' => 1])
    ->andWhere(['<', 'published_time', date('Y-m-d H:i:s')])
    ->andWhere(['not in', 'id', $not_ids])
    ->limit(8 - count($not_ids))
    ->orderBy('rand()')
    ->indexBy('id')
    ->all();

/**
 * @var $products Product[]
 */
$products = array_merge($products_1, $products_2, $products_3, $products_4);

?>
<div class="aside-featured-stories aspect-ratio <?= Yii::$app->params['product_image_ratio_class'] ?>">
    <div class="title">Tin nổi bật</div>
    <ul class="clr">
        <?php
        foreach ($products as $item) {
            ?>
            <li>
                <?= $item->viewAnchor(
                    '<div class="image"><span>'
                    . $item->avatarImg()
                    . '</span></div>'

                    . '<h4 class="name">'
                    . Html::encode($item->name)
                    . '</h4>'
                ) ?>
            </li>
            <?php
        }
        ?>
    </ul>
</div>

