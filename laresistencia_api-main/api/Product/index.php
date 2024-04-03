<?php
require_once '../../vendor/autoload.php';
use Controllers\Product\ProductController;
use Utils\api\Server\Server;

$server = new Server(new ProductController());
$server
    ->get(function (Server $s) {
        $s->controller->get();
    })
    ->post(function (Server $s) {
        echo "post";
    })
    ->put(function (Server $s) {
        echo "put";
    })
    ->delete(function (Server $s) {
        echo "delete";
    })
    ->start();
