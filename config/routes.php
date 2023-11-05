<?php

return [
    '#^login?#' => [
        'controller' => 'login',
        'model' => 'login',
        'view' => '',
    ],
    '#^auth/login?#' => [
        'controller' => 'login',
        'model' => 'login',
        'view' => 'auth/login',
    ],
    '#^registry?#' => [
        'controller' => 'registry',
        'model' => 'registry',
        'view' => '',
    ],
    '#^auth/registry?#' => [
        'controller' => 'registry',
        'model' => 'registry',
        'view' => 'auth/registry',
    ],
    '#^auth/redirect_registry?#' => [
        'controller' => 'redirectRegistry',
        'model' => 'redirectRegistry',
        'view' => 'auth/redirect_registry',
    ],
];
