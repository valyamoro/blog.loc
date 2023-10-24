<?php

return [
    '#^regsitry?#' => [
        'controller' => 'registry',
        'model' => 'registry',
        'view' => '',
    ],
    '#^auth/registry?#' => [
        'controller' => 'registry',
        'model' => 'registry',
        'view' => 'auth/registry',
    ],
    '#^auth/notification?#' => [
        'controller' => 'notification',
        'model' => 'notification',
        'view' => 'auth/notification',
    ],
];
