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
            <div class="table-wrapper">
                <table>
                    <!-- filled by JS -->
                </table>
            </div>
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

<script src="https://www.paypal.com/sdk/js?client-id=<?= \Yii::$app->params['paypal.clientID'] ?>"></script>
<script>
    function initPaypalButton() {
        var shoppingCartItems = getCacheData('shoppingCartItems', []);
        if (shoppingCartItems.length === 0) {
            return;
        }

        var paymentView = document.querySelector('#payment-view');
        paymentView.classList.remove('hidden');

        var total_value = shoppingCartItems.reduce(function (amount, item) {
            return amount + item.quantity * item.discountedPrice;
        }, 0);

        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    "intent": "CAPTURE",
                    "purchase_units": [{
                        "description": "This is the payment transaction description.",
                        "amount": {
                            "value": total_value,
                            "currency_code": "USD"
                        }
                    }],
                    "application_context": {}
                });
            },
            onApprove: function(data, actions) {
                // Capture the funds from the transaction
                return actions.order.capture().then(function(details) {
                    // Show a success message to your buyer
                    console.log('Transaction completed by ' + details.payer.name.given_name);
                    // Call your server to save the transaction
                    var xhr = new XMLHttpRequest();
                    xhr.onload = function () {
                        var res = JSON.parse(xhr.responseText);
                        switch (res.status) {
                            case 'success':
                                popup('Successfully! Thanks for your payment.');
                                break;
                            case 'db_insert_error':
                                popup('Failed save your order. Please contact us for support. Paypal_Order_ID: ' + data.orderID);
                                break;
                            case 'paypal_error_res':
                                popup('Failed to get the order details from PayPal. Please contact us for support. Paypal_Order_ID: ' + data.orderID);
                                break;
                            default:
                                popup('Something went wrong! Please contact us for support. Paypal_Order_ID: ' + data.orderID);
                        }

                        // clear cart
                        setCacheData('shoppingCartItems', []);
                    };
                    xhr.onerror = function () {
                        popup('No internet connection.');
                    };
                    xhr.onloadend = function () {
                        document.querySelector('body').classList.remove('loading-opacity');
                    };
                    xhr.open("POST", "<?= \yii\helpers\Url::to(['product-api/paypal-transaction-complete']) ?>", true);

                    var fd = new FormData();
                    fd.append('products', JSON.stringify(getCacheData('shoppingCartItems', [])));
                    fd.append('paypal_order_id', data.orderID);
                    fd.append('total_value', total_value);

                    document.querySelector('body').classList.add('loading-opacity');
                    xhr.send(fd);
                });
            },
            onError: function (err) {

            }
        }).render('#payment-view .paypal-button-container');
    }

    initPaypalButton();
</script>