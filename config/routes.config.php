<?php

$baseConfig['routes'] = [
    0 => [
        'GET',
        '/',
        'index',
    ],

    1 => [
        'GET',
        '/index[/{action}]',
        'index',
    ],

    2 => [
        ['GET', 'POST'],
        '/users[/{action}[/{id:[0-9]+}]]',
        'user',
    ],

    3 => [
        ['GET', 'POST'],
        '/products[/{action}[/{id:[0-9]+}]]',
        'product',
    ],
    4 => [
        'GET',
        '/baz[/{action}]',
        'specialmodule/index',
    ],
    5 => [
        'GET',
        '/admin[/{action}]',
        'specialmodule/index',
    ],
    6 => [
        'GET',
        '/foo[/{action}]',
        'strangeModule/foo',
    ],
];