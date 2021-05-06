<?php


namespace App\Models\Helpers;


interface CryptoServiceInterface
{
    function confirmRegistration(string $transaction_id): bool|array;

    function receiveDataTransaction(string|int $transaction_id);

    function receiveDataContractTransactions();
}
