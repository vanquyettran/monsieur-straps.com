<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\searchModels\MenuItem */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Menu Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Menu Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'menu_id',
                'value' => function (\backend\models\MenuItem $model) {
                    return $model->menuName();
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'menu_id',
                    $searchModel->getMenuNames(),
                    ['class'=>'form-control', 'prompt' => '']
                )
            ],
            [
                'attribute' => 'parent_id',
                'value' => function (\backend\models\MenuItem $model) {
                    if ($parent = $model->parent) {
                        return $parent->label;
                    }
                    return null;
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'parent_id',
                    \backend\models\MenuItem::dropDownListData(),
                    ['class'=>'form-control', 'prompt' => '']
                )
            ],
            'label',
            'sort_order',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
