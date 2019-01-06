<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\PlaceT2 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Place T2s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="place-t2-view">

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
            'type',
            'place_t1_id',
            'latitude',
            'longitude',
        ],
    ]) ?>

</div>
