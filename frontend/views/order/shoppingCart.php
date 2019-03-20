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
        <div id="payment-view" class="payment-view hidden">
            <div class="title">Checkout with Paypal</div>
            <div class="paypal-button-container"></div>
        </div>
        <!--<div class="contact-view">
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
        <div>
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_cart">
                <input type="hidden" name="business" value="5Y8HZYMY7KUSW">
                <input type="hidden" name="lc" value="VN">
                <input type="hidden" name="button_subtype" value="services">
                <input type="hidden" name="no_note" value="0">
                <input type="hidden" name="upload" value="1">
                <input type="hidden" name="cn" value="Add special instructions to the seller:">
                <input type="hidden" name="no_shipping" value="2">
                <input type="hidden" name="amount_1" value="120">
                <input type="hidden" name="amount_2" value="1200">
                <input type="hidden" name="item_name_1" value="Cart Checkout">
                <input type="hidden" name="item_number_1" value="3">
                <input type="hidden" name="item_name_2" value="Cart Checkout 2">
                <input type="hidden" name="item_number_2" value="2">
                <input type="hidden" name="currency_code" value="USD">
                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
        </div>-->
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

//    document.querySelector('[name="amount"]').value = getCacheData('shoppingCartItems', []).reduce(function (amount, item) {
//        return amount + item.quantity * item.discountedPrice;
//    }, 0);
</script>

<script>
//    var form = document.querySelector('.contact-view form');
//    form.onsubmit = function (ev) {
//        ev.preventDefault();
//        var customer = {
//            name: '',
//            address: '',
//            address_2: '',
//            city: '',
//            province: '',
//            country: '',
//            postal_code: '',
//            phone: '',
//            email: ''
//        };
//        var attr;
//        for (attr in customer) {
//            if (customer.hasOwnProperty(attr)) {
//                customer[attr] = form.querySelector('[name="' + attr + '"]').value;
//            }
//        }
//        console.log(customer);
//
//        var shoppingCartItems = getCacheData('shoppingCartItems', []);
//        var order = {
//            totalValue: shoppingCartItems.reduce(function (amount, item) {
//                return amount + item.quantity * item.discountedPrice;
//            }, 0),
//            deliveryFee: 0
//        };
//
//        submitOrderForm(
//            shoppingCartItems,
//            order,
//            customer,
//            function (ProductOrder) {
//                console.log('ProductOrder', ProductOrder);
//                setCacheData('shoppingCartItems', []);
//            },
//            function (err) {
//                console.log(err);
//                setCacheData('shoppingCartItems', []);
//            }
//        );
//    };
//
//    function submitOrderForm(products, order, customer, onSuccess, onError) {
//        var fd = new FormData();
//
//        fd.append('products', JSON.stringify(products));
//        fd.append('order', JSON.stringify(order));
//        fd.append('customer', JSON.stringify(customer));
//        fd.append('<?//= Yii::$app->request->csrfParam ?>//', '<?//= Yii::$app->request->csrfToken ?>//');
//
//        var xhr = new XMLHttpRequest();
//
//        xhr.open('POST', '<?//= \yii\helpers\Url::to(['product-api/save-product-order']) ?>//', true);
//        xhr.onload = function () {
//            var res = JSON.parse(xhr.responseText);
//            if (res.ProductOrder && res.ProductOrder.id) {
//                onSuccess(res.ProductOrder);
//            } else {
//                onError(res.errors);
//            }
//        };
//        xhr.onerror = function () {
//            onError({status: xhr.status, statusText: xhr.statusText});
//        };
//        xhr.send(fd);
//    }
</script>

<script src="https://www.paypal.com/sdk/js?client-id=AeDdMjpwHKx05zK8ge1h79gPUeUhjz6c4xx83Uy1U0M1_jbWD969X56O_tHKXmlrghzx19ZIb3EvLdj3"></script>
<script>
    function initPaypalButton() {
        var shoppingCartItems = getCacheData('shoppingCartItems', []);
        if (shoppingCartItems.length === 0) {
            return;
        }

        var paymentView = document.querySelector('#payment-view');
        paymentView.classList.remove('hidden');

        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    "intent": "CAPTURE",
    //                "payer": {"name": {"given_name": "Jane", "surname": "Doe"}},
                    "purchase_units": [{
                        "description": "This is the payment transaction description.",
                        "amount": {
    //                        "currency": "USD",
    //                        "details": {"subtotal": 300, "shipping": 20, "tax": 30},
                            "value": shoppingCartItems.reduce(function (amount, item) {
                                return amount + item.quantity * item.discountedPrice;
                            }, 0),
                            "currency_code": "USD"
                        }
    //                    ,
    //                    "shipping": {
    //                        "name": {"full_name": "Jane Doe"},
    //                        "address": {
    //                            "address_line_1": "2211 North Street",
    //                            "address_line_2": "",
    //                            "admin_area_1": "San Jose",
    //                            "admin_area_2": "CA",
    //                            "country_code": "US",
    //                            "postal_code": "95123"
    //                        }
    //                    }
                    }],
                    "application_context": {}
                });
            },
            onApprove: function(data, actions) {
                // Capture the funds from the transaction
                return actions.order.capture().then(function(details) {
                    // Show a success message to your buyer
                    popup('Transaction completed by ' + details.payer.name.given_name);
                    // Call your server to save the transaction
                    return fetch('<?= \yii\helpers\Url::to(['product-api/paypal-transaction-complete']) ?>', {
                        method: 'post',
                        body: JSON.stringify({
                            orderID: data.orderID
                        })
                    });
                });
            },
            onError: function (err) {

            }
        }).render('#payment-view .paypal-button-container');
    }

    initPaypalButton();
</script>