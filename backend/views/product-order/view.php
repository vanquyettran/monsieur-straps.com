<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductOrder */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Product Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'total_value',
            'delivery_fee',
            'customer_name',
            'customer_phone',
            'customer_email:email',
            'customer_address',
            'customer_place_t1_id',
            'customer_place_t2_id',
            'customer_place_t3_id',
            'status',
            'created_time',
            'updated_time',
            'updated_user_id',
            'user_note',
        ],
    ]) ?>

</div>
