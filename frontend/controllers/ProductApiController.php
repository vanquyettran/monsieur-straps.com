<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/15/2018
 * Time: 1:15 AM
 */

namespace frontend\controllers;


use backend\models\ProductOrderToProduct;
use common\models\ProductContact;
use common\models\ProductOrder;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;

class ProductApiController extends Controller
{
    public function actionSaveProductOrder()
    {
        if (!\Yii::$app->request->isPost) {
            throw new BadRequestHttpException('Invalid Request. Request must be POST.');
        }

        $products_json = \Yii::$app->request->post('products');
        $order_json = \Yii::$app->request->post('order');
        $customer_json = \Yii::$app->request->post('customer');

        if (!$products_json || !$order_json || !$customer_json) {
            throw new BadRequestHttpException('Some params were missing.');
        }

        $products = json_decode($products_json, true);
        $order = json_decode($order_json, true);
        $customer = json_decode($customer_json, true);

        try {
            $errors = [];

            $model = new ProductOrder();
            $model->status = ProductOrder::STATUS__NEW;
            // Customer
            $model->customer_name = $customer['name'];
            $model->customer_phone = $customer['phone'];
            $model->customer_email = $customer['email'];
            $model->customer_address = $customer['address'];
            $model->customer_address_2 = $customer['address_2'];
            $model->customer_country = $customer['country'];
            $model->customer_province = $customer['province'];
            $model->customer_city = $customer['city'];
            $model->customer_postal_code = $customer['postal_code'];
            // Order
            $model->delivery_fee = $order['deliveryFee'];
            $model->total_value = $order['totalValue'];

            $junctionAttributesList = [];

            if ($model->save()) {
                foreach ($products as $product) {
                    $junction = new ProductOrderToProduct();
                    $junction->product_order_id = $model->id;
                    $junction->product_id = $product['id'];
                    $junction->product_name = $product['name'];
                    $junction->product_code = $product['code'];
                    $junction->product_price = $product['price'];
                    $junction->product_discounted_price = $product['discountedPrice'];
                    $junction->product_quantity = $product['quantity'];
                    $junction->product_customizing = json_encode($product['customizing'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
                    if ($junction->product_customizing === '[]') {
                        $junction->product_customizing = '{}';
                    }

                    if ($junction->save()) {
                        $junctionAttributesList[] = $junction->attributes;
                    } else {
                        $errors['ProductOrderToProduct'][] = $junction->errors;
                    }
                }
            } else {
                $errors['ProductOrder'] = $model->errors;
            }

            return [
                'ProductOrder' => $model->attributes,
                'ProductOrderToProduct' => $junctionAttributesList,
                'errors' => $errors
            ];
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function actionSaveProductContact() {
        if (!\Yii::$app->request->isPost) {
            throw new BadRequestHttpException('Invalid Request. Request must be POST.');
        }

        $contact = new ProductContact();

        $contact->customer_name = \Yii::$app->request->post('customer_name');
        $contact->customer_phone = \Yii::$app->request->post('customer_phone');
        $contact->product_id = \Yii::$app->request->post('product_id');
        $contact->type = ProductContact::TYPE__ADVISORY_REQUEST;
        $contact->status = ProductContact::STATUS__NEW;

        if ($contact->save()) {
            return 'success';
        } else {
            return $contact->errors;
        }
    }
}
