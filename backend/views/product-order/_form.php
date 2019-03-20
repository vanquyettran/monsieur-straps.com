<?php

use backend\models\ProductOrder;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList(ProductOrder::$allStatusLabels) ?>

    <?= $form->field($model, 'delivery_fee')->textInput() ?>

    <?= $form->field($model, 'user_note')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<h3>Order products</h3>

<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>ID</th>
        <th>Code</th>
        <th>Name</th>
        <th>Customizing</th>
        <th>Price (VND)</th>
        <th>Final Price (VND)</th>
        <th>Quantity</th>
        <th></th>
    </tr>
    </thead>

    <tbody>
    <?php

    foreach ($model->productOrderToProducts as $item) {
        ?>
        <tr>
            <td><?= $item->product_id ?></td>
            <td><?= $item->product_code ?></td>
            <td><?= $item->product_name ?></td>
            <td><?= $item->product_customizing ?></td>
            <td><?= number_format($item->product_price) ?></td>
            <td><?= number_format($item->product_discounted_price) ?></td>
            <td><?= number_format($item->product_quantity) ?></td>
            <td>
                <?= $item->product->viewAnchor('<span class="glyphicon glyphicon-new-window"></span>', [ 'target' => '_blank' ]) ?>
            </td>
        </tr>
        <?php
    }

    ?>
    </tbody>
</table>

<h3>Order details</h3>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        [
            'attribute' => 'status',
            'value' => function (\backend\models\ProductOrder $model) {
                return $model->statusLabel();
            }
        ],
        'user_note',
        [
            'attribute' => 'total_value',
            'value' => function (\backend\models\ProductOrder $model) {
                return number_format($model->total_value) . ' VND';
            }
        ],
        [
            'attribute' => 'delivery_fee',
            'value' => function (\backend\models\ProductOrder $model) {
                return number_format($model->delivery_fee) . ' VND';
            }
        ],
        'customer_name',
        'customer_email:email',
        'customer_address',
        'payment_gateway_name',
        [
            'attribute' => 'payment_gateway_response',
            'format' => 'raw',
            'value' => function (\backend\models\ProductOrder $model) {
                return '<pre>' . json_encode(json_decode($model->payment_gateway_response, true), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)
                . '</pre>';
            }
        ],
        'created_time',
        'updated_time',
        'updatedUser.username',
    ],
]) ?>