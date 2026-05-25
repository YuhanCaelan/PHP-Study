<?php

function compareThreeNumbers($a, $b, $c)
{
    $numbers = [$a, $b, $c];
    rsort($numbers, SORT_NUMERIC);

    return $numbers;
}
