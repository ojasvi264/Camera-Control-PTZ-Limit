<?php

function getAdjustedPanLimit($panValue) {
    if ($panValue > 180) {
        return $panValue - 360;
    } elseif ($panValue < -180) {
        return $panValue + 360;
    }
    return $panValue;
}
