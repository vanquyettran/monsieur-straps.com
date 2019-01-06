<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\searchModels\ProductOrder */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'total_value',
                'value' => function (\backend\models\ProductOrder $model) {
                    return number_format($model->total_value);
                }
            ],
            [
                'attribute' => 'delivery_fee',
                'value' => function (\backend\models\ProductOrder $model) {
                    return number_format($model->delivery_fee);
                }
            ],
            'customer_name',
            'customer_phone',
            //'customer_email:email',
            'customer_address',
            //'customer_place_t1_id',
            //'customer_place_t2_id',
            //'customer_place_t3_id',
            [
                'attribute' => 'status',
                'value' => function (\backend\models\ProductOrder $model) {
                    return $model->statusLabel();
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    \backend\models\ProductOrder::$allStatusLabels,
                    ['class'=>'form-control', 'prompt' => '']
                )
            ],
            'created_time:datetime',
            //'updated_time',
            //'updated_user_id',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}'
            ],
        ],
    ]); ?>
</div>
