<?php

return [
    'default' => [
        'name' => 'required|max:255|string',
    ],
    'update' => [
        'id' => 'required|integer',
    ],
    'destroy' => [
        'id' => 'required|integer',
        'name' => ''
    ],
];
