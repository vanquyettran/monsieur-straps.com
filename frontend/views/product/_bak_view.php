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

// Embedding variables

$order_button = function ($text = null) {
    if ($text === null) {
        $text = 'Order';
    }
    return '<button type="button" class="order-button" onclick="popupOrderForm(this)">' . $text . '</button>';
};

$advisory_button = function ($text = null) {
    if ($text === null) {
        $text = 'Tư vấn miễn phí';
    }
    return '<button type="button" class="advisory-button" onclick="popupAdvisoryRequestForm(this)">' . $text . '</button>';
};

$hotline = '';
$hotline_param = SiteParam::findOneByName(SiteParam::PHONE);
if ($hotline_param !== null) {
    $hotline = $hotline_param->value;
}

$hotline_button = function ($text = null) use ($hotline) {
    if ($text === null) {
        $text = $hotline;
    } else {
        $text = str_replace('*hotline*', $hotline, $text);
    }

    return '<a class="hotline-button" href="tel:' . $hotline . '">' . $text . '</a>';
};

$hotline_text = function ($text = null) use ($hotline) {
    if ($text === null) {
        $text = $hotline;
    } else {
        $text = str_replace('*hotline*', $hotline, $text);
    }

    return '<a class="hotline" href="tel:' . $hotline . '">' . $text . '</a>';
};

$fb_chat_button = function () {};

$zalo_chat_button = function () {};

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
    return '<table class="product-options-table"></table>';
};

