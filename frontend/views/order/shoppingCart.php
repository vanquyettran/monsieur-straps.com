<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 3/12/2019
 * Time: 7:34 AM
 */
?>
<div class="container">
    <div class="inner clr">
        <div id="shopping-cart-view" class="shopping-cart-view">
            <div class="title"></div>
            <table>
                <!-- filled by JS -->
            </table>
        </div>
        <div class="contact-view">
            <div class="title">Contact</div>
            <form>
                <div class="field-group">
                    <div class="field-name">Full Name</div>
                    <div class="field-input">
                        <input type="text" name="name" placeholder="">
                    </div>
                </div>
                <div class="field-group">
                    <div class="field-name">Address Line 1</div>
                    <div class="field-input">
                        <input type="text" name="address" placeholder="">
                        <div>Street address, P.O. Box, company name, c/o</div>
                    </div>
                </div>
                <div class="field-group">
                    <div class="field-name">Address Line 2</div>
                    <div class="field-input">
                        <input type="text" name="address_2" placeholder="">
                        <div>Apartment, suite, unit, building, floor, etc.</div>
                    </div>
                </div>
                <div class="field-group">
                    <div class="field-name">City</div>
                    <div class="field-input">
                        <input type="text" name="city" placeholder="">
                    </div>
                </div>
                <div class="field-group">
                    <div class="field-name">State/Province/Region</div>
                    <div class="field-input">
                        <input type="text" name="province" placeholder="">
                    </div>
                </div>
                <div class="field-group">
                    <div class="field-name">ZIP/Postal Code</div>
                    <div class="field-input">
                        <input type="text" name="postal_code" placeholder="">
                    </div>
                </div>
                <div class="field-group">
                    <div class="field-name">Country</div>
                    <div class="field-input">
                        <select name="country">
                            <option>Vietnam</option>
                            <option>USA</option>
                            <option>Korean</option>
                        </select>
                    </div>
                </div>
                <div class="field-group">
                    <div class="field-name">Phone</div>
                    <div class="field-input">
                        <input type="text" name="phone" placeholder="">
                    </div>
                </div>
                <div class="field-group">
                    <div class="field-name">Email</div>
                    <div class="field-input">
                        <input type="text" name="email" placeholder="">
                    </div>
                </div>
                <div class="field-group">
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function fillUpShoppingCartView() {
        var shoppingCartView = document.querySelector('#shopping-cart-view');
        var titleView = shoppingCartView.querySelector('.title');
        var table = shoppingCartView.querySelector('table');
        var shoppingCartItems = getCacheData('shoppingCartItems', []);

        if (shoppingCartItems.length === 0) {
            titleView.innerHTML = 'Your shopping cart currently is empty.';
            return;
        }

        titleView.innerHTML = 'Shopping cart';

        var tableHead = elm('thead', elm('tr', [
            elm('td', 'ID'),
            elm('td', 'Photo'),
            elm('td', 'Name'),
            elm('td', 'Code'),
            elm('td', 'Customizing'),
            elm('td', 'Sale Price'),
            elm('td', 'Quantity')
        ]));
        var tableBody = elm('tbody', shoppingCartItems.map(function (item) {

            var options = [];
            var groupName;
            for (groupName in item.customizing) {
                if (item.customizing.hasOwnProperty(groupName)) {
                    options.push(groupName + ': ' + item.customizing[groupName]);
                }
            }

            return elm('tr', [
                elm('td', item.id),
                elm('td', elm('div', elm('span', elm('img', null, {src: item.avatar})), {'class': 'image aspect-ratio __3x2'})),
                elm('td', elm('a', item.name, {href: item.url, target: '_blank'})),
                elm('td', item.code),
                elm('td', elm('ul', options.map(function (option) {
                    return elm('li', option);
                }))),
                elm('td', formatUsd(item.discountedPrice)),
                elm('td', item.quantity)
            ]);
        }));
        var tableFoot = elm('tfoot', elm('tr', [
            elm('td', null, {colspan: 5}),
            elm('td', 'Total amount: ' + formatUsd(shoppingCartItems.reduce(function (amount, item) {
                return amount + item.quantity * item.discountedPrice;
            }, 0)), {colspan: 2})
        ]));
        appendChildren(table, [tableHead, tableBody, tableFoot]);
    }

    fillUpShoppingCartView();
</script>

<script>
    var form = document.querySelector('.contact-view form');
    form.onsubmit = function (ev) {
        ev.preventDefault();
        var customer = {
            name: '',
            address: '',
            address_2: '',
            city: '',
            province: '',
            country: '',
            postal_code: '',
            phone: '',
            email: ''
        };
        var attr;
        for (attr in customer) {
            if (customer.hasOwnProperty(attr)) {
                customer[attr] = form.querySelector('[name="' + attr + '"]').value;
            }
        }
        console.log(customer);

        var shoppingCartItems = getCacheData('shoppingCartItems', []);
        var order = {
            totalValue: shoppingCartItems.reduce(function (amount, item) {
                return amount + item.quantity * item.discountedPrice;
            }, 0),
            deliveryFee: 0
        };

        submitOrderForm(
            shoppingCartItems,
            order,
            customer,
            function (ProductOrder) {
                console.log('ProductOrder', ProductOrder);
                setCacheData('shoppingCartItems', []);
            },
            function (err) {
                console.log(err);
                setCacheData('shoppingCartItems', []);
            }
        );
    };

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