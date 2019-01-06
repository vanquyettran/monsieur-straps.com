<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\searchModels\ArticleCategory */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Article Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Article Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'slug',
            [
                'attribute' => 'type',
                'value' => function (\backend\models\ArticleCategory $model) {
                    return $model->typeLabel();
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'type',
                    (new \backend\models\ArticleCategory())->getTypeLabels(),
                    ['class'=>'form-control', 'prompt' => '']
                )
            ],
            [
                'attribute' => 'parent_id',
                'value' => function (\backend\models\ArticleCategory $model) {
                    if ($parent = $model->parent) {
                        return $parent->name;
                    }
                    return null;
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'parent_id',
                    \backend\models\ArticleCategory::dropDownListData(),
                    ['class'=>'form-control', 'prompt' => '']
                )
            ],
            'active:boolean',
            'visible:boolean',
            'allow_indexing:boolean',
            'allow_following:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
