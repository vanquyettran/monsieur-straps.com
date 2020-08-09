<?php
use common\models\SiteParam;

$logo = SiteParam::findOneByName(SiteParam::LOGO_WHITE);

if ($logo) {
    echo $logo->value;
}
