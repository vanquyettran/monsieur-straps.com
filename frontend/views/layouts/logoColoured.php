<?php
use common\models\SiteParam;

$logo = SiteParam::findOneByName(SiteParam::LOGO_COLOURED);

if ($logo) {
    echo $logo->value;
}
