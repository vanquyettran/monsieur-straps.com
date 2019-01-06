<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\searchModels\PlaceT3 */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Place T3s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="place-t3-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Place T3', ['create'], ['class' => 'btn btn-success']) ?>
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
                'value' => function (\backend\models\PlaceT3 $model) {
                    return $model->typeLabel();
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'type',
                    \backend\models\PlaceT3::$allTypeLabels,
                    ['class'=>'form-control', 'prompt' => '']
                )
            ],
            'place_t1_id',
            'place_t2_id',
            'placeT1.name',
            'placeT2.name',
            //'latitude',
            //'longitude',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