$preg_callback = function ($matches) use (
    $product_attr,
    $product_options,
    $product_get,
    $order_button,
    $advisory_button,
    $hotline_button,
    $hotline_text
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
//        case 'advisory_button': return $advisory_button($parts[1]);
        case 'hotline_button': return $hotline_button($parts[1]);
        case 'hotline_text': return $hotline_text($parts[1]);
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
                    <div class="clr product-thumbnail-list aspect-ratio __3x2">
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
    window.placeTree = window.placeTree || [];

    <?php
    $paymentUrl = SiteParam::findOneByName(SiteParam::PAYMENT_LINK);
    ?>
    var paymentUrl = '<?= $paymentUrl ? $paymentUrl->value : '' ?>';
    var orderCodeParam = '<?= UrlParam::ORDER_CODE ?>';

    // TODO: Order Form
    var stage = 1;
    var finalStage = 1;

    var product = {
        id: <?= json_encode($model->id) ?>,
        avatar: <?= json_encode($model->avatarImage ? $model->avatarImage->getImgSrc() : '') ?>,
        name: <?= json_encode($model->name) ?>,
        code: <?= json_encode($model->code) ?>,
        price: <?= json_encode($model->price) ?>,
        discountedPrice: <?= json_encode($model->discountedPrice()) ?>,
        quantity: 1
    };

    var order = {
        deliveryFee: 0,
        totalValue: product.discountedPrice
    };

    var customer = {
        name: '',
        email: '',
        phone: '',
        placeT1: 0,
        placeT2: 0,
        placeT3: 0,
        address: ''
    };

    var totalValueEl = elm('span', formatVnd(order.totalValue), {
        updateContent: function () {
            this.textContent = formatVnd(order.totalValue);
        }
    });

    var productInputs = {
        quantity: elm('select', ([0, 1, 2, 3, 4, 5, 6, 7, 8, 9]).map(function (item, index) {
            return elm('option', index + 1);
        }), {
            onchange: function () {
                product.quantity = +this.value;
                order.totalValue = product.discountedPrice * product.quantity;
                totalValueEl.updateContent();
//                updateDeliveryFeeAsync();
                updateDeliveryFee();
            }
        })
    };

    var getFullAddress = function () {
        return [customer.address].concat(
            [findPlaceT3(customer.placeT3, customer.placeT2, customer.placeT1), findPlaceT2(customer.placeT2, customer.placeT1), findPlaceT1(customer.placeT1)]
                .filter(function (place) {
                    return place !== undefined;
                })
                .map(function (place) {
                    return place[1];
                })
        ).filter(function (item) {
            return !!item;
        }).join(', ');
    };

    var fullAddressEl = elm('span', null, {
        updateContent: function () {
            this.textContent = getFullAddress();
        },
        'class': 'full-address'
    });

    var selectPrompt = elm('option', '-- Chọn --', {value: ''});

    var customerInputs = {
        name: elm('input', null, {
            type: 'text',
            maxlength: 255,
            onchange: function () {
                customer.name = this.value;
            }
        }),
        email: elm('input', null, {
            type: 'email',
            placeholder: '(nếu có)',
            onchange: function () {
                customer.email = this.value;
            }
        }),
        phone: elm('input', null, {
            type: 'tel',
            onchange: function () {
                customer.phone = this.value;
            }
        }),
        placeT1: elm('select', []),
        placeT2: elm('select', []),
        placeT3: elm('select', []),
        address: elm('input', null, {
            type: 'text',
            maxlength: 255,
            oninput: function () {
                customer.address = this.value;
                fullAddressEl.updateContent();
            }
        })
    };

    var findPlaceT1 = function (id) {
        return window.placeTree.filter(function (placeT1) {
            return placeT1[0] === id;
        })[0];
    };

    var findPlaceT2 = function (id, placeT1Id) {
        var placeT1 = findPlaceT1(placeT1Id);

        if (placeT1) {
            return placeT1[2].filter(function (placeT2) {
                return placeT2[0] === id;
            })[0];
        }

        return undefined;
    };

    var findPlaceT3 = function (id, placeT2Id, placeT1Id) {
        var placeT2 = findPlaceT2(placeT2Id, placeT1Id);

        if (placeT2) {
            return placeT2[2].filter(function (placeT3) {
                return placeT3[0] === id;
            })[0];
        }

        return undefined;
    };

    var getPlaceT1Options = function (selectedValue) {
        return [selectPrompt.cloneNode(true)].concat(window.placeTree.map(function (placeT1) {
            return elm('option', placeT1[1], {
                value: placeT1[0],
                selected: selectedValue === placeT1[0]
            });
        }));
    };

    var getPlaceT2Options = function (selectedValue) {
        var placeT1 = findPlaceT1(customer.placeT1);

        if (placeT1) {
            return [selectPrompt.cloneNode(true)].concat(placeT1[2].map(function (placeT2) {
                return elm('option', placeT2[1], {
                    value: placeT2[0],
                    selected: selectedValue === placeT2[0]
                });
            }));
        }

        return [];
    };

    var getPlaceT3Options = function (selectedValue) {
        var placeT2 = findPlaceT2(customer.placeT2, customer.placeT1);

        if (placeT2) {
            return [selectPrompt.cloneNode(true)].concat(placeT2[2].map(function (placeT3) {
                return elm('option', placeT3[1], {
                    value: placeT3[0],
                    selected: selectedValue === placeT3[0]
                });
            }));
        }

        return [];
    };

    var updateOptionsForPlaceT1T2Inputs = function () {
        empty(customerInputs.placeT2);
        var placeT2Options = getPlaceT2Options(customer.placeT2);
        if (placeT2Options.length > 0) {
            appendChildren(customerInputs.placeT2, placeT2Options);
            customerInputs.placeT2.disabled = false;
        } else {
            customerInputs.placeT2.disabled = true;
        }

        empty(customerInputs.placeT3);
        var placeT3Options = getPlaceT3Options(customer.placeT3);
        if (placeT3Options.length > 0) {
            appendChildren(customerInputs.placeT3, placeT3Options);
            customerInputs.placeT3.disabled = false;
        } else {
            customerInputs.placeT3.disabled = true;
        }
    };

    updateOptionsForPlaceT1T2Inputs();

    var deliveryFeeEl = elm('span', null, {
        style: style({
            minWidth: '2em',
            minHeight: '1em',
            display: 'inline-block'
        }),
        'class': 'delivery-fee'
    });

    var updateDeliveryFee = function () {
        if (order.totalValue < 1000000) {
            order.deliveryFee = 20000;
            deliveryFeeEl.textContent = formatVnd(order.deliveryFee);
            deliveryFeeEl.classList.remove('delivery-free');
        } else {
            order.deliveryFee = 0;
            deliveryFeeEl.textContent = 'Miễn phí';
            deliveryFeeEl.classList.add('delivery-free');
        }
    };

    updateDeliveryFee();

    var updateDeliveryFeeAsync = function () {
        var placeT1 = findPlaceT1(customer.placeT1);
        var placeT2 = findPlaceT2(customer.placeT2, customer.placeT1);

        order.deliveryFee = 0;
        deliveryFeeEl.textContent = '';

        if (placeT1 && placeT2) {
            deliveryFeeEl.classList.add('loading');
            requestDeliveryFee({
                placeT1: placeT1[1],
                placeT2: placeT2[1],
                weight: 200 + product.quantity * 200,
                value: order.totalValue
            }, function (fee) {
                order.deliveryFee = fee;
                deliveryFeeEl.textContent = formatVnd(fee);
                deliveryFeeEl.classList.remove('loading');
            }, function () {
                deliveryFeeEl.classList.remove('loading');
            });
        } else {
            deliveryFeeEl.classList.remove('loading');
        }
    };

    [customerInputs.placeT1, customerInputs.placeT2, customerInputs.placeT3].forEach(function (input) {
        input.addEventListener('change', function () {
            customer.placeT1 = +customerInputs.placeT1.value || 0;
            customer.placeT2 = +customerInputs.placeT2.value || 0;
            customer.placeT3 = +customerInputs.placeT3.value || 0;

            updateOptionsForPlaceT1T2Inputs();
            fullAddressEl.updateContent();

            if (input === customerInputs.placeT1 || input === customerInputs.placeT2) {
                updateDeliveryFeeAsync();
            }
        });
    });

    var validateStage = function () {
        var result = {ok: true, messages: []};
        switch (stage) {
            case 1:
                if (!customer.name) {
                    result.messages.push('Vui lòng cho biết họ tên người nhận hàng.');
                    result.ok = false;
                }
                if (!customer.phone) {
                    result.messages.push('Vui lòng cung cấp số điện thoại để chúng tôi có thể liên lạc nhanh chóng.');
                    result.ok = false;
                } else if (!getIsValidPhone(customer.phone)) {
                    result.messages.push('Số điện thoại không hợp lệ.');
                    result.ok = false;
                }
                if (customer.email && !checkEmail(customer.email)) {
                    result.messages.push('Email không đúng định dạng.');
                    result.ok = false;
                }
                if (!customer.address) {
                    result.messages.push('Địa chỉ không được để trống.');
                    result.ok = false;
                }
                break;

            case 2:
                if (!findPlaceT1(customer.placeT1)) {
                    result.messages.push('Tỉnh/Thành phố không hợp lệ.');
                    result.ok = false;
                }
                if (!findPlaceT2(customer.placeT2, customer.placeT1)) {
                    result.messages.push('Quận/Huyện không hợp lệ.');
                    result.ok = false;
                }
                if (!findPlaceT3(customer.placeT3, customer.placeT2, customer.placeT1)) {
                    result.messages.push('Phường/Xã không hợp lệ.');
                    result.ok = false;
                }
                if (!customer.address) {
                    result.messages.push('Địa chỉ chi tiết không được để trống.');
                    result.ok = false;
                }
                break;

            case 3:
                if (!customer.name) {
                    result.messages.push('Vui lòng cho biết họ tên người nhận hàng.');
                    result.ok = false;
                }
                if (!customer.phone) {
                    result.messages.push('Vui lòng cung cấp số điện thoại để chúng tôi có thể liên lạc nhanh chóng.');
                    result.ok = false;
                } else if (!getIsValidPhone(customer.phone)) {
                    result.messages.push('Số điện thoại không hợp lệ.');
                    result.ok = false;
                }
                if (customer.email && !checkEmail(customer.email)) {
                    result.messages.push('Email không đúng định dạng.');
                    result.ok = false;
                }
                break;
        }

        validateStageResults[stage] = result;

        return result;
    };

    var validateStageResults = {};

    var form = elm('form', null, {
        'class': 'popup-order-form scroll-styled',
        onsubmit: function (event) {
            event.preventDefault();
        }
    });

    var formSubmitFeedback;

    var renderButtonGroup = function () {
        return elm('div', [
            stage > 1 && elm('button', 'Quay lại', {
                type: 'button',
                onclick: function () {
                    stage--;
                    renderFormContent();
                },
                'class': 'negative'
            }),
            stage < finalStage && elm(
                'button',
                [
                    elm('span', stage === finalStage - 1 ? 'Bước cuối cùng' : 'Bước tiếp theo')//,
                    //elm('i', null, {'class': 'icon play-circle-icon'})
                ],
                {
                    type: 'button',
                    onclick: function () {
                        if (validateStage().ok) {
                            stage++;
                        }
                        renderFormContent();
                    },
                    'class': 'positive'
                }
            ),
            stage === finalStage && elm('button', 'Gửi đơn hàng', {
                type: 'button',
                onclick: submitForm,
                'class': 'positive'
            })
        ], {
            'class': 'button-group popup-buttons'
        });
    };

    var renderFormContent = function () {
        form.setAttribute('data-stage', stage);
        empty(form);

        appendChildren(form, elm('div', [
            elm('div', {
                html: <?= json_encode($this->render('//layouts/svgLogo')) ?>
            }, {
                'class': 'heading'
            }),

            elm('div', [
                stage === 0 && formSubmitFeedback,

                stage === 1 && elm('table', [
                    elm('tr', [
                        elm('th', 'Sản phẩm'),
                        elm('td', product.name, {'class': 'product-name'})
                    ]),
                    elm('tr', [
                        elm('th', 'Số lượng'),
                        elm('td', productInputs.quantity)
                    ]),
                    elm('tr', [
                        elm('th', 'Thành tiền'),
                        elm('td', totalValueEl, {'class': 'total-amount'})
                    ]),
                    elm('tr', [
                        elm('th', 'Vận chuyển'),
                        elm('td', deliveryFeeEl)
                    ]),
                    elm('tr', [
                        elm('th', 'Người nhận'),
                        elm('td', customerInputs.name)
                    ]),
                    elm('tr', [
                        elm('th', 'Điện thoại'),
                        elm('td', customerInputs.phone)
                    ]),
                    elm('tr', [
                        elm('th', 'Địa chỉ'),
                        elm('td', customerInputs.address)
                    ]),
                    stage > 0 && elm('tr', [
                        elm('th', ''),
                        elm('td', renderButtonGroup())
                    ])
                ]),

                stage === 2 && elm('table', [
                    elm('caption', [
                        elm('div', 'Địa chỉ nhận hàng:', {
                            'class': 'title'
                        }),
                        fullAddressEl
                    ]),
                    elm('tr', [
                        elm('th', 'Tỉnh/Thành phố'),
                        elm('td', customerInputs.placeT1)
                    ]),
                    elm('tr', [
                        elm('th', 'Quận/Huyện'),
                        elm('td', customerInputs.placeT2)
                    ]),
                    elm('tr', [
                        elm('th', 'Phường/Xã'),
                        elm('td', customerInputs.placeT3)
                    ]),
                    elm('tr', [
                        elm('th', 'Địa chỉ chi tiết'),
                        elm('td', customerInputs.address)
                    ])
                ]),

                stage === 3 && elm('table', [
                    elm('tr', [
                        elm('th', 'Số lượng'),
                        elm('td', product.quantity)
                    ]),
                    elm('tr', [
                        elm('th', 'Thành tiền'),
                        elm('td', totalValueEl, {'class': 'amount'})
                    ]),
                    elm('tr', [
                        elm('th', 'Vận chuyển'),
                        elm('td', deliveryFeeEl, {'class': 'amount'})
                    ]),
                    elm('tr', [
                        elm('th', 'Địa chỉ'),
                        elm('td', getFullAddress())
                    ]),
                    elm('tr', [
                        elm('th', 'Người nhận'),
                        elm('td', customerInputs.name)
                    ]),
                    elm('tr', [
                        elm('th', 'Điện thoại'),
                        elm('td', customerInputs.phone)
                    ]),
                    elm('tr', [
                        elm('th', 'Email'),
                        elm('td', customerInputs.email)
                    ])
                ]),

                validateStageResults[stage]
                && validateStageResults[stage].messages.length > 0
                && elm('ul', validateStageResults[stage].messages.map(function (message) {
                    return elm('li', message);
                }), {
                    'class': validateStageResults[stage].ok ? 'alert alert-info' : 'alert alert-error'
                })
//                stage > 0 && elm('div', [
//                    stage > 1 && elm('button', 'Quay lại', {
//                        type: 'button',
//                        onclick: function () {
//                            stage--;
//                            renderFormContent();
//                        },
//                        'class': 'negative'
//                    }),
//                    stage < finalStage && elm(
//                        'button',
//                        [
//                            elm('span', stage === finalStage - 1 ? 'Bước cuối cùng' : 'Bước tiếp theo')//,
//                            //elm('i', null, {'class': 'icon play-circle-icon'})
//                        ],
//                        {
//                            type: 'button',
//                            onclick: function () {
//                                if (validateStage().ok) {
//                                    stage++;
//                                }
//                                renderFormContent();
//                            },
//                            'class': 'positive'
//                        }
//                    ),
//                    stage === finalStage && elm('button', 'Gửi đơn hàng', {
//                        type: 'button',
//                        onclick: submitForm,
//                        'class': 'positive'
//                    })
//                ], {
//                    'class': 'button-group popup-buttons'
//                })
            ], {
                'class': 'body'
            })
        ], {
            'class': 'scrollable'
        }));
    };

    var orderIdToCode = function (orderId) {
        if (orderId < 10) {
            return '00' + orderId;
        }
        if (orderId < 100) {
            return '0' + orderId;
        }
        return orderId.toString();
    };


    var submitForm = function () {
        if (validateStage().ok) {
            form.classList.add('loading-opacity');
            submitOrderForm([product], order, customer, function (productOrder) {
                // success
                stage = 0;
                var orderCode = orderIdToCode(productOrder.id);
                var paymentButton = paymentUrl
                    ? elm('a', 'Thực hiện thanh toán', {
                    href: paymentUrl + '?' + orderCodeParam + '=' + orderCode,
                    target: '_blank',
                    'class': 'payment-button'})
                    : null;
                formSubmitFeedback = elm('div', [
                    elm('div', 'Đặt hàng thành công!', {'class': 'title'}),
                    elm('div', [
                        elm('p', ['Mã đơn hàng: ', elm('b', orderCode)]),
                        elm('p', ['Số điện thoại: ', elm('b', productOrder.customer_phone)]),
                        elm('p', 'Cảm ơn bạn đã đặt hàng! Chúc bạn một ngày tốt lành.')
                    ], {'class': 'message'}),
                    paymentButton
                ], {'class': 'feedback positive'});
                form.classList.remove('loading-opacity');
                renderFormContent();

            }, function () {
                // error
                stage = 0;
                formSubmitFeedback = elm('div', [
                    elm('div', 'Oops... Có gì đó không đúng đã xảy ra!', {'class': 'title'}),
                    elm('div', [
                        elm('p', 'Bạn vui lòng liên hệ qua số điện thoại trên trang web để được trợ giúp.'),
                        elm('p', 'Chúng tôi rất xin lỗi vì sự bất tiện này.')
                    ], {'class': 'message'}),
                    elm('div', [
                        elm('button', 'Xem lại thông tin', {
                            type: 'button',
                            onclick: function () {
                                stage = finalStage;
                                renderFormContent();
                            },
                            'class': 'negative'
                        }),
                        elm('button', 'Gửi lại', {
                            type: 'button',
                            onclick: submitForm,
                            'class': 'positive'
                        })
                    ], {
                        'class': 'button-group popup-buttons'
                    })
                ], {'class': 'feedback negative'});
                form.classList.remove('loading-opacity');
                renderFormContent();
            });
            console.log('--- Submited data --- ');
            console.log('product', product);
            console.log('order', order);
            console.log('customer', customer);
            console.log('--- / --- ');
        }
        renderFormContent();
    };

    renderFormContent();

    /**
     *
     * @param {HTMLButtonElement} button
     */
    var orderDialog;
    function popupOrderForm(button) {
        if (orderDialog) {
            orderDialog.open();
        } else {
            appendChildren(customerInputs.placeT1, getPlaceT1Options());
            orderDialog = popup(form, {
                className: 'product-order-popup'
            });
        }

//        if (orderDialog) {
//            orderDialog.open();
//        } else if (window.placeTree.length > 0) {
//            appendChildren(customerInputs.placeT1, getPlaceT1Options());
//            orderDialog = popup(form);
//        } else {
//            if (button) {
//                button.disabled = true;
//                button.classList.add('loading-opacity');
//            }
//            var inter = setInterval(function () {
//                if (window.placeTree.length > 0) {
//                    clearInterval(inter);
//                    if (button) {
//                        button.disabled = false;
//                        button.classList.remove('loading-opacity');
//                    }
//                    appendChildren(customerInputs.placeT1, getPlaceT1Options());
//                    orderDialog = popup(form);
//                }
//            }, 20);
//        }
    }

    // TODO: Validators
    function checkEmail(value) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(value).toLowerCase());
    }

    function getIsValidPhone(value) {
        var flag = false;
        var phone = String(value);
        // phone = phone.replace('(+84)', '0');
        // phone = phone.replace('+84', '0');
        // phone = phone.replace('0084', '0');
        phone = phone.replace(/ /g, '');
        if (phone !== '') {
            var firstNumber = phone.substring(0, 2);

            if ((firstNumber === '09' || firstNumber === '08' || firstNumber === '03') && phone.length === 10) {
                if (phone.match(/^\d{10}/)) {
                    flag = true;
                }
            }

            else if (firstNumber === '01' && phone.length === 11) {
                if (phone.match(/^\d{11}/)) {
                    flag = true;
                }
            }
        }
        return flag;
    }

    function requestDeliveryFee(data, onSuccess, onError) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?= \yii\helpers\Url::to(['ghtk-api/get-fee'], true) ?>', true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            var res = JSON.parse(xhr.responseText);
            console.log('getFee res', res);
            if (res.success) {
                onSuccess(+res.fee.fee);
            } else {
                onError();
            }
        };
        xhr.onerror = function () {
            console.log(xhr.response);
            onError();
        };
        xhr.send('province=' + data.placeT1 + '&district=' + data.placeT2
            + '&weight=' + data.weight + '&value=' + data.value
            + '&<?= Yii::$app->request->csrfParam ?>=<?= Yii::$app->request->csrfToken ?>');
    }

    function submitOrderForm(products, order, customer, onSuccess, onError) {
        var fd = new FormData();

        fd.append('products', JSON.stringify(products));
        fd.append('order', JSON.stringify(order));
        fd.append('customer', JSON.stringify(customer));
        fd.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->csrfToken ?>');

        var xhr = new XMLHttpRequest();

        xhr.open('POST', '<?= \yii\helpers\Url::to(['product-api/save-product-order']) ?>', true);
        xhr.onload = function () {
            var res = JSON.parse(xhr.responseText);
            if (res.ProductOrder && res.ProductOrder.id) {
                onSuccess(res.ProductOrder);
            } else {
                onError(res.errors);
            }
        };
        xhr.onerror = function () {
            onError({status: xhr.status, statusText: xhr.statusText});
        };
        xhr.send(fd);

    }
