<?php
/**
 * @var $menu vanquyet\menu\Menu
 */

if (!empty($menu->getRootItems())) {
    ?>
    <nav class="nav-bar">
        <div class="menu clr">
            <button type="button" class="menu-toggle" onclick="this.classList.toggle('active')">
                <i class="icon menu-icon"></i>
                <span>Danh mục</span>
            </button>
            <ul class="clr">
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
                            <button type="button" class="menu-toggle<?= $item->isActive() ? ' active' : '' ?>" onclick="this.classList.toggle('active')"></button>
                            <?= $item->a() ?>
                            <ul>
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
                                            <button class="menu-toggle" onclick="this.classList.toggle('active')"></button>
                                            <?= $child->a() ?>
                                            <ul>
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
            <button type="button" class="search-toggle" onclick="toggleSearchToolbar()">
                <i class="icon magnifier-icon"></i>
            </button>
        </div>
    </nav>
    <?php
}
?>

