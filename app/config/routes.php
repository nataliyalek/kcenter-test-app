<?php
$routes = [
    '' => [
        'controller' => 'main',
        'action' => 'index',
    ],
    'priceinfoapi/subscribe' => [
        'controller' => 'priceinfoapi',
        'action'    => 'subscribe',
        ],
    'priceinfoapi/get_price' => [
        'controller' => 'priceinfoapi',
        'action'    => 'getPrice',
    ],
];

return $routes;

