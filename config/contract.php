<?php

return [
    'address' => env('CONTRACT_ADDRESS', 'Set contract address'),
    'abi' => file_get_contents(__DIR__.'/contract/abi.json'),
];
