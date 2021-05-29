<?php


namespace App\Models\Helpers;


interface CryptoServiceInterface
{

    function getImplementClass();

    function formUrlRequest(string $method_slug, ?array $params): string;

}
