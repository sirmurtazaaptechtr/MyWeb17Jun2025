<?php
function pr ($input) {
    echo "<pre>";
    print_r($input);
    echo "</pre>";
}

function prx ($input) {
    echo "<pre>";
    print_r($input);
    echo "</pre>";
    die();
}

function safe_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
