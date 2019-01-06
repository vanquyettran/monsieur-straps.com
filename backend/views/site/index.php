<?php
use backend\models\Game;
use yii\helpers\Url;
use \yii\helpers\Html;
/* @var $this yii\web\View */
use backend\models\Article;
use common\modules\image\models\Image;
$this->title = Yii::$app->name;
?>
<div class="site-index">

    <div align="center">
        <h1>Congratulations!</h1>
        <p class="lead">You have successfully logged in. Let's start with...</p>
        <p><a class="btn btn-lg btn-success" href="<?= Url::to(['article/create']) ?>"><span class="glyphicon glyphicon-edit"></span> NEW Article</a></p>
        <p><a class="btn btn-lg btn-primary" href="<?= Url::to(['image/default/create']) ?>"><span class="glyphicon glyphicon-cloud-upload"></span> Image</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-6">
                <h2>Articles (<?= Article::find()->count() ?>)</h2>

                <ul>
                    <?php
                    foreach (Article::find()->orderBy('published_time desc')->limit(20)->all() as $item) {
                        /**
                         * @var $item Article
                         */
                        echo '<li style="margin-top: 0.1em">'
                            . Html::a($item->avatarImg('30x30') . " $item->name", [
                                '/article/update', 'id' => $item->id
                            ])
                            . '</li>';
                    }
                    ?>
                </ul>

                <p>
                    <a class="btn btn-default" href="<?= Url::to([
                        '/article/index',
                    ]) ?>">View all &raquo;</a>
                </p>
            </div>
            <div class="col-lg-6">
                <h2>Images (<?= Image::find()->count() ?>)</h2>

                <ul id="motivatebox">
                    <?php
                    foreach (Image::find()->orderBy('created_time desc')->limit(20)->all() as $item) {
                        /**
                         * @var $item Image
                         */
                        echo '<li style="margin-top: 0.1em">'
                            . Html::a($item->img('30x30') . " $item->name", [
                                '/image/default/update', 'id' => $item->id
                            ])
                            . '</li>';
                    }
                    ?>
                </ul>

                <p>
                    <a class="btn btn-default" href="<?= Url::to([
                        '/image/default/index',
                    ]) ?>">View all &raquo;</a>
                </p>
            </div>
        </div>

    </div>
</div>
