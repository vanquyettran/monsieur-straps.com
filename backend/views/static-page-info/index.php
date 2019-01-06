<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\searchModels\StaticPageInfo */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Static Page Infos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="static-page-info-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Static Page Info', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            [
                'attribute' => 'route',
                'value' => function (\backend\models\StaticPageInfo $model) {
                    return $model->routeLabel();
                }
            ],
            'url_pattern',
            'active:boolean',
            'allow_indexing:boolean',
            'allow_following:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
