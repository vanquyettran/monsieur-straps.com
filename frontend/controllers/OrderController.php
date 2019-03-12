<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 3/12/2019
 * Time: 7:31 AM
 */

namespace frontend\controllers;


class OrderController extends BaseController
{
    public function actionShoppingCart() {
        return $this->render('shoppingCart');
    }
}