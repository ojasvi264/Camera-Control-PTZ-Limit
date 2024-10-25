<?php

function getAdjustedTiltLimit($panValue) {
    if ($panValue < -90) {
        return -90;
    } elseif ($panValue > 20) {
        return 20;
    }
    return $panValue;
}
