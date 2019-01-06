<?php
/**
 * Created by PhpStorm.
 * User: VanQuyet
 * Date: 11/19/2018
 * Time: 3:37 PM
 */

 foreach (explode("\n", $src_mixed) as $font_src) {
                 if (trim($font_src) === '') continue;
                     ?>

             @font-face {
                 font-family: <?= $family ?>;
                 src: <?= trim($font_src) ?>;
             }

             <?php
         }