<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductOrderToProduct */

$this->title = 'Update Product Order To Product: ' . $model->product_order_id;
$this->params['breadcrumbs'][] = ['label' => 'Product Order To Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->product_order_id, 'url' => ['view', 'product_order_id' => $model->product_order_id, 'product_id' => $model->product_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-order-to-product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
