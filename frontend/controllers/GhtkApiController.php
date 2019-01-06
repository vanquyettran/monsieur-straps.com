<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 3/4/2017
 * Time: 1:25 AM
 */

namespace frontend\controllers;

use Yii;
use yii\base\Controller;

class GhtkApiController extends Controller
{
    public function actionGetFee()
    {
        header('Access-Control-Allow-Origin: *');

        $province = Yii::$app->request->getBodyParam('province');
        $district = Yii::$app->request->getBodyParam('district');
        $weight = Yii::$app->request->getBodyParam('weight');
        $value = Yii::$app->request->getBodyParam('value');

//        if (!$province || !$district || !$weight || !$value) {
//            return false;
//        }

        $data = array(
//            "pick_address_id" => 2342057,
            "pick_province" => "Hà Nội",
            "pick_district" => "Quận Hoàn Kiếm",
            "province" => $province,
            "district" => $district,
            "weight" => $weight,
            "value" => $value,
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://services.giaohangtietkiem.vn/services/shipment/fee?" . http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => array(
                "Token: 68BC9503B7eF82bEc0D9348d5988C3be38985A04",
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}