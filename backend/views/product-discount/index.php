<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductDiscountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Discounts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-discount-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Product Discount', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'occasion:ntext',
            'percentage',
            'start_time',
            //'end_time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
