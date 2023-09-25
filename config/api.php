<?php

return [
    'pagination' => [
        'perPage' => [
            'default' => 50,
            'min' => 10,
            'max' => 100,
            'inputKey' => 'limit'
        ],
        'pageName' => 'page',
    ],
    'resource' => \Illuminate\Http\Resources\Json\JsonResource::class,
    'locale' => null,
    'default-order-direction' => 'desc',
];
