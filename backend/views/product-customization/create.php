<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model backend\models\ProductCustomization */

$this->title = 'Create Product Customization';
if ($product = $model->product) {
    $this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['product/view', 'id' => $product->id]];
    $this->params['breadcrumbs'][] = ['label' => 'Product Customizations', 'url' => ['index', 'product_id' => $product->id]];
} else {
    $this->params['breadcrumbs'][] = ['label' => 'Product Customizations', 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-customization-create">

    <?php
    if ($product) {
        ?>
        <h1>
            Create
            <a href="<?= Url::to(['product/update', 'id' => $product->id]) ?>"
               ><?= $product->name ?></a>'s Customization
        </h1>
        <?php
    } else {
        ?>
        <h1><?= Html::encode($this->title) ?></h1>
        <?php
    }
    ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
