<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductAttributeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Attributes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-attribute-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Product Attribute', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'product_attribute_group_id',
                'value' => function (\backend\models\ProductAttribute $model) {
                    if ($group = $model->productAttributeGroup) {
                        return $group->name;
                    }
                    return null;
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'product_attribute_group_id',
                    ArrayHelper::map(\backend\models\ProductAttributeGroup::find()->all(), 'id', 'name'),
                    ['class'=>'form-control', 'prompt' => '']
                )
            ],
            'name',
            'value',
            'sort_order',
            'illustration_image_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
