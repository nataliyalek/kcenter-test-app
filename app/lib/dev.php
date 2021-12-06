<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

function debug($par){
    echo '<pre>';
    var_dump($par);
    echo '</pre>';
    exit();
}