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
