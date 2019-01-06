<?php
use common\models\SiteParam;

foreach (SiteParam::findAllByNames([SiteParam::TRACKING_CODE]) as $tracking_code) {
    echo $tracking_code->value;
}