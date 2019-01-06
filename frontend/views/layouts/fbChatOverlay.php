<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/30/2018
 * Time: 5:37 PM
 */

use common\models\SiteParam;

if ($fb = SiteParam::findOneByName(SiteParam::FACEBOOK_PAGE)) {
    ?>
    <div class="fb-msg-overlay">
        <div class="fb-msg-title" onclick="this.parentNode.classList.toggle('active')">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="96 93 322 324">
                <path
                    d="M257 93c-88.918 0-161 67.157-161 150 0 47.205 23.412 89.311 60 116.807V417l54.819-30.273C225.449 390.801 240.948 393 257 393c88.918 0 161-67.157 161-150S345.918 93 257 93zm16 202l-41-44-80 44 88-94 42 44 79-44-88 94z"
                    fill="white"></path>
            </svg>
            <span>Chat với <?= Yii::$app->name ?></span>
        </div>
        <div class="fb-msg-content">
            <div class="fb-page"
                 data-href="<?= $fb->value ?>"
                 data-small-header="true"
                 data-height="300"
                 data-width="250"
                 data-tabs="messages"
                 data-adapt-container-width="false"
                 data-hide-cover="true"
                 data-show-facepile="false"
                 data-show-posts="false">
            </div>
        </div>
    </div>
    <?php
}
?>
