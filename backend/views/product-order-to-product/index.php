<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\searchModels\ProductOrderToProduct */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Order To Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-order-to-product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Product Order To Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'product_order_id',
            'product_id',
            'product_quantity',
            'product_code',
            'product_name',
            //'product_price',
            //'product_discounted_price',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
