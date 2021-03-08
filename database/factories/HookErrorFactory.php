<?php

/* @var $factory Factory */

use App\HookError;
use Illuminate\Database\Eloquent\Factory;

$factory->define(HookError::class, function () {
    return [
        'status_code' => "503",
        'response_body' => "Server error"
    ];
});
