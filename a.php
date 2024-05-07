<?php

function f1 ($n, $s)
{
    echo filter_var($n, FILTER_SANITIZE_SPECIAL_CHARS) . PHP_EOL;
    echo filter_var($s, FILTER_VALIDATE_INT);
}

f1("<div>aa</div>", "5");
echo PHP_EOL;
