<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2/2/2018
 * Time: 2:25 AM
 */

use frontend\models\Article;
use yii\helpers\Html;

$articles_1 = Article::find()
    ->andWhere(['active' => 1, 'visible' => 1])
    ->andWhere(['<', 'published_time', date('Y-m-d H:i:s')])
    ->andWhere(['>', 'published_time', date('Y-m-d H:i:s', time() - 2 * 86400)])
    ->limit(2)
    ->orderBy('view_count desc')
    ->indexBy('id')
    ->all();

$not_ids = array_keys($articles_1);

$articles_2 = Article::find()
    ->andWhere(['active' => 1, 'visible' => 1])
    ->andWhere(['<', 'published_time', date('Y-m-d H:i:s')])
    ->andWhere(['>', 'published_time', date('Y-m-d H:i:s', time() - 7 * 86400)])
    ->andWhere(['not in', 'id', $not_ids])
    ->limit(4 - count($not_ids))
    ->orderBy('view_count desc')
    ->indexBy('id')
    ->all();

$not_ids = array_merge($not_ids, array_keys($articles_2));

$articles_3 = Article::find()
    ->andWhere(['active' => 1, 'visible' => 1])
    ->andWhere(['<', 'published_time', date('Y-m-d H:i:s')])
    ->andWhere(['not in', 'id', $not_ids])
    ->limit(6 - count($not_ids))
    ->orderBy('published_time desc')
    ->indexBy('id')
    ->all();

$not_ids = array_merge($not_ids, array_keys($articles_3));

$articles_4 = Article::find()
    ->andWhere(['active' => 1, 'visible' => 1])
    ->andWhere(['<', 'published_time', date('Y-m-d H:i:s')])
    ->andWhere(['not in', 'id', $not_ids])
    ->limit(8 - count($not_ids))
    ->orderBy('rand()')
    ->indexBy('id')
    ->all();

/**
 * @var $articles Article[]
 */
$articles = array_merge($articles_1, $articles_2, $articles_3, $articles_4);

?>
<div class="aside-featured-stories aspect-ratio __3x2">
    <div class="title">Tin nổi bật</div>
    <ul class="clr">
        <?php
        foreach ($articles as $item) {
            ?>
            <li>
                <?= $item->viewAnchor(
                    '<div class="image"><span>'
                    . $item->avatarImg()
                    . '</span></div>'

                    . '<h4 class="name">'
                    . Html::encode($item->name)
                    . '</h4>'
                ) ?>
            </li>
            <?php
        }
        ?>
    </ul>
</div>

