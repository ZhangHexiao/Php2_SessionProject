<?php

$baseConfig['routes'] = [
    0 => [
        ['GET', 'POST'],
        '/',
        'index',
    ],
    1 => [
        ['GET', 'POST'],
        '/index[/{action}]',
        'product',
    ],
    2 => [
        ['GET', 'POST'],
        '/products[/{action}[/{id:[0-9]+}]]',
        'product',
    ],
    3 => [
        'GET',
        '/baz[/{action}]',
        'specialmodule/index',
    ],
    4 => [
        'GET',
        '/admin[/{action}]',
        'specialmodule/index',
    ],

    5 => [
        'GET',
        '/foo[/{action}]',
        'StrangeController/Controller/Foo',
    ],


];