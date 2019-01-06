<?php
/**
 * @var $this \yii\web\View
 * @var $models \frontend\models\Product[]
 */

use yii\helpers\Html;

/**
 * @var $imageSize int
 */
if (!isset($imageSize)) {
    $imageSize = null;
}

foreach ($models as $model) {
    $imageGroup = '';

    if ($avatarImage = $model->avatarImage) {
        foreach ($model->productToDetailedImages as $productToDetailedImage) {
            if ($detailedImage = $productToDetailedImage->detailedImage) {
                if ($detailedImage->id !== $avatarImage->id) {
                    $imageGroup
                        = $avatarImage->img($imageSize, ['class' => 'released-only', 'alt' => $model->name])
                        . $detailedImage->img($imageSize, ['class' => 'focused-only', 'alt' => $model->name]);
                    break;
                }
            }
        }

        if ($imageGroup === '') {
            $imageGroup = $avatarImage->img($imageSize, ['alt' => $model->name]);
        }
    }

    $discount_percent = $model->totalDiscountPercentage();

    if ($discount_percent > 0) {
        $imageGroup .= '<span class="discount-sticky">' . $discount_percent . '% OFF</span>';
    }

    ?>
        <?= $model->viewAnchor(
            '<div class="image"><span>' . $imageGroup . '</span></div>'
            . '<h3 class="name" data-max-line-count="2">' . Html::encode($model->name) . '</h3>'
            . '<div class="price">'
            . ($discount_percent > 0
                ? '<span class="price-old">' . $model->formatPrice($model->price) . '</span> '
                : '')
            . '<span class="price-sale">' . $model->formatPrice($model->discountedPrice()) . '</span>'
            . '</div>',
            [
                'class' => 'item',
                'data-focusable' => 'true',
            ]
        ); ?>
    <?php
}
