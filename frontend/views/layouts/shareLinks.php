<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/30/2018
 * Time: 9:18 PM
 */

/**
 * @var $seoInfo \frontend\models\SeoInfo
 */
$seoInfo = $this->context->seoInfo;
?>
<div class="share-links">
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $seoInfo->canonicalLink ?>"
       title="Chia sẻ trên Facebook"
       target="_blank">
        <i class="icon facebook-share-icon"></i>
    </a>
    <a href="https://twitter.com/intent/tweet?text=<?= $seoInfo->heading ?>&amp;url=<?= $seoInfo->canonicalLink ?>&amp;via=<?= Yii::$app->name ?>"
       title="Chia sẻ trên Twitter"
       target="_blank">
        <i class="icon twitter-share-icon"></i>
    </a>
    <a href="http://www.tumblr.com/share/link?url=<?= $seoInfo->canonicalLink ?>"
       title="Chia sẻ trên Tumblr"
       target="_blank">
        <i class="icon tumblr-share-icon"></i>
    </a>
    <a href="http://www.pinterest.com/pin/create/button/?url=<?= $seoInfo->canonicalLink ?>&amp;media=<?= $seoInfo->image_src ?>&amp;description=<?= $seoInfo->heading ?>"
       title="Chia sẻ trên Pinterest"
       target="_blank">
        <i class="icon pinterest-share-icon"></i>
    </a>
</div>
