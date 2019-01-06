<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 6/11/2017
 * Time: 10:31 PM
 */

/**
 * @var string url
 */
if (!isset($url)) {
    $url = \yii\helpers\Url::current([], true);
}

?>
<div id="fb-comments" class="fb-comments" data-href="<?= $url ?>" data-numposts="5" data-width="100%"></div>
