<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\searchModels\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'username',
            [
                'attribute' => 'type',
                'value' => function (\backend\models\User $model) {
                    return $model->typeLabel();
                }
            ],
            [
                'attribute' => 'status',
                'value' => function (\backend\models\User $model) {
                    return $model->statusLabel();
                }
            ],
            [
                'attribute' => 'role',
                'value' => function (\backend\models\User $model) {
                    return $model->roleLabel();
                }
            ],
            'created_time:datetime',
            'updated_time:datetime',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
