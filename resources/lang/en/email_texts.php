<?php

return [
    'dear_customer'      => 'Dear Customer,',
    'thank_you'          => 'Thank you,',
    'regards'            => 'Ragards,',
    'team'               => config('app.name', 'Laravel') . ' Team.',
    'hello'              => 'Hello',
    'registration_title' => 'Please verify your email.',

    'admin_reset_password_text_1' => 'We received a request to reset your password for your Future trading platform
     account. We are here to help!',
    'admin_reset_password_text_2' => 'Use a new password:',
    'reset_password_title'        => 'Password reset',

    'change_email_title' => 'Email change',
    'change_email'       => 'A mail change has been requested. In two weeks, your email will be changed.
    If you did not make this request, please ignore this email.',

    'new_email_title'            => 'New email',
    'new_email_1'                => 'A mail change has been requested. To confirm your new email, please
     use the following link on the website.',
    'new_email_2'                => 'If you did not make this request, please ignore this email.',

    //GENERAL
    'current_year'               => date('Y'),
    'have_a_good_day'            => 'Have a good day.',
    'questions_to_support'       => 'If you have any questions, please contact our support team.',
    'reset_password_text_2'      => 'If you did not make this request, please ignore this email.',

    //REGISTRATION
    'register_text'              => 'Thank you for registering in ' . config('app.name', 'Laravel')
        . '! To confirm the registration, follow the link below:',
    'verify_email'               => '[Verify My Email]',
    'link_prompt'                => 'If the link did not open, copy it to the clipboard, paste it into the address bar of the browser, press Enter',
    'invite_text'                => 'You were invited in ' . config('app.name', 'Laravel'),
    'invite_link'                => 'To confirm the registration, follow the link below:',
    'super_admin_invite_text'    => 'You have successfully invited a new administrator',
    'your_password_is'           => 'Your password is',
    'email_is'                   => 'Email',
    'password_is'                => 'Password',

    //RESET PASSWORD
    'reset_password_text_1'      => 'A password reset has been requested. To reset your password, please use the following
     link on the website.',

    //MANAGEMENT
    'blocked_account'            => 'Your account has been blocked by the Administrator.',
    'unblocked_account'          => 'Your account has been successfully restored',
    'restore_account'            => 'To restore your account, contact our support team',
    'deleted_account'            => 'Your account has been deleted by the Administrator.',
    'delete_account_questions'   => 'If you have any questions, please contact our support team.',

    //2FA
    'enabled_2fa_1'              => 'You have activated two-factor authentication for your account.',
    'enabled_2fa_2'              => 'To disable two-factor authentication, go to your account settings on the site.',
    'disabled_2fa_1'             => 'You have disabled two-factor authentication for your account',
    'disabled_2fa_2'             => 'To activate two-factor authentication, go to your account settings on the site.',

    //KYC
    'kyc_accepted' => 'Your verification has been successfully confirmed by the administrator',

    //PAYMENTS
    'successful_payment_token_1' => 'You have successfully completed the purchase of a token',
    'successful_payment_token_2' => 'using a bank card payment.',
    'token_address_is' => 'Your token address is',

    //Token reminding
    'remind_1' => 'We remind you that the token',
    'remind_2' => 'is open for sale',
    'remind_link' => 'You can view and buy it by clicking on the link',

    //Pack reminding
    'pack_remind_1' => 'We remind you that the pack',
    'pack_remind_2' => 'is open for sale',
    'pack_remind_link' => 'You can view and buy it by clicking on the link',

    //Token purchase

    'token_purchased_1' => 'Your token was purchased by',
    'token_purchased_2' => 'To transfer the token, follow the ',
    'token_purchased_3' => 'Your token was successfully purchased by',
    'token_purchased_4' => 'Token with id ',
    'token_purchased_5' => 'was successfully transferred to buyer wallet address',
    'token_purchased_6' => 'but token transfer transaction was rejected',
    'token_purchased_7' => 'Please try again, follow the ',
];
