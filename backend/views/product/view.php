<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

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
        &middot;
        <a href="<?= Url::to([
            'product-customization/index',
            'product_id' => $model->id
        ]) ?>">All Customizations</a>
        &middot;
        <a href="<?= Url::to([
            'product-customization/create',
            'product_id' => $model->id
        ]) ?>">NEW Customization</a>
        &middot;
        <a href="<?= Url::to([
            'product/reorder-detailed-images',
            'id' => $model->id
        ]) ?>" target="_blank">Reorder Detailed Images</a>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'slug',
            'heading',
            'page_title',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'description',
            'code',
            'price',
            'long_description:ntext',
            'details:ntext',
            'active',
            'visible',
            'featured',
            'allow_indexing',
            'allow_following',
            'production_status',
            'sort_order',
            'published_time',
            'created_time',
            'updated_time',
            'view_count',
            'creator_id',
            'updater_id',
            'avatar_image_id',
            'product_category_id',
        ],
    ]) ?>

</div>
