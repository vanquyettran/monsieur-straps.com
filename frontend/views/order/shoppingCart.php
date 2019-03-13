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
                <div>
                    <label>Name</label>
                    <input type="text" name="name" placeholder="">
                </div>
                <div>
                    <label>Address</label>
                    <div>
                        <input type="text" name="address" placeholder="Address">
                        <input type="text" name="country" placeholder="Country">
                        <input type="number" name="postal_code" placeholder="Postal code">
                    </div>
                </div>
                <div>
                    <label>Phone number</label>
                    <input type="tel" name="phone" placeholder="">
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" name="email" placeholder="">
                </div>

            </form>
        </div>
        <div class="payment-view">
            <div class="title">Payment</div>
            <div>
                <a class="payment-method">Pay with VISA</a>
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
            elm('td', 'Sale Price'),
            elm('td', 'Quantity')
        ]));
        var tableBody = elm('tbody', shoppingCartItems.map(function (item) {
            return elm('tr', [
                elm('td', item.id),
                elm('td', elm('img', null, {src: item.avatar})),
                elm('td', elm('a', item.name, {href: item.url, target: '_blank'})),
                elm('td', item.code),
                elm('td', formatUsd(item.discountedPrice)),
                elm('td', item.quantity)
            ]);
        }));
        var tableFoot = elm('tfoot', elm('tr', [
            elm('td', null, {colspan: 4}),
            elm('td', 'Total amount: ' + formatUsd(shoppingCartItems.reduce(function (amount, item) {
                return amount + item.quantity * item.discountedPrice;
            }, 0)), {colspan: 2})
        ]));
        appendChildren(table, [tableHead, tableBody, tableFoot]);
    }

    fillUpShoppingCartView();
</script>