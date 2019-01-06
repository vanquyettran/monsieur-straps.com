<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Article */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-view">

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
            'name',
            'slug',
            'heading',
            'page_title',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'description',
            'video_src',
            'content:ntext',
            'active',
            'visible',
            'featured',
            'allow_indexing',
            'allow_following',
            'view_count',
            'published_time',
            'created_time',
            'updated_time',
            'creator_id',
            'updater_id',
            'avatar_image_id',
            'article_category_id',
        ],
    ]) ?>

</div>
