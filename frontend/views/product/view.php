<?php
/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\Product
 * @var $modelType string
 * @var $relatedItems \frontend\models\Product[]
 */
use common\models\SiteParam;
use common\models\UrlParam;
use yii\helpers\Html;

$addToBreadcrumb = function (\common\models\ProductCategory $category) use (&$addToBreadcrumb) {
    if ($category->parent) {
        $addToBreadcrumb($category->parent);
    }
    $this->params['breadcrumbs'][] = ['label' => $category->name, 'url' => $category->viewUrl()];
};
if ($model->productCategory) {
    $addToBreadcrumb($model->productCategory);
}
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => $model->viewUrl()];

$order_button = function ($text = null) {
    if ($text === null) {
        $text = 'Add to cart';
    }
    return '<button type="button" class="order-button" onclick="addToCart(this)">' . $text . '</button>';
};

$product_attr = function ($attr = null) use ($model) {
    if ($attr === null || !$model->hasAttribute($attr)) {
        return '';
    }

    return $model->getAttribute($attr);
};

$product_get = function ($val_name = null) use ($model) {
    switch ($val_name) {
        case 'discounted_price': return $model->formatPrice($model->discountedPrice());
        case 'price': return $model->formatPrice($model->price);
        default: return '';
    }
};

$product_options = function () {
    return '<table class="product-options-table"></table><div class="order-error-msg"></div>';
};

$preg_callback = function ($matches) use (
    $product_attr,
    $product_options,
    $product_get,
    $order_button
) {
    $parts = explode('|', $matches[1], 2);

    $parts = array_map(function ($item) { return trim($item); }, $parts);

    if (!isset($parts[1])) {
        $parts[1] = null;
    }

    switch ($parts[0]) {
        case 'product_attr': return $product_attr($parts[1]);
        case 'product_options': return $product_options();
        case 'product_get': return $product_get($parts[1]);
        case 'order_button': return $order_button($parts[1]);
        default: return $matches[0];
    }
};

$preg_open = "{{";
$preg_close = "}}";
$preg_pattern_template = "/open([\\s\\S]*?)close/";
$preg_pattern = str_replace(['open', 'close'], [$preg_open, $preg_close], $preg_pattern_template);

$replaceActionItems = function ($content) use ($preg_pattern, $preg_callback) {
    return preg_replace_callback($preg_pattern, $preg_callback, $content);
};

