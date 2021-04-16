<?php

namespace App\Exceptions;

interface ErrorMessages
{
    public const IMAGE_NOT_EXIST = 'image_not_exist';

    public const INVALID_CREDENTIALS = 'invalid_credentials';

    public const MODEL_LOAD_ERROR = 'model_not_loaded';

    public const UNAUTHORIZED = 'unauthorized';

    public const MODEL_NOT_FOUND = 'model_not_found';

    public const MODEL_NOT_UPDATED = 'model_not_updated';

    public const MODEL_NOT_CREATED = 'model_not_created';

    public const MODEL_NOT_DELETED = 'model_not_deleted';

    public const UNAUTHENTICATED = 'unauthenticated';

    public const USER_NOT_FOUND = 'user_not_found';

    public const USER_NOT_EXIST = 'user_not_exist';

    public const USERNAME_NOT_EXIST = 'username not exist';

    public const USERNAME_ALREADY_ATTEMPTED = 'username_already_attempted';

    public const USER_BLOCKED = 'user_blocked';

    public const ADMIN_NOT_FOUND = 'admin_not_found';

    public const ADMIN_NOT_EXIST = 'admin_not_exist';

    public const ADMIN_BLOCKED = 'admin_blocked';

    public const EMAIL_NOT_CONFIRMED = 'email_not_confirmed';

    public const TOKEN_NOT_PROVIDED = 'token_not_provided';

    public const TOKEN_EXPIRED = 'token_expired';

    public const PHONE_NOT_FOUND = 'phone_not_found';

    public const INVALID_TOTP_CODE = 'invalid_totp_code';

    public const TOTP_CODE_ISNT_SENDED = 'totp_code_isnt_sended';

    public const TWO_FA_AUTH_ENABLED = 'two_fa_auth_enabled';

    public const TOKEN_INVALID = 'token_invalid';

    public const INVALID_TOKEN = 'invalid_token';

    public const NO_PERMISSIONS = 'no_permissions';

    public const DATA_NOT_FOUND = 'data_not_found';

    public const HTTP_NOT_FOUND = 'http_not_found';

    public const FILE_UPLOAD = 'error_during_uploading_file';

    public const DATA_IS_ALREADY_CREATED = 'data_is_already_created';

    public const POST_TOO_LARGE = 'post_too_large';

    public const ALREADY_REQUESTED_CHANGE_EMAIL = 'already_requested_email_change';

    public const BINDING_ERROR = 'binding_resolution_exception';

    public const BOT_USER_NOT_ADDED = 'bot_user_not_added';

    public const SOMETHING_WENT_WRONG = 'something_went_wrong';

    public const ACCESS_DENIED = 'access_denied';

    public const DATE_NOT_ALLOWED = 'date not allowed for this collection type';

    //KYC
    public const BIRTH_DATE_CANT_BE_GREATER_THAN_NOW = 'birth_date_cant_be_greater_than_now';

    public const VERIFICATION_ALREADY_EXISTS = 'verification_already_exists';

    public const USER_NOT_VERIFIED = 'user_not_verified';

    public const USER_KYC_ALREADY_REJECTED = 'user_kyc_already_rejected';

    public const USER_KYC_ALREADY_VERIFIED = 'user_kyc_already_verified';
}
