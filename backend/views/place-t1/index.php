<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\searchModels\PlaceT1 */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Place T1s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="place-t1-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Place T1', ['create'], ['class' => 'btn btn-success']) ?>
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
                'value' => function (\backend\models\PlaceT1 $model) {
                    return $model->typeLabel();
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'type',
                    \backend\models\PlaceT1::$allTypeLabels,
                    ['class'=>'form-control', 'prompt' => '']
                )
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