</script>

<script>
    var advisoryFormSent = false;
    var advisoryFormSentOk = false;

    var advisoryRequestInputs = {
        customerName: elm('input', null, {
            type: 'text',
            placeholder: 'Họ tên'
        }),
        customerPhone: elm('input', null, {
            type: 'text',
            placeholder: 'Số điện thoại'
        })
    };

    var advisoryRequestErrors = [];

    function popupAdvisoryRequestForm() {
        var form = elm('form', null, {
            'class': 'popup-advisory-form',
            onsubmit: function (event) {
                event.preventDefault();

                var cusName = advisoryRequestInputs.customerName.value.trim();
                var cusPhone = advisoryRequestInputs.customerPhone.value.trim();

                advisoryRequestErrors = [];

                if (cusName === '') {
                    advisoryRequestErrors.push('Vui lòng cho biết họ tên của bạn');
                }

                if (cusPhone === '') {
                    advisoryRequestErrors.push('Vui lòng cung cấp số điện thoại để chúng tôi liên hệ lại');
                } else if (!getIsValidPhone(cusPhone)) {
                    advisoryRequestErrors.push('Số điện thoại không hợp lệ');
                }

                if (advisoryRequestErrors.length === 0) {
                    // Submit
                    form.classList.add('loading-opacity');

                    var fd = new FormData();

                    fd.append('customer_name', advisoryRequestInputs.customerName.value.trim());
                    fd.append('customer_phone', advisoryRequestInputs.customerPhone.value.trim());
                    fd.append('product_id', <?= json_encode($model->id) ?>);
                    fd.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->csrfToken ?>');

                    var xhr = new XMLHttpRequest();

                    xhr.open('POST', '<?= \yii\helpers\Url::to(['product-api/save-product-contact']) ?>', true);
                    xhr.onload = function () {
                        var res = JSON.parse(xhr.responseText);
                        if (res === 'success') {
                            advisoryFormSentOk = true;
                        } else {
                            advisoryFormSentOk = false;
                        }
                        form.classList.remove('loading-opacity');
                        advisoryFormSent = true;

                        renderAdvisoryFormContent();
                    };
                    xhr.onerror = function () {
                        form.classList.remove('loading-opacity');
                        advisoryFormSent = true;
                        advisoryFormSentOk = false;

                        renderAdvisoryFormContent();
                    };
                    xhr.send(fd);
                }

                renderAdvisoryFormContent();
            }
        });

        var renderAdvisoryFormContent = function () {
            empty(form);
            appendChildren(form, advisoryFormSent ? [
                advisoryFormSentOk
                    ? elm('div', 'Gửi yêu cầu thành công.', {'class': 'feedback positive'})
                    : elm('div', [
                    elm('div', 'Đã có lỗi xảy ra!', {'class': 'title'}),
                    elm('div', [
                        elm('p', 'Vui lòng kiểm tra kết nối mạng và thử lại.'),
                        elm('p', 'Hoặc liên hệ trực tiếp với chúng tôi qua số điện thoại trên website.'),
                    ], {'class': 'message'}),
                    elm('div', [
                        elm('button', 'Xem lại thông tin', {
                            type: 'button',
                            onclick: function () {
                                advisoryFormSent = false;
                                renderAdvisoryFormContent();
                            },
                            'class': 'negative'
                        }),
                        elm('button', 'Gửi lại', {
                            type: 'submit',
                            'class': 'positive'
                        })
                    ], {
                        'class': 'button-group popup-buttons'
                    })
                ], {'class': 'feedback negative'})
            ] : [
                elm('div', [
                    advisoryRequestInputs.customerName,
                    advisoryRequestInputs.customerPhone
                ]),
                advisoryRequestErrors.length > 0 && elm('ul', advisoryRequestErrors.map(function (error) {
                    return elm('li', error);
                }), {
                    'class': 'alert alert-error'
                }),
                elm('div', [
                    elm('button', 'Gọi lại cho tôi', {
                        type: 'submit',
                        'class': 'positive'
                    })
                ], {
                    'class': 'button-group popup-buttons'
                })
            ]);
        };

        renderAdvisoryFormContent();

        popup(form, {
            className: 'product-advisory-popup'
        });
    }
</script>