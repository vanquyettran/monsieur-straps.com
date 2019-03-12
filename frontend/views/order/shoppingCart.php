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
            <!-- filled by JS -->
        </div>
    </div>
</div>


<script>
    function fillUpShoppingCartView() {
        var shoppingCartView = document.querySelector('#shopping-cart-view');
        var shoppingCartItems = getCacheData('shoppingCartItems', []);

        if (shoppingCartItems.length === 0) {
            shoppingCartView.appendChild(elm('div', 'Your cart currently is empty.', {'class': 'cart-title'}));
        }

        var tableHead = elm('thead', elm('tr', [
            elm('td', 'ID'),
            elm('td', 'Name'),
            elm('td', 'Code'),
            elm('td', 'Sale Price'),
            elm('td', 'Quantity')
        ]));
        var tableBody = elm('tbody', shoppingCartItems.map(function (item) {
            return elm('tr', [
                elm('td', item.id),
                elm('td', item.name),
                elm('td', item.code),
                elm('td', formatUsd(item.discountedPrice)),
                elm('td', item.quantity)
            ]);
        }));
        var tableFoot = elm('tfoot', elm('tr', [
            elm('td', null, {colspan: 3}),
            elm('td', formatUsd(shoppingCartItems.reduce(function (amount, item) {
                return amount + item.quantity * item.discountedPrice;
            }, 0)), {colspan: 2})
        ]));
        var table = elm('table', [tableHead, tableBody, tableFoot]);
        shoppingCartView.appendChild(table);
    }

    fillUpShoppingCartView();
</script>