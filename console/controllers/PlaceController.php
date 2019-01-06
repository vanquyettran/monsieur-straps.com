<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/14/2018
 * Time: 1:12 AM
 */

namespace console\controllers;


use common\models\PlaceT1;
use common\models\PlaceT2;
use common\models\PlaceT3;
use yii\console\Controller;

class PlaceController extends Controller
{
    public function actionExportTreeToJson() {
        $data = array_map(function ($placeT1) {
            /**
             * @var $placeT1 PlaceT1
             */

            return [
                $placeT1->id,
                $placeT1->shortName(),
                array_map(function ($placeT2) {
                    /**
                     * @var $placeT2 PlaceT2
                     */

                    return [
                        $placeT2->id,
                        $placeT2->fullName(),
                        array_map(function ($placeT3) {
                            /**
                             * @var $placeT3 PlaceT3
                             */

                            return [
                                $placeT3->id,
                                $placeT3->fullName(),
                            ];

                        }, $placeT2->getPlaceT3s()->orderBy('type asc, name asc')->all()),
                    ];

                }, $placeT1->getPlaceT2s()->orderBy('type asc, name asc')->all()),
            ];

        }, PlaceT1::find()->orderBy('type asc, name asc')->all());

        $file = fopen(\Yii::getAlias('@frontend/web/json/place-tree.json'), 'w');
        fwrite($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        fclose($file);

        $file = fopen(\Yii::getAlias('@frontend/web/json/place-tree.min.json'), 'w');
        fwrite($file, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        fclose($file);

        $file = fopen(\Yii::getAlias('@frontend/web/js/place-tree.min.js'), 'w');
        fwrite($file, 'window.placeTree=' . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ';');
        fclose($file);


    }
}