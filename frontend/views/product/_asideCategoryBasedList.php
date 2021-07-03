<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2/6/2018
 * Time: 11:14 PM
 */

use frontend\models\ProductCategory;

/**
 * @var $product_categories ProductCategory[]
 */
$product_categories = array_filter(ProductCategory::indexData(true), function ($item) {
    /**
     * @var $item ProductCategory
     */
    return strpos($item->displaying_areas, json_encode(ProductCategory::DISPLAYING_AREA__ASIDE)) !== false;
});

foreach ($product_categories as $category) {
    /**
     * @var $products \frontend\models\Product[]
     */
    $products = $category->getAllProducts()
        ->andWhere(['active' => 1, 'visible' => 1])
        ->andWhere(['<', 'published_time', date('Y-m-d H:i:s')])
        ->orderBy('published_time desc')
        ->limit(5)
        ->all();
    ?>
    <div class="aside-story-list aspect-ratio <?= Yii::$app->params['product_image_ratio_class'] ?>">
        <?= $category->viewAnchor(null, ['class' => 'title']) ?>
        <ul>
            <?php
            $i = 0;
            foreach ($products as $product) {
                $i++;
                ?>
                <li>
                    <?php
                    if (1 == $i) {
                        echo $product->viewAnchor(
                            '<div class="image"><span>' . $product->avatarImg() . '</span></div>'
                            . '<h3 class="name">' . $product->name . '</h3>',
                            ['class' => 'clr']
                        );
                    } else {
                        echo $product->viewAnchor();
                    }
                    ?>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
    <?php
}
?>

