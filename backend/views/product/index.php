<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'slug',
            [
                'attribute' => 'production_status',
                'value' => function (\backend\models\Product $model) {
                    return $model->productionStatusLabel();
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'production_status',
                    $searchModel->getProductionStatusLabels(),
                    ['class'=>'form-control', 'prompt' => '']
                )
            ],
            [
                'attribute' => 'product_category_id',
                'value' => function (\backend\models\Product $model) {
                    if ($category = $model->productCategory) {
                        return $category->name;
                    }
                    return null;
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'product_category_id',
                    \backend\models\ProductCategory::dropDownListData(),
                    ['class'=>'form-control', 'prompt' => '']
                )
            ],
            'active:boolean',
            'visible:boolean',
            'featured:boolean',
            'allow_indexing:boolean',
            'published_time:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
