<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 6/11/2017
 * Time: 11:24 PM
 */

/**
 * @var string url
 */
if (!isset($url)) {
    $url = \yii\helpers\Url::current([], true);
}
?>
<div class="social-shares clr">
    <div class="item">
        <div id="fb-like" class="fb-like" data-href="<?= $url ?>" data-layout="button_count" data-size="small" data-action="like" data-show-faces="true" data-share="true"></div>
    </div>
    <div class="item">
        <a class="twitter-share-button" href="https://twitter.com/intent/tweet">Tweet</a>
    </div>
    <div class="item">
        <div class="g-plus" data-action="share"></div>
    </div>
</div>

<style>
    .social-shares {
        display: block;
        line-height: 0;
    }
    .social-shares > .item {
        display: inline-block;
    }
    .social-shares > .item > * {
        display: block !important;
        float: left !important;
        line-height: normal;
    }
</style>
