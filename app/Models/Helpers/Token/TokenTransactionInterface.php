<?php

declare(strict_types=1);

namespace App\Models\Helpers\Token;

/**
 * Interface TokenTransactionInterface
 * @package App\Models\Helpers\Token
 */
interface TokenTransactionInterface
{
    public const TYPE_CARD_TRANSACTION = 'card_transaction';

    public const TYPE_CRYPTO_TRANSACTION = 'blockchain_transaction_transfer';

    public const TYPE_SET_FEE_TRANSACTION = 'blockchain_transaction_set_fee';

    public const TYPE_CREATE_TOKEN_TRANSACTION = 'blockchain_transaction_create_token';

    public const TYPE_CREATE_COLLECTION_TRANSACTION = 'blockchain_transaction_create_collection';

    public const TYPE_ALLOWANCE_TRANSACTION = 'blockchain_transaction_allowance';

    public const TYPE_MAKE_TOKEN_RESALE_TRANSACTION = 'blockchain_transaction_make_token_resale';

    public const DEFAULT_CURRENCY = 'USD';

    public const HIDDEN_TRANSACTIONS = [
        self::TYPE_CREATE_COLLECTION_TRANSACTION,
        self::TYPE_CREATE_TOKEN_TRANSACTION,
        self::TYPE_ALLOWANCE_TRANSACTION,
    ];

    public const BLOCKCHAIN_TRANSACTIONS = [
        self::TYPE_CREATE_COLLECTION_TRANSACTION,
        self::TYPE_CREATE_TOKEN_TRANSACTION,
        self::TYPE_ALLOWANCE_TRANSACTION,
        self::TYPE_CRYPTO_TRANSACTION,
        self::TYPE_SET_FEE_TRANSACTION,
        self::TYPE_MAKE_TOKEN_RESALE_TRANSACTION
    ];

    // Statuses

    public const UNCONFIRMED_STATUS = 'Unconfirmed';

    public const DROPPED_OR_REPLACED_STATUS = "Dropped or Replaced";

    public const CONFIRMED_STATUS = 'Confirmed';

    public const PENDING_STATUS = 'Pending';

    public const REJECTED_STATUS = 'Rejected';

    public const REJECTED_BLOCKCHAIN_STATUS = 'Rejected in blockchain';

    public const REVERTED_BY_CONTRACT = 'Reverted action by contract';

    public const CREATED_BY_ADMIN = 'admin';

    public const CREATED_BY_USER = 'user';

    public const RECEIVER_ADDRESS_DEFAULT = '0x0';
}
