@extends('emails.layouts.basic')

@section('content')
    <tr сlass="hello">
        <td style="font: 23px Arial,sans-serif; font-weight: 700; -webkit-text-size-adjust: none; padding-bottom: 75px;">
            @lang('email_texts.hello') !
        </td>
    </tr>
    <tr class="content">
        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none; padding-bottom: 10px;">
            @lang('email_texts.admin_reset_password_text_1')
        </td>
    </tr>
    <tr class="content">
        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none; padding-bottom: 10px;">
            @lang('email_texts.admin_reset_password_text_2')
        </td>
    </tr>
   <!-- <tr>
        <td style="padding: 15px 30px; text-align: center;">
            <p style="margin-top: 30px; font-family: 'Roboto', sans-serif;font-size:24px; font-weight: 700;color: #070a14;">
                @lang('email_texts.reset_password_title')</p>
        </td>
    </tr>
    <tr>
        <td style="padding: 30px 15px 60px 15px;;font-family: 'Roboto', sans-serif;  line-height: 1.5;  " valign="top">
            <div style="margin-top: 45px; font-size: 15px;">
                <p style="font-size: 18px; font-weight: 700; color: #404040; ">
                    @lang('email_texts.hello') !
                </p>
                <p style="margin-top: 25px">
                    @lang('email_texts.admin_reset_password_text_1')
                </p>
                <p style="margin: 25px 0 0 0">
                    @lang('email_texts.admin_reset_password_text_2')
                    <span style="color: #070a14; font-weight: 700;">
                                    {{$password}}
                                </span>
                </p>
                <div style="margin: 30px 0 0 0; padding-top: 30px; border-top: 1px solid #d5d5d5">
                    <p style="margin: 0;">
                        @lang('email_texts.thank_you')
                        <br/>
                        <span style="font-weight: 700; color: #070a14">
                                    @lang('email_texts.team')
                                </span>
                    </p>
                </div>

            </div>
        </td>
    </tr> -->
@endsection
