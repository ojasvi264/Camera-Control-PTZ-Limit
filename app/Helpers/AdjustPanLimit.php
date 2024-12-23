<?php

function getAdjustedPanLimit($panValue) {
    if ($panValue < -180) {
        return -180;
    } elseif ($panValue > 180) {
        return 180;
    }
    return $panValue;
}
