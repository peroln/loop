<?php


namespace App\Models\Helpers;


interface CryptoServiceInterface
{
//    function confirmRegistration(string $transaction_id): bool|array;

    function receiveDataTransaction(string|int $transaction_id);

    function receiveDataContractTransactions();

    function getImplementClass();

//    function extractRegisteredWallets();

    function formUrlRequest(string $method_slug, ?array $params): string;

//    function extractDataFromRegisterTransaction(array $data);
}
