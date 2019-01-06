<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\searchModels\Banner */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Banners';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Banner', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'image_id',
                'format' => 'raw',
                'value' => function (\backend\models\Banner $model) {
                    if ($image = $model->image) {
                        return $image->img('100x100');
                    }
                    return '';
                }
            ],
            'title',
            [
                'attribute' => 'position',
                'value' => function (\backend\models\Banner $model) {
                    return $model->positionLabel();
                }
            ],
            'sort_order',
            'start_time:datetime',
            'end_time:datetime',
            'active:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
