<?php
/**
 * @var $this \yii\web\View
 * @var $category ProductCategory
 * @var $products \frontend\models\Product[]
 * @var $attributeGroups \common\models\ProductAttributeGroup[]
 * @var $groupedAttributes \common\models\ProductAttribute[][]
 * @var $sort string
 * @var $jsonParams string
 * @var $hasMore boolean
 * @var $page integer
 */
use common\models\ProductAttributeGroup;
use frontend\models\ProductCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>
<?php
if (count($category->bannerImages) > 0) {

    $bannerId = "Banner_ProductCategory{$category->id}";

    if ($category->banner_font_src) {
        ?>
        <style>
            <?= $this->render('//layouts/_fontFace', ['src_mixed' => $category->banner_font_src, 'family' => $bannerId]) ?>
            <?= "#$bannerId" ?> .caption-content {
                font-family: <?= $bannerId ?>, Arial, Sans-Serif;
            }
        </style>
        <?php
    }

    if ($category->banner_filter) {
        ?>
        <style>
            <?= "#$bannerId" ?> .caption-overlay {
                background: <?= $category->banner_filter ?>;
            }
        </style>
        <?php
    }

    ?>
    <div class="product-category-banner" id="<?= $bannerId ?>">
        <div class="frame aspect-ratio __4x1 __sm-16x9">
            <div class="slider"
                 data-autorun-delay="3000"
                 data-slide-time="500"
                 data-slide-timing="ease"
                 data-swipe-timing="ease-out"
                 data-display-navigator="false"
            >
                <?php
                foreach ($category->bannerImages as $image) {
                    ?>
                    <div class="image aspect-ratio __4x1 __sm-16x9">
                        <span>
                            <?= $image->img() ?>
                        </span>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <?php
        if ($category->banner_caption) {
            ?>
            <div class="caption-overlay">
                <div class="caption-content">
                    <?= $category->banner_caption ?>
                </div>
            </div>
            <?php
        }
        ?>

    </div>
    <?php
}
?>
<div class="container">
    <div class="inner clr">
        <div class="product-category clr">
            <div class="filter-col">
                <form action="" method="get" class="filter-form" id="filter-form">
                    <div class="groups">
                        <div class="group">
                            <h4 class="name"><?= Yii::t('app', 'Sort By') ?></h4>
                            <div class="options">
                                <label>
                                    <input type="radio"
                                           name="sort"
                                           value="recently"
                                           <?= 'recently' == $sort ? 'checked' : '' ?>
                                    >
                                    <b></b>
                                    <span><?= Yii::t('app', 'Recently') ?></span>
                                </label>
                                <label>
                                    <input type="radio"
                                           name="sort"
                                           value="price_up"
                                           <?= 'price_up' == $sort ? 'checked' : '' ?>
                                    >
                                    <b></b>
                                    <span><?= Yii::t('app', 'Price Ascending') ?></span>
                                </label>
                                <label>
                                    <input type="radio"
                                           name="sort"
                                           value="price_down"
                                           <?= 'price_down' == $sort ? 'checked' : '' ?>
                                    >
                                    <b></b>
                                    <span><?= Yii::t('app', 'Price Descending') ?></span>
                                </label>
                            </div>
                        </div>

                        <?php
                        foreach ($attributeGroups as $attrGroup) {
                            /**
                             * @var $attrGroup ProductAttributeGroup
                             */
                            $inputName = 'group_' . $attrGroup->id;
                            $checkedValues = $groupedAttributes[$attrGroup->id];
                            ?>
                            <div class="group">
                                <h4 class="name"><?= $attrGroup->name ?></h4>
                                <div class="options">
                                    <?php
                                    foreach ($attrGroup->productAttributes as $attr) {
                                        $value = $attr->id;
                                        ?>
                                        <label>
                                            <input type="checkbox"
                                                   name="<?= $inputName ?>[]"
                                                   value="<?= $value ?>"
                                                   <?= in_array($value, $checkedValues) ? 'checked' : '' ?>
                                            >
                                            <b></b>
                                            <span><?= $attr->name ?></span>
                                        </label>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </form>
            </div>
            <div class="products-col">
                <div class="clr product-thumbnail-list aspect-ratio <?= Yii::$app->params['product_image_ratio_class'] ?>">
                    <?php
                    if (count($products) > 0) {
                        echo $this->render('_thumbnailList', ['models' => $products]);
                    } else {
                        echo 'No content yet.';
                    }
                    ?>
                </div>
                <?php
                if ($hasMore) {
                    ?>
                    <button
                        type="button"
                        class="see-more"
                        onclick="loadMore(this.previousElementSibling, this)"
                    ><?= Yii::t('app', 'See more') ?></button>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>


<script>
    var jsonParams = <?= $jsonParams ?>;
    var page = <?= $page + 1 ?>;
    function loadMore(container, button) {
        loadMoreProducts(container, button, '_thumbnailList', jsonParams, page, function () {
            page++;
        });
    }
</script>


<script>
    function isSmallScreen() {
        return window.innerWidth <= 640;
    }

    !function (form) {
        var groupsWrapper = form.querySelector('.groups');
        var groupEls = [].slice.call(groupsWrapper.children);

        groupEls.forEach(function (group) {
            var nameEl = group.querySelector(".name");
            var inputs = [].slice.call(group.querySelectorAll("input"));
            nameEl.onclick = function () {
                groupEls.forEach(function (_group) {
                    if (_group === group) {
                        if (group.classList.contains('active')) {
                            group.classList.remove('active');
//                            if (_group.getAttribute('data-value') !== _group.getAttribute('data-old-value')) {
//                                isSmallScreen() && form.submit();
//                            }
                           _group.setAttribute('data-value', _group._getGroupValueJson());
                           _group.setAttribute('data-old-value', _group._getGroupValueJson());
                        } else {
                            group.classList.add('active');
                           _group.setAttribute('data-value', _group._getGroupValueJson());
                           _group.setAttribute('data-old-value', _group._getGroupValueJson());
                        }
                    } else {
                        _group.classList.remove('active');
                    }
                });
            };
            nameEl._textContent = nameEl.textContent;

            group._getGroupValue = function () {
                return inputs.filter(function (input) {
                    return input.checked;
                }).map(function (input) {
                    return input.value;
                });
            };

            group._getGroupValueJson = function () {
                return JSON.stringify(group._getGroupValue());
            };
            group.setAttribute('data-value', group._getGroupValueJson());
            group.setAttribute('data-old-value', group._getGroupValueJson());

            var setGroupNameText = function () {
//                var valueText = inputs.filter(function (input) {
//                    return input.checked;
//                }).map(function (input) {
//                    return input.parentNode.querySelector("span").textContent;
//                }).join(", ");
//                nameEl.textContent = nameEl._textContent + (valueText && ": " + valueText);
            };

            if (isSmallScreen()) {
                setGroupNameText();
            }

            inputs.forEach(function (input) {
                input.addEventListener("change", function () {
                    group.setAttribute('data-value', group._getGroupValueJson());
                    if (isSmallScreen()) {
                        setGroupNameText();
                        form.submit();
                    } else {
                        form.submit();
                    }
                });
            });
        });

        var reorder = function () {
            groupEls.sort(function (a, b) {
                var x = a.querySelector(":checked") ? 1 : 0;
                var y = b.querySelector(":checked") ? 1 : 0;
                return y - x;
            });

            while (groupsWrapper.firstChild) {
                groupsWrapper.removeChild(groupsWrapper.firstChild);
            }

            groupEls.forEach(function (group) {
                groupsWrapper.appendChild(group);
            });
        };

        reorder();

    }(document.getElementById("filter-form"));
</script>