<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 6/19/2018
 * Time: 12:51 PM
 */
use common\models\SiteParam;
use yii\helpers\Url;

$logo_block = function () {
    ?>
    <a class="logo" href="<?= Url::home() ?>" title="<?= Yii::$app->name ?>">
        <?= $this->render('svgLogo') ?>
    </a>
    <div class="socials">
        <?php
        if ($fb = SiteParam::findOneByName(SiteParam::FACEBOOK_PAGE)) {
            ?>
            <a href="<?= $fb->value ?>" title="Facebook" target="_blank">
                <i class="icon facebook-icon"></i>
            </a>
            <?php
        }
        if ($ins = SiteParam::findOneByName(SiteParam::INSTAGRAM_PAGE)) {
            ?>
            <a href="<?= $ins->value ?>" title="Instagram" target="_blank">
                <i class="icon instagram-icon"></i>
            </a>
            <?php
        }
        if ($yt = SiteParam::findOneByName(SiteParam::YOUTUBE_CHANNEL)) {
            ?>
            <a href="<?= $yt->value ?>" title="Youtube" target="_blank">
                <i class="icon youtube-icon"></i>
            </a>
            <?php
        }
        if ($twt = SiteParam::findOneByName(SiteParam::TWITTER_PAGE)) {
            ?>
            <a href="<?= $twt->value ?>" title="Pinterest" target="_blank">
                <i class="icon twitter-icon"></i>
            </a>
            <?php
        }
        if ($tumblr = SiteParam::findOneByName(SiteParam::TUMBLR_PAGE)) {
            ?>
            <a href="<?= $tumblr->value ?>" title="Tumblr" target="_blank">
                <i class="icon tumblr-icon"></i>
            </a>
            <?php
        }
        if ($pin = SiteParam::findOneByName(SiteParam::PINTEREST_PAGE)) {
            ?>
            <a href="<?= $pin->value ?>" title="Pinterest" target="_blank">
                <i class="icon pinterest-icon"></i>
            </a>
            <?php
        }
        ?>
    </div>
    <?php
};

$footer_c1_title = SiteParam::findOneByName(SiteParam::FOOTER_C1_TITLE);
$footer_c2_title = SiteParam::findOneByName(SiteParam::FOOTER_C2_TITLE);
?>
<footer class="container">
    <div class="inner">
        <div class="sm-logo-block sm-only">
            <?php $logo_block(); ?>
        </div>
        <div class="links-block ft-table <?= $footer_c1_title !== null ? 'has-c1' : '' ?> <?= $footer_c2_title !== null ? 'has-c2' : '' ?>">
            <div class="ft-row">
                <div class="ft-col sm-hidden">
                    <?php $logo_block(); ?>
                </div>
                <?php
                if ($footer_c1_title !== null) {
                    ?>
                    <div class="ft-col">
                        <h3 class="title"><?= $footer_c1_title->value ?></h3>
                        <ul class="links">
                            <?php
                            foreach (SiteParam::findAllByNames([SiteParam::FOOTER_C1_LINK]) as $c1_link) {
                                $name_link = explode(' | ', $c1_link->value, 2);
                                if (count($name_link) == 2) {
                                    ?>
                                    <li>
                                        <a href="<?= $name_link[1] ?>" title="<?= $name_link[0] ?>">
                                            <?= $name_link[0] ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <?php
                }
                ?>
                <?php
                if ($footer_c2_title !== null) {
                    ?>
                    <div class="ft-col">
                        <h3 class="title"><?= $footer_c2_title->value ?></h3>
                        <ul class="links">
                            <?php
                            foreach (SiteParam::findAllByNames([SiteParam::FOOTER_C2_LINK]) as $c2_link) {
                                $name_link = explode(' | ', $c2_link->value, 2);
                                if (count($name_link) == 2) {
                                    ?>
                                    <li>
                                        <a href="<?= $name_link[1] ?>" title="<?= $name_link[0] ?>">
                                            <?= $name_link[0] ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <?php
                }
                ?>
                <div class="ft-col">
                    <h3 class="title">Liên hệ</h3>
                    <ul class="links">
                        <?php
                        $phone_values = array_map(function (SiteParam $param) {
                            return \yii\helpers\Html::a($param->value, "tel:$param->value");
                        }, SiteParam::findAllByNames([SiteParam::PHONE]));

                        $email_values = array_map(function (SiteParam $param) {
                            return \yii\helpers\Html::a($param->value, "mailto:$param->value");
                        }, SiteParam::findAllByNames([SiteParam::EMAIL]));

                        $workshops = SiteParam::findAllByNames([SiteParam::WORKSHOP]);

                        if (count($phone_values) > 0) {
                            ?>
                            <li>Hotline: <?= implode(', ', $phone_values) ?></li>
                            <?php
                        }

                        if (count($email_values) > 0) {
                            ?>
                            <li>Email: <?= implode(', ', $email_values) ?></li>
                            <?php
                        }

                        foreach ($workshops as $workshop) {
                            ?>
                            <li><?= $workshop->value ?></li>
                            <?php
                        }
                        ?>
                    </ul>

                </div>
            </div>
        </div>
        <div class="copyright">
            &copy; Copyright <?= date('Y') ?>
        </div>
        <div class="company">
            <?php
            if ($company = SiteParam::findOneByName(SiteParam::COMPANY)) {
                echo $company->value;
            }
            ?>
        </div>
        <div class="address">
            <?php
            $addresses = array_values(SiteParam::findAllByNames([SiteParam::ADDRESS]));
            foreach ($addresses as $index => $address) {
                ?>
                <div><?= count($addresses) > 1 ? ('Địa chỉ ' . ($index + 1)) . ': ' : '' ?><?= $address->value ?></div>
                <?php
            }
            ?>
        </div>
    </div>
</footer>