?>
<div class="container">
    <div class="inner clr">
        <?= $this->render('//layouts/breadcrumb', [
            'links' => $this->params['breadcrumbs'],
        ]) ?>
        <div class="product-view clr" id="product-view" data-sticky-container="product-view" data-sticky-start="10">
            <div class="info">
                <h1 class="name"><?= $model->name ?></h1>
                <div class="meta">
                    <span class="code">CODE: <?= $model->code ?></span>
                    <span class="divider">|</span>
                    <span class="production-status"><?= $model->productionStatusLabel() ?></span>
                </div>
                <div class="price">
                    <?php
                    if ($model->price > 0) {
                        ?>
                        <?php
                        if ($model->discountedPrice() < $model->price) {
                            ?>
                            <span class="price-old">
                            <?= $model->formatPrice($model->price) ?>
                        </span>
                            <?php
                        }
                        ?>
                        <span class="price-sale">
                        <?= $model->formatPrice($model->discountedPrice()) ?>
                    </span>
                        <?php
                    } else {
                        ?>
                        <span class="price-contact">Giá: Liên hệ</span>
                    <?php
                    }
                    ?>
                </div>
                <div class="zoomed">
                    <div class="zoom-container" data-sticky-in="product-view" data-sticky-responsive="tablet|desktop"></div>
                </div>
            </div>
            <div class="images clr" data-sticky-in="product-view" data-sticky-responsive="tablet|desktop">
                <ul class="previews">
                    <?php
                    foreach ($model->productToDetailedImages as $item) {
                        if ($detailedImage = $item->detailedImage) {
                            echo '<li>' . $detailedImage->img() . '</li>';
                        }
                    }
                    ?>
                </ul>
                <div class="full">

                </div>
            </div>
            <div class="info">
                <?php
                if ($model->details !== '') {
                    ?>
                    <div class="details paragraph clr">
                        <?= $replaceActionItems($model->details) ?>
                    </div>
                    <?php
                }
                ?>

                <div class="shares">
                    <?= $this->render('//layouts/shareLinks') ?>
                </div>
            </div>
        </div>

        <?php
        if ($model->long_description !== '') {
            ?>
            <div class="product-desc paragraph clr">
                <?= $replaceActionItems($model->long_description) ?>
            </div>
            <?php
        }
        ?>

        <?= $this->render('//article/_salesPolicies') ?>

        <?php
        if (count($relatedItems) > 0) {
            ?>
            <div class="product-related">
                <h3 class="title">Similar products</h3>
                <div class="body">
                    <div class="clr product-thumbnail-list aspect-ratio <?= Yii::$app->params['product_image_ratio_class'] ?>">
                        <?= $this->render('_thumbnailList', ['models' => $relatedItems]) ?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<script>
    window.addEventListener("load", function () {
        updateProductCounter(<?= $model->id ?>, "view_count", 1);
    });
</script>
<script>
    !function (productView) {
        var previewImgs = productView.querySelectorAll(".images .previews img");
        var imageFullDiv = productView.querySelector(".images .full");
        var zoom = productView.querySelector(".zoom-container");
        if (previewImgs.length > 0) {
            var fullImg = new Image();
            fullImg.addEventListener('load', function () {
                zoom.style.height = Math.min(fullImg.getBoundingClientRect().height, window.innerHeight - 20) + "px";
            });
            fullImg.src = previewImgs[0].src;
            previewImgs[0].parentNode.classList.add("active");
            imageFullDiv.appendChild(fullImg);
            initImagesZoom(fullImg, zoom);
            [].forEach.call(previewImgs, function (previewImg) {
                previewImg.addEventListener("mouseover", function () {
                    [].forEach.call(previewImgs, function (_previewImg) {
                        _previewImg.parentNode.classList.remove("active");
                    });
                    previewImg.parentNode.classList.add("active");
                    fullImg.src = previewImg.src;
                });
            });
        }
    }(document.querySelector("#product-view"));


    function initImagesZoom(img, zoom, lens_bg, data_attr, loading, min_zoom_covered_rate) {
        if (!img || !zoom) {
            return;
        }
        if (!lens_bg) {
            lens_bg = "data:image/gif;base64,R0lGODlhZABkAPABAHOf4fj48yH5BAEAAAEALAAAAABkAGQAAAL+jI+py+0PowOB2oqvznz7Dn5iSI7SiabqWrbj68bwTLL2jUv0Lvf8X8sJhzmg0Yc8mojM5kmZjEKPzqp1MZVqs7Cr98rdisOXr7lJHquz57YwDV8j3XRb/C7v1vcovD8PwicY8VcISDGY2GDIKKf4mNAoKQZZeXg5aQk5yRml+dgZ2vOpKGraQpp4uhqYKsgKi+H6iln7N8sXG4u7p2s7ykvnyxos/DuMWtyGfKq8fAwd5nzGHN067VUtiv2lbV3GDfY9DhQu7p1pXoU+rr5ODk/j7sSePk9Ub33PlN+4jx8v4JJ/RQQa3EDwzcGFiBLi6AfN4UOGCyXegGjIoh0fisQ0rsD4y+NHjgZFqgB5y2Qfks1UPmEZ0OVLlIcKAAA7";
        }
        if (!data_attr) {
            data_attr = {};
        }
        if (!data_attr.src) {
            data_attr.src = "src";
        }
        if (!min_zoom_covered_rate) {
            min_zoom_covered_rate = 1;
        }
        if (!loading) {
            loading = {
                "start": function () {},
                "finish": function () {}
            };
        }

        var loadedImages = [];
        var magnifier = document.createElement("div");
        magnifier.style.display = "none";
        magnifier.style.position = "fixed";
        magnifier.style.pointerEvents = "none";
        magnifier.style.backgroundImage = lens_bg ? "url('" + lens_bg + "')" : "";
        magnifier.style.backgroundRepeat = "repeat";
        document.body.appendChild(magnifier);

            img.onmousemove = function (event) {
                event = window.event || event;
                var self = this;
                var image = zoom.querySelector("img");
                if (!image) {
                    image = new Image();
                    // console.log("loaded="+self.getAttribute(data_attr.loaded));
                    // if (!self.getAttribute(data_attr.loaded)) {
                    if (loadedImages.indexOf(self.getAttribute(data_attr.src)) === -1) {
                        // image.src = self.src;
                        loading.start(zoom);
                        var temp_image = new Image();
                        temp_image.src = self.getAttribute(data_attr.src);
                        temp_image.onload = function () {
                            loading.finish();
                            image.src = temp_image.src;
                            // self.setAttribute(data_attr.loaded, 1);
                            loadedImages.push(self.getAttribute(data_attr.src));
                        };
                        temp_image.onerror = function () {
                            loading.finish();
                        }
                    } else {
                        image.src = self.getAttribute(data_attr.src);
                    }

                    image.style.position = "absolute";
                }
                var rect = this.getBoundingClientRect();
                var x = event.clientX - rect.left;
                var y = event.clientY - rect.top;
                // @TODO: Make zoom area always covered by image
                var min_zoom_covered_wid = min_zoom_covered_rate * zoom.clientWidth;
                var min_zoom_covered_hei = min_zoom_covered_rate * zoom.clientHeight;
                if (x < (min_zoom_covered_wid / 2) * rect.width / image.width) {
                    x = (min_zoom_covered_wid / 2) * rect.width / image.width;
                }
                if (x > (image.width - min_zoom_covered_wid / 2) * rect.width / image.width) {
                    x = (image.width - min_zoom_covered_wid / 2) * rect.width / image.width;
                }
                if (y < (min_zoom_covered_hei / 2) * rect.height / image.height) {
                    y = (min_zoom_covered_hei / 2) * rect.height / image.height;
                }
                if (y > (image.height - min_zoom_covered_hei / 2) * rect.height / image.height) {
                    y = (image.height - min_zoom_covered_hei / 2) * rect.height / image.height;
                }
                // ./
                image.style.left = (zoom.clientWidth / 2) - (x / rect.width) * image.width + "px";
                image.style.top = (zoom.clientHeight / 2) - (y / rect.height) * image.height + "px";
                var mag_wid = zoom.clientWidth * rect.width / image.width;
                var mag_hei = zoom.clientHeight * rect.height / image.height;
                magnifier.style.width = mag_wid + "px";
                magnifier.style.height = mag_hei + "px";
                magnifier.style.left = rect.left + x - mag_wid / 2 + "px";
                magnifier.style.top = rect.top + y - mag_hei / 2 + "px";
                if (mag_wid < image.width) {
                    magnifier.style.display = "block";
                    zoom.appendChild(image);
                }
            };
            img.onmouseout = function () {
                while (zoom.firstChild) {
                    zoom.removeChild(zoom.firstChild);
                }
                magnifier.style.display = "none";
            };
    }
</script>

<script>
    <?php
    $groupOptions = [];
    $groupSelectedOptionIds = [];

    foreach ($model->productAttributes as $attr) {
        $group = $attr->productAttributeGroup;

        if (isset($groupOptions[$group->name])) {
            $groupOptions[$group->name][$attr->id] = $attr->name;
        } else {
            $groupItem = [];
            $groupItem[$attr->id] = $attr->name;
            $groupOptions[$group->name] = $groupItem;
            $groupSelectedOptionIds[$group->name] = null;
        }
    }
    ?>

    var groupOptions = <?= json_encode($groupOptions) ?>;
    var groupSelectedOptionIds = <?= json_encode($groupSelectedOptionIds) ?>;

    updateProductOptionsTableContents();

    function updateProductOptionsTableContents() {
        [].forEach.call(document.querySelectorAll('.product-options-table'), function (table) {
            while (table.firstChild) {
                table.removeChild(table.firstChild);
            }
            var groupName;
            for (groupName in groupOptions) {
                if (groupOptions.hasOwnProperty(groupName)) {
                    var row = elm('tr');
                    table.appendChild(row);

                    var th = elm('th', groupName);
                    row.appendChild(th);

                    var selectBox = elm('select', elm('option'), {
                        name: groupName,
                        onchange: function () {
                            if (this.selectedIndex > -1) {
                                groupSelectedOptionIds[this.name] = this.options[this.selectedIndex].value;
                                updateProductOptionsTableContents();
                            }
                        }
                    });
                    var optionId;
                    for (optionId in groupOptions[groupName]) {
                        if (groupOptions[groupName].hasOwnProperty(optionId)) {
                            var optionTag = elm('option', groupOptions[groupName][optionId], {
                                value: optionId,
                                selected: optionId === groupSelectedOptionIds[groupName]
                            });
                            selectBox.appendChild(optionTag);
                        }
                    }
                    var td = elm('td', selectBox);
                    row.appendChild(td);
                }
            }
        });
    }
</script>

<script>
    var product = {
        id: <?= json_encode($model->id) ?>,
        avatar: <?= json_encode($model->avatarImage ? $model->avatarImage->getImgSrc() : '') ?>,
        url: <?= json_encode($model->viewUrl()) ?>,
        name: <?= json_encode($model->name) ?>,
        code: <?= json_encode($model->code) ?>,
        price: <?= json_encode($model->price) ?>,
        discountedPrice: <?= json_encode($model->discountedPrice()) ?>
    };

    function addToCart(button) {
        var shoppingCartItems = getCacheData('shoppingCartItems', []);

        var errorFields = [];
        var groupName;
        for (groupName in groupSelectedOptionIds) {
            if (groupSelectedOptionIds.hasOwnProperty(groupName)) {
                if (!groupSelectedOptionIds[groupName]) {
                    errorFields.push(groupName);
                }
            }
        }

        var errorEls = document.querySelectorAll('.order-error-msg');

        if (errorFields.length > 0) {
            [].forEach.call(errorEls, function (el) {
                el.innerHTML = 'Please choose: ' + errorFields.join(', ');
                el.classList.remove('hidden');
            });
            return;
        }

        [].forEach.call(errorEls, function (el) {
            el.innerHTML = '';
            el.classList.add('hidden');
        });

        var alreadyAdded = false;
        shoppingCartItems.forEach(function (item) {
            if (item.id === product.id) {
                item.quantity++;
                alreadyAdded = true;
            }
        });

        if (!alreadyAdded) {
            product.quantity = 1;
            shoppingCartItems.push(product);
        }

        shoppingCartItems.forEach(function (item) {
            if (item.id === product.id) {
                var customizing = {};
                for (groupName in groupSelectedOptionIds) {
                    if (groupSelectedOptionIds.hasOwnProperty(groupName)) {
                        var optionId = groupSelectedOptionIds[groupName];
                        customizing[groupName] = groupOptions[groupName][optionId];
                    }
                }
                item.customizing = customizing;
            }
        });

        console.log(getCacheData('shoppingCartItems', []));

        setCacheData('shoppingCartItems', shoppingCartItems);
        refreshCartCounter();
        setCartButtonActivity(true);
        scrollToTop();

    }

</script>
