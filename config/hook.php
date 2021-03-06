<?php

return [
    // Hook http timeout in seconds.
    'timeout' => env('HOOK_TIMEOUT', 10),

    // Number of seconds that will be wait to retry hook.
    'retry_delay' => env('HOOK_RETRY_DELAY', 2),
];
