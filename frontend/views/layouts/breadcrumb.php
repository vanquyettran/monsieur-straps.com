<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/11/2018
 * Time: 5:35 PM
 */

/**
 * @var $links array
 */
?>
<ol class="breadcrumb">
    <?php
    foreach ($links as $item) {
        ?><li>
            <a href="<?= $item['url'] ?>"
               title="<?= $item['label'] ?>"
            ><?= $item['label'] ?></a>
        </li><?php
    }
    ?>
</ol>
