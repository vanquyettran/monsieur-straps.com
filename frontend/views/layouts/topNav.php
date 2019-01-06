<?php
/**
 * Created by PhpStorm.
 * User: Quyet
 * Date: 4/24/2018
 * Time: 2:21 PM
 */
use yii\helpers\Url;
/**
 * @var $menu vanquyet\menu\Menu
 */
?>
<div class="container" id="top-nav">
    <div class="inner">
        <span class="menu-toggle lg-hidden"
              onclick="document.querySelector('html').classList.toggle('menu-active')"
        >
            <!--<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 width="200px" height="150.332px" viewBox="0 0 200 150.332" enable-background="new 0 0 200 150.332"
                 xml:space="preserve">
                <path d="M198.866,14.339c0,7.549-6.118,13.667-13.666,13.667H14.731c-7.549,0-13.667-6.119-13.667-13.667l0,0c0-7.548,6.119-13.667,13.667-13.667H185.2C192.748,0.672,198.866,6.791,198.866,14.339L198.866,14.339z"></path>
                <path d="M162.088,76.711c0,7.96-6.452,14.411-14.412,14.411H15.974c-7.961,0-14.414-6.451-14.414-14.411l0,0c0-7.96,6.453-14.413,14.414-14.413h131.702C155.636,62.298,162.088,68.75,162.088,76.711L162.088,76.711z"></path>
                <path d="M199.115,136.102c0,7.549-6.12,13.667-13.667,13.667H14.98c-7.55,0-13.669-6.118-13.669-13.667l0,0c0-7.549,6.119-13.668,13.669-13.668h170.468C192.995,122.434,199.115,128.553,199.115,136.102L199.115,136.102z"></path>
            </svg>-->

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                <path d="M436 124H12c-6.627 0-12-5.373-12-12V80c0-6.627 5.373-12 12-12h424c6.627 0 12 5.373 12 12v32c0 6.627-5.373 12-12 12zm0 160H12c-6.627 0-12-5.373-12-12v-32c0-6.627 5.373-12 12-12h424c6.627 0 12 5.373 12 12v32c0 6.627-5.373 12-12 12zm0 160H12c-6.627 0-12-5.373-12-12v-32c0-6.627 5.373-12 12-12h424c6.627 0 12 5.373 12 12v32c0 6.627-5.373 12-12 12z"></path>
            </svg>
        </span>
        <ul class="menu clr">
            <li class="menu-header lg-hidden">
                <span class="close-button" onclick="document.querySelector('html').classList.remove('menu-active')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 408 408" width="17px" height="17px">
                        <path fill="#fff" d="M408 178.5H96.9L239.7 35.7 204 0 0 204l204 204 35.7-35.7L96.9 229.5H408v-51z"></path>
                    </svg>
                </span>
            </li>
            <?php
            foreach ($menu->getRootItems() as $item) {
                /**
                 * @var $item \vanquyet\menu\MenuItem
                 * @var $children \vanquyet\menu\MenuItem[]
                 * @var $grandchildren \vanquyet\menu\MenuItem[]
                 */
                $children = $item->getChildren();
                ?>
                <li<?= $item->isActive() ? ' class="active"' : '' ?>>
                    <?php
                    if (empty($children)) {
                        echo $item->a();
                    } else {
                        ?>
                        <span class="sub-menu-toggle<?= $item->isActive() ? ' active' : '' ?>" onclick="this.classList.toggle('active')"></span>
                        <?= $item->a() ?>
                        <ul class="sub-menu">
                            <?php
                            foreach ($children as $child) {
                                ?>
                                <li<?= $child->isActive() ? ' class="active"' : '' ?>>
                                    <?php
                                    $grandchildren = $child->getChildren();
                                    if (empty($grandchildren)) {
                                        echo $child->a();
                                    } else {
                                        ?>
                                        <span class="sub-menu-toggle<?= $child->isActive() ? ' active' : '' ?>" onclick="this.classList.toggle('active')"></span>
                                        <?= $child->a() ?>
                                        <ul class="sub-menu">
                                            <?php
                                            foreach ($grandchildren as $grandchild) {
                                                echo ($grandchild->isActive() ? '<li class="active">' : '<li>') . "{$grandchild->a()}</li>";
                                            }
                                            ?>
                                        </ul>
                                        <?php
                                    }
                                    ?>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                        <?php
                    }
                    ?>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>
