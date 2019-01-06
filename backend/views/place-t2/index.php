<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\searchModels\PlaceT2 */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Place T2s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="place-t2-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Place T2', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function (\backend\models\PlaceT2 $model) {
                    return $model->typeLabel();
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'type',
                    \backend\models\PlaceT2::$allTypeLabels,
                    ['class'=>'form-control', 'prompt' => '']
                )
            ],
            'place_t1_id',
            'placeT1.name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
